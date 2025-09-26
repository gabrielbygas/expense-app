<?php

namespace Modules\Expenses\Enums;

enum ExpenseCategory: string
{
    case FOOD = 'food';
    case TRANSPORT = 'transport';
    case UTILITIES = 'utilities';
    case ENTERTAINMENT = 'entertainment';
    case OTHER = 'other';
}