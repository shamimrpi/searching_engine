<?php 
function permission_modules()
{
    return [
        "module_permission" => "Business Settings",
    ];
}


function module_permission()
{
    return [
        'user_list' => 'User List',
        'user_access_control' => 'User Access Controle',
        'url_create' => 'Url Create',
        'url_edit' => 'Url Edit',
        'url_show' => 'Url Show',
        'url_delete' => 'Url Delete',
    ];
}