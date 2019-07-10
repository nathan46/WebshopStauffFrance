<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;

class RechercheAvanceeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function affiche()
    {
        return view('onglet.recherche.rechercheAvancee');
    }

    public function afficheRes(Request $request)
    {
        $type = $request->searchtype;

        if ($type == 'articleStauff') {
            return redirect()->action('RechercheController@afficherParCode', ['search_matnr_descr' => $request->search_matnr_descr]);
        } elseif ($type == 'refClient') {
            return redirect()->action('RechercheController@afficherParRefClient', ['search_matnr_descr' => $request->search_custm]);
        } elseif ($type == 'filterinterchange') {
        }
    }
}
