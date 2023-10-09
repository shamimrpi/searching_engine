<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{

    public function index(){

        // check_user_access('user_list');
      
        $model = [];
        $dataset = User::where('id', '!=', Auth::id())->whereNotIn('id', [1])->orderBy('id', 'ASC')->get();
        $model['dataset'] = $dataset;
        return view('users.index', $model);
    }
    public function user_access_by_id($id)
    {
        check_user_access('user_access_control');
        $user = User::with('permission')->where('id', $id)->firstOrFail();
        $permissions = $user->permission ? $user->permission->items : "{}";
        $permissions = json_decode($permissions, true) ?? [];
       
        return view('users.user_access', [
            'page_title' => 'User Access Control',
            'permissions' => $permissions,
            'user' => $user,
        ]);
    }

    public function update_user_access_by_id(Request $request)
    {

        
        $permissions = json_encode($request->access);
        $user = User::with('permission')->findOrFail($request->user_id);

        if ($user->permission == null) {
            $user->permission()->create(['items' => $permissions]);
        } else {
            $user->permission()->update(['items' => $permissions]);
        }

        return redirect('user')->with('success', 'User Permission has been Updated Successfully.');
    }

}
