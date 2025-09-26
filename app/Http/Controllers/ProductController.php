<?php

namespace App\Http\Controllers;

use App\Enums\ListingPlatform;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProductController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $perPage = max(1, min($request->integer('per_page', 15), 100));

        $products = $request->user()->products()
            ->with(['images', 'listings', 'user'])
            ->filter($request->query())
            ->latest()
            ->paginate($perPage)
            ->appends($request->query());

        return ProductResource::collection($products);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $this->validatedData($request);

        $product = DB::transaction(function () use ($request, $validated) {
            $product = $request->user()->products()->create($validated);

            if (! empty($validated['images'])) {
                $this->syncImages($product, $validated['images']);
            }

            if (! empty($validated['listings'])) {
                $this->syncListings($product, $validated['listings']);
            }

            return $product->load(['images', 'listings', 'user']);
        });

        return ProductResource::make($product)
            ->response()
            ->setStatusCode(201)
            ->header('Location', route('products.show', ['product' => $product]));
    }

    public function show(Request $request, Product $product): ProductResource
    {
        $this->ensureOwnership($request, $product);

        return new ProductResource($product->load(['images', 'listings', 'user']));
    }

    public function update(Request $request, Product $product): ProductResource
    {
        $this->ensureOwnership($request, $product);

        $validated = $this->validatedData($request);

        return DB::transaction(function () use ($product, $validated) {
            $product->update($validated);

            if (array_key_exists('images', $validated)) {
                $product->images()->delete();
                $this->syncImages($product, $validated['images'] ?? []);
            }

            if (array_key_exists('listings', $validated)) {
                $product->listings()->delete();
                $this->syncListings($product, $validated['listings'] ?? []);
            }

            return ProductResource::make($product->load(['images', 'listings', 'user']));
        });
    }

    public function destroy(Request $request, Product $product): Response
    {
        $this->ensureOwnership($request, $product);

        $product->delete();

        return response()->noContent();
    }

    protected function ensureOwnership(Request $request, Product $product): void
    {
        if ($product->user_id !== $request->user()->id) {
            throw new NotFoundHttpException();
        }
    }

    protected function validatedData(Request $request): array
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'brand' => ['nullable', 'string', 'max:255'],
            'gender' => ['required', 'in:women,men,unisex'],
            'condition' => ['required', 'in:new_with_tags,new_without_tags,excellent,good,fair'],
            'price' => ['required', 'numeric', 'min:0'],
            'currency' => ['required', 'string', 'size:3'],
            'size' => ['nullable', 'string', 'max:50'],
            'is_sold' => ['sometimes', 'boolean'],
            'notes' => ['nullable', 'string'],
            'images' => ['sometimes', 'array'],
            'images.*.path' => ['required_with:images', 'string', 'max:2048'],
            'images.*.position' => ['nullable', 'integer', 'min:0'],
            'images.*.is_primary' => ['nullable', 'boolean'],
            'listings' => ['sometimes', 'array'],
            'listings.*.platform' => ['required_with:listings', 'in:wallapop,depop,ebay,shopify,vinted'],
            'listings.*.external_id' => ['nullable', 'string', 'max:255'],
            'listings.*.url' => ['nullable', 'url', 'max:2048'],
            'listings.*.listed_at' => ['nullable', 'date'],
            'listings.*.sold_at' => ['nullable', 'date'],
            'listings.*.notes' => ['nullable', 'string'],
        ]);

        $validated['is_sold'] = $validated['is_sold'] ?? false;
        $validated['currency'] = strtoupper($validated['currency']);

        if (isset($validated['gender'])) {
            $validated['gender'] = strtolower($validated['gender']);
        }

        if (isset($validated['condition'])) {
            $validated['condition'] = strtolower($validated['condition']);
        }

        if (isset($validated['listings'])) {
            $validated['listings'] = collect($validated['listings'])
                ->map(function (array $listing): array {
                    $listing['platform'] = strtolower($listing['platform']);

                    return $listing;
                })
                ->all();
        }

        return $validated;
    }

    protected function syncImages(Product $product, array $images): void
    {
        foreach ($images as $index => $image) {
            $product->images()->create([
                'path' => $image['path'],
                'position' => $image['position'] ?? $index,
                'is_primary' => $image['is_primary'] ?? $index === 0,
            ]);
        }
    }

    protected function syncListings(Product $product, array $listings): void
    {
        foreach ($listings as $listing) {
            $product->listings()->create([
                'platform' => ListingPlatform::from($listing['platform'])->value,
                'external_id' => $listing['external_id'] ?? null,
                'url' => $listing['url'] ?? null,
                'listed_at' => $listing['listed_at'] ?? null,
                'sold_at' => $listing['sold_at'] ?? null,
                'notes' => $listing['notes'] ?? null,
            ]);
        }
    }
}
