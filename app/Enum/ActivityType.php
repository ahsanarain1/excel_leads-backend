<?php

namespace App\Enum;

enum ActivityType: string
{
    const TWOFACTOR = 'two_factor_code_generated';
    const REGISTERED = 'registered';
    const LOGINFAILED = 'login_failed';
    const LOGIN = 'login';
    const LOGOUT = 'logout';
    const LEADS_VIEW = 'leads_view';
    const LEAD_DELETE = 'lead_delete';
    const LEAD_COPY = 'lead_copy';
}
