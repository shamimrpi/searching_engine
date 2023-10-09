<?php 

use Illuminate\Support\Facades\Auth;
function check_user_access($access)
{
    $_backUrl = route('dashboard');
    if (isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER'])) {
        $_backUrl = $_SERVER['HTTP_REFERER'];
    }

    if (has_user_access($access)) {
        return true;
    } else {
        return redirect($_backUrl)->with('danger', 'You are not authorized to perform this action')->send();
    }
}

function has_user_access($access)
{
    $permissions = Auth::user()->permission->items ?? "{}";
    $access_items = json_decode($permissions, true);
    if (empty($access_items)) {
        return false;
    }
    if (array_key_exists($access, $access_items) || Auth::id() == 1) {
        return true;
    }
    return false;
}
