<?php

namespace App\Enums;

enum ConditionType: string
{
    case NEW_WITH_TAGS = 'new_with_tags';
    case NEW_WITHOUT_TAGS = 'new_without_tags';
    case EXCELLENT = 'excellent';
    case GOOD = 'good';
    case FAIR = 'fair';
}
