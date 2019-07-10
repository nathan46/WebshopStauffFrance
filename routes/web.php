<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/change-password', 'Auth\ChangePasswordController@index')->name('password.change');
Route::post('/change-password', 'Auth\ChangePasswordController@changePassword')->name('password.new');

Route::get('/rechercheAvancee', 'RechercheAvanceeController@affiche')->name('rechercheAvancee.affiche');
Route::post('/rechercheAvancee', 'RechercheAvanceeController@afficheRes')->name('rechercheAvancee.afficheresultat');
Route::get('/recherche', 'RechercheController@afficherParCode')->name('rechercheparcode');
Route::get('/rechercheParRef', 'RechercheController@afficherParRefClient')->name('rechercheparrefclient');
Route::get('/prixStock', 'RechercheController@showPrixStock')->name('prixstock');
Route::get('/productDetails', 'RechercheController@showProductDetails')->name('productdetails');

Route::get('/monCompte', 'MonCompteController@showPage')->name('moncompte.affiche');

Route::get('/monCompte/conditionLivraisonPaiement', 'ConditionLivraisonPaiementController@showPage')->name('conditionlivraisonpaiement');

Route::get('/monCompte/listeCommande', 'ListCommandController@showSelect')->name('commande.afficheselect');
Route::post('/monCompte/listeCommande', 'ListCommandController@showCommand')->name('commande.affichecommande');

//Route::get('/ajouterPanier', 'PanierController@ajouterAuPanier')->name('ajouteraupanier');
Route::get('/panier', 'PanierController@affichePanier')->name('affichepanier');
Route::post('/panier/verifierEtCommander', 'PanierController@verifierEtCommander')->name('verifieretcommander');
Route::post('/ajouterAuPanier', function () {
    if (Request::ajax()) {
        session()->push('panier', array('itemCode' => request('itemCode'), 'desc' => request('desc'), 'qty' => request('qty'), 'complete' => 'false'));
    }
});
Route::post('/savePanierInput', function () {
    if (Request::ajax()) {
        if (request('qty') !== null) {
            $type = 'qty';
        } elseif (request('complete') !== null) {
            $type = 'complete';
        }

        $panier = session('panier');
        $i=-1;
        foreach ($panier as &$p) {
            $i++;
            if (intval(request('index')) === $i) {
                $p[$type] = request($type);
                break;
            }
        }
        session()->put('panier', $panier);
        unset($p);
    }
});

Route::get('/importCommande', 'PanierController@importCommandeAffiche')->name('importcommandeaffiche');
Route::post('/importCommande', 'PanierController@importCommande')->name('importcommande');

Route::get('/requestList', 'RequestListController@afficheRequestList')->name('afficherequestlist');
Route::post('/ajouterRequestList', function () {
    if (Request::ajax()) {
        /*$key = -1;
        if (request('itemCode') !== null) {
            $key = array_search(request('itemCode'), array_column(session('requestList'), 'itemCode'));
            //return $key;
            if ($key > 0) {
                return 'oui';
            }
        }*/

        session()->push('requestList', array('itemCode' => request('itemCode'),
                                             'desc' => request('desc'),
                                             'qty' => request('qty'),
                                             'text' => request('text'),
                                             'delai' => request('delai') !== null ? 'true' :'false',
                                             'prix' => request('prix') !== null ? 'true' :'false'));
        //return 'non';
    }
});
Route::post('/saveRequestListInput', function () {
    if (Request::ajax()) {
        if (request('text') !== null) {
            $type = 'text';
        } elseif (request('qty') !== null) {
            $type = 'qty';
        } elseif (request('prix') !== null) {
            $type = 'prix';
        } elseif (request('delai') !== null) {
            $type = 'delai';
        }
        $requestList = session('requestList');
        $i=-1;
        foreach ($requestList as &$article) {
            $i++;
            if (intval(request('index')) === $i) {
                $article[$type] = request($type);
                break;
            }
        }
        session()->put('requestList', $requestList);
        unset($article);
    }
});
Route::post('/requestList', 'RequestListController@envoyerRequestList')->name('envoyerrequestlist');

Route::get('/monCompte/nouvelUtilisateur', 'GestionUtlisateurController@afficheCreate')->name('nouvelutilisateur.affiche');
Route::post('/monCompte/nouvelUtilisateur', 'GestionUtlisateurController@register')->name('nouvelutilisateur.ajouter');

Route::get('/monCompte/modifierProfil', 'GestionUtlisateurController@afficheModif')->name('modifprofil.affiche');
Route::post('/monCompte/modifierProfil', 'GestionUtlisateurController@modifier')->name('modifprofil.modifier');
//Route::post('/deleteUser', 'GestionUtlisateurController@deleteUser');
Route::post('/deleteUser', function () {
    DB::table('STAUFF_Users')->where('id', '=', request('currentId'))->delete();
    return response()->json(request()->all());
});
