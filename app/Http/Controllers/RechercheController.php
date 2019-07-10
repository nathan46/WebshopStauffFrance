<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;
use Carbon\Carbon;

use App\Repositories\InfoArticleRepository;

class RechercheController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function afficherParCode(Request $request)
    {
        $item = $request->hitsperpage ?? 10;
        $text = trim($request->search_matnr_descr);
        $bool = true;

        if (substr($text, -1) ==='*') {
            $bool = false;
        }

        if (substr_count($text, '*') === 0) {
            $text = $text . '*';
        }
        $text = str_replace('*', '%', $text);

        $articlesRequest = DB::table('OITM')
                    ->select('itemCode', 'itemName', 'U_CodesSTD')
                    ->where('frozenFor', '=', 'n')
                    ->whereNotNull('U_CodesSTD')
                    ->where(function ($query) use ($text) {
                        $query->where('itemCode', 'like', $text)
                              ->orWhere('itemName', 'like', $text)
                              ->orWhere('U_CodesSTD', 'like', $text);
                    });


        if ($request->sortBy !== null) {
            $articles = $articlesRequest->orderBy($request->sortBy, $request->sortType)
                                        ->paginate($item);
        } else {
            $articles = $articlesRequest->orderBy('itemname', 'asc')
                                        ->paginate($item);
        }

        $customerNo = DB::table('OSCN')
                      ->select('ItemCode', 'substitute')
                      ->where('CardCode', '=', Auth::user()->CardCode)
                      ->get();


        //$text = substr(str_replace('%','*',$text), 0, strlen($text)-1);
        $text = str_replace('%', '*', $text);
        if ($bool) {
            if (substr($text, -1) === '*') {
                $text = substr($text, 0, strlen($text)-1);
            } else {
                $text = substr($text, 0, strlen($text));
            }
        }

        return view('onglet.recherche.recherche')->with(array(
                                                        'articles' => $articles,
                                                        'nb' => $articles->total(),
                                                        'input' => $text,
                                                        'nbPerPage' => $request->hitsperpage,
                                                        'item' => $item,
                                                        'customerNo' => $customerNo
                                                        ));
    }

    public function afficherParRefClient(Request $request)
    {
        $itemCodeRequest = DB::table('OSCN')
                               ->select('itemcode')
                               ->where('CardCode', '=', Auth::user()->CardCode)
                               ->where('substitute', '=', trim($request->search_matnr_descr))
                               ->get();

        if (!$itemCodeRequest->isEmpty()) {
            foreach ($itemCodeRequest as $i) {
                $itemCode = $i->itemcode;
            }

            $articles = DB::table('OITM')
                            ->select('itemCode', 'itemName', 'U_CodesSTD')
                            ->where('frozenFor', '=', 'n')
                            ->whereNotNull('U_CodesSTD')
                            ->where('itemCode', '=', $itemCode)
                            ->get();

            return view('onglet.recherche.rechercheRefClient')->with(array('nb' => 1, 'articles' => $articles, 'input' => $request->search_matnr_descr));
        } else {
            return view('onglet.recherche.rechercheRefClient')->with(array('nb' => 0,'input' => $request->search_matnr_descr));
        }
    }

    public function showProductDetails()
    {
        $data = InfoArticleRepository::getProduitDetails(request('code'));
        //dump($data);
        //die();
        return view('onglet.recherche.productdetails')->with('data', $data);
    }

    public function showPrixStock()
    {
        if (request('premier') !== null) {
            return view('onglet.recherche.prixStock')->with(array('premier' => true, 'code' => request('code')));
        }

        $itemCode = request('code');
        $cardCode = Auth::user()->CardCode;
        $qty = request('qty');

        $prix = InfoArticleRepository::getPrix($itemCode, $cardCode);

        $pere = DB::table('STAUFF_webshop_nomenclature')
                    ->select('codePere')
                    ->where('codePere', '=', $itemCode)
                    ->distinct()
                    ->get();

        if ($pere->isEmpty()) {
            $dateDispo = InfoArticleRepository::getDate($itemCode, $qty);
        } else {
            $filsrequest =  DB::table('STAUFF_webshop_nomenclature_groupby')
                                ->select('codeFils', 'quantite as qty')
                                ->where('codePere', '=', $itemCode)
                                ->get();

            $dateMax = Carbon::now()->setTimezone('Europe/Paris');
            foreach ($filsrequest as $fils) {
                $listDate = InfoArticleRepository::getDate($fils->codeFils, intval(intval($fils->qty)*intval($qty)));
                foreach ($listDate['dates'] as $date) {
                    if ($date === false) {
                        $dateMax = false;
                        break 2;
                    }

                    $tmpDate = new Carbon(str_replace('/', '-', $date));
                    if ($tmpDate > $dateMax) {
                        $dateMax = $tmpDate;
                    }
                }
            }
            $dateDispo['dates'] = $dateMax === false ? array(false) :  array($dateMax->format('d/m/Y'));
            $dateDispo['qty'] = array($qty);
        }


        $cond = InfoArticleRepository::getCond(request('code'));

        return view('onglet.recherche.prixStock')->with(array("code" => $itemCode, 'prix' => $prix, 'dateDispo' => $dateDispo, 'cond' => $cond));


        //dump($prix, $date);
        //die();
    }
}
