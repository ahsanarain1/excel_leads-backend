<?php

namespace App\Enum;

enum PermissionEnum: string
{
    const VIEW_LEADS = 'view_leads';
    const DELETE_LEADS = 'delete_leads';
    const REGISTER_USER = 'register_user';
    const VIEW_STATS = 'view_stats';
}
