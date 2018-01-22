<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\User;
use Session;

class PasswordController extends Controller
{
    public function resetPassword(Request $r)
    {
        $key = Session::get('pswdresetkey');
        $key_from_form = $r->key;
        $uid = Session::get('pswdresetuid');
        $user_id = $r->uid;
        if ($key_from_form != $key or $uid != $user_id) {
            return view('common.generic')->with('message_type', 'error')->with('message', 'Unauthorized access');
        }
        if ($key_from_form == $key and $uid == $user_id) {
            $u = User::find($uid);
            $u->password = bcrypt($r->password);
            $u->save();
        }
        Session::forget('pswdresetuid');
        Session::forget('pswdresetkey');
        $message_type = "success";
        $message = "Password reset is successfull. Login using your new password";
        return view("common.generic")->with('message', $message)->with('message_type', $message_type);
    }

}
