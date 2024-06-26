<?php

namespace App\Enums\Microsites;

enum MicrositePermissions: string
{
    case ViewAny = 'microsites.view_any';
    case View = 'microsites.view';
    case Create = 'microsites.create';
    case Update = 'microsites.update';
    case Delete = 'microsites.delete';
}
