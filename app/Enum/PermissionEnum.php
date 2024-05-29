<?php

namespace App\Enum;

enum PermissionEnum: string
{
    const VIEW_LEADS = 'view leads';
    const COPY_LEADS = 'copy leads';
    const DELETE_LEADS = 'delete leads';
    const EDIT_LEADS = 'edit leads';
    const REGISTER_USER = 'register user';
}
