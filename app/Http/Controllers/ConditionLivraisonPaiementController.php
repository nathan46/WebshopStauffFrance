<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;

class ConditionLivraisonPaiementController extends Controller
{
    public function __construct()
    {
        $this->middleware('condition');
    }
    public function showPage()
    {
        $client = DB::table('STAUFF_webshop_client')
                    ->select('PymntGroup', 'Descript', 'U_GROUPAGE', 'U_FRANCO', 'U_MINI')
                    ->where('CardCode', '=', Auth::user()->CardCode)
                    ->get();

        return view('onglet.account.livraisonPaiement')->with('client', $client[0]);
    }

    public function conditionLivraisonPaiement()
    {
    }
    //
}
