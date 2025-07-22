<?php

namespace App\Helpers;

use App\Models\SLP\Permission;

class TableHelper
{
    public static function roles_in_permission($permission)
    {
        $return = '<span class="d-flex flex-wrap">';
        foreach ($permission->roles as $role) {
            $return .= '<span class="m-1 badge rounded-pill text-bg-primary">'.$role->name.'</span>';
        }
        $return .= '</span>';

        return $return;
    }
}
