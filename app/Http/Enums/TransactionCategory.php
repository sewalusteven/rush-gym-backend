<?php

namespace App\Http\Enums;

enum TransactionCategory : string
{
    case MEMBERSHIP_DEPOSIT = 'MEMBERSHIP_DEPOSIT';
    case SERVICE_INCOME = 'SERVICE_INCOME';
    case EXPENSE = 'EXPENSE';
    case OTHER = 'OTHER';

}
