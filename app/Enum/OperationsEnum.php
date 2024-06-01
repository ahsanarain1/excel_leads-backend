<?php

namespace App\Enum;

enum OperationsEnum: string
{
    const STORE = 'create';
    const DELETE = 'delete';
    const UPDATE = 'update';
}
