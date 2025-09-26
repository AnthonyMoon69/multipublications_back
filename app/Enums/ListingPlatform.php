<?php

namespace App\Enums;

enum ListingPlatform: string
{
    case WALLAPOP = 'wallapop';
    case DEPOP = 'depop';
    case EBAY = 'ebay';
    case SHOPIFY = 'shopify';
    case VINTED = 'vinted';
}
