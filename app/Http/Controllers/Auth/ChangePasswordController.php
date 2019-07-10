<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use DB;

class ChangePasswordController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function index(){
        return view('Auth.passwords.change');
    }

    public function changePassword(Request $request){

        $this->validate($request, [
            'oldpassword' => 'required',
            'password' => 'required|confirmed|min:8'
        ]);

        $hashedPassword = Auth::user()->password;
        if(Hash::check($request->oldpassword, $hashedPassword)){
            $user=User::find(Auth::id());
            $user->password = Hash::make($request->password);
            $user->save();
            $id = Auth::user()->id;
            DB::table('stauff_users')->where('id',$id)->update(['firstTime' => 0]);
            return back()->with('msgSuccess',"Le mot de passe a bien été changé !");
        }else{
            return back()->with('msgError',"Le mot de passe actuel ne correspond pas !");
        }
    }
}
