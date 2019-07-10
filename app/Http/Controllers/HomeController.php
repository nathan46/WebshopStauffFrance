<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Auth;
use DB;

class HomeController extends Controller
{
    /**
    * Create a new controller instance.
    *
    * @return void
    */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
    * Show the application dashboard.
    *
    * @return \Illuminate\Contracts\Support\Renderable
    */
    public function index()
    {
        $id = Auth::user()->id;
        $user = DB::table('stauff_users')->whereId($id)->first();

        if ($user->firstTime === '1') {
            return redirect('change-password');
        } else {
            return view('onglet.home')->with('tabSelect', 'acceuil');
        }




        //return view('home');
    }
}
