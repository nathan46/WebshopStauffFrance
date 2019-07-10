<?php

namespace App\Repositories;

use DB;
use Carbon\Carbon;

class InfoArticleRepository
{
    public static function getDate($itemCode, $qty)
    {
        $dateNow = Carbon::now()->setTimezone('Europe/Paris');

        // On cherche les infos de l'article
        $magRequest = DB::table('STAUFF_webshop_dispo_stock')
                          ->select('Mag1', 'Mag2')
                          ->where('itemCode', '=', $itemCode)
                          ->get();

        // On recupere les stock
        $mag1 = $magRequest[0]->Mag1;
        $mag2 = $magRequest[0]->Mag2;

        $qtyRequest = DB::table('STAUFF_webshop_dispo_date')
                          ->select('CardCode', 'U_Jourrecep as nbJour', 'u_Class_STD as classe', 'LeadTime', 'Type', 'Date', 'Quantite')
                          ->where('itemCode', '=', $itemCode)
                          ->get();

        $total = 0;
        foreach ($qtyRequest as $q) {
            if ($q->Quantite !== null) {
                if ($q->Type =='V') {
                    $total -= $q->Quantite;
                } /*elseif ($q->Type =='A') {
                    $total += $q->Quantite;
                    break;
                }*/
            }
        }

        if ($qty <= ($mag1 + $total)) {
            return array('dates' => array($dateNow->format('d/m/Y')),
                         'qty' => array(intval($qty))
                         );
        } else {
            if ($qty <= ($mag1 + $mag2 + $total)) {
                if ($mag1 + $total >= 0) {
                    return array('dates' => array($dateNow->format('d/m/Y'), $dateNow->add(2, 'day')->format('d/m/Y')),
                                 'qty' => array(intval($mag1 + $total), intval(($qty - ($mag1 + $total))))
                                 );
                } else {
                    return array('dates' => array($dateNow->format('d/m/Y'), $dateNow->add(2, 'day')->format('d/m/Y')),
                                 'qty' => array(0, intval($qty))
                                 );
                }
            } else {
                $qtyAchatRequest = DB::table('STAUFF_webshop_dispo_date')
                                       ->select('Date', 'Quantite')
                                       ->where('itemCode', '=', $itemCode)
                                       ->where('Type', '=', 'A')
                                       ->orderBy('Date', 'asc')
                                       ->get();
                // Regarde les achats
                $x = $mag1 + $mag2 + $total;
                $derniereDate = $dateNow->copy();
                foreach ($qtyAchatRequest as $q) {
                    $x += $q->Quantite;
                    $d = new Carbon($q->Date);
                    $derniereDate = $d;
                    if ($qty <= $x) {
                        return array('dates' => array($dateNow->format('d/m/Y'),
                                                      $dateNow->add(2, 'day')->format('d/m/Y'),
                                                      $d->add($qtyRequest[0]->nbJour, 'day')->format('d/m/Y')
                                                      ),
                                     'qty' => array(intval($mag1 + $total) < 0 ? 0 : intval($mag1 + $total),
                                                    intval($mag1 + $mag2 + $total) < 0 ? 0 : intval($mag2),
                                                    intval($mag1 + $mag2 + $total) < 0 ? $qty : intval($qty -($mag1 + $mag2 + $total))
                                                    )
                                    );
                    }
                }

                // Si les achats ne suffisent pas

                if ($x > 0) {
                    return array('dates' => array($dateNow->format('d/m/Y'),
                                              $dateNow->add(2, 'day')->format('d/m/Y'),
                                              $derniereDate->add($qtyRequest[0]->nbJour, 'day')->format('d/m/Y'),
                                              false
                                              ),
                                 'qty' => array(intval($mag1 + $total) < 0 ? 0 : intval($mag1 + $total),
                                                intval($mag1 + $mag2 + $total) < 0 ? 0 : intval($mag2),
                                                intval($mag1 + $mag2 + $total) < 0 ? intval($x) : intval($x - ($mag1 + $mag2 + $total)),
                                                intval($qty - $x)
                                                )
                                 );
                } else {
                    return array('dates' => array(false),
                                 'qty' => array($qty)
                                );
                }
            }
        }
    }

    public static function getPrix($itemCode, $cardCode)
    {

        // Regarde si il a un prix spécial
        $prixSpecialRequest = DB::table('STAUFF_webshop_tarif_prixnets')
                                  ->select('prixNet')
                                  ->where('itemCode', '=', $itemCode)
                                  ->where('CardCode', '=', $cardCode)
                                  ->get();

        // Si il a un prix spécial
        if (!$prixSpecialRequest ->isEmpty()) {
            // On recupere le prix special
            $prix = $prixSpecialRequest[0]->prixNet;
            return $prix;

        // Si il n'a pas de prix spécial
        } else {

            // On cherche le prix tarif
            $prixTarifRequest = DB::table('STAUFF_webshop_tarif_prixnets')
                            ->select('Price')
                            ->where('itemCode', '=', $itemCode)
                            ->get();
            // Si il y a un prix tarif
            if (!$prixTarifRequest->isEmpty()) {
                // On recupere le prix tarif
                $prixTarif = $prixTarifRequest[0]->Price;

                // Si le prix tarif = 0
                if ($prixTarif === '.000000') {
                    //return view('onglet.recherche.prixStock');
                    return false;
                }

                // On cherche le groupe de l'article
                $groupeArticleRequest = DB::table('OITM')
                                            ->select('itmsgrpcod as codeGroupeArticle')
                                            ->where('itemCode', '=', $itemCode)
                                            ->get();

                // On recupere le groupe de l'article
                $groupeArticle = $groupeArticleRequest[0]->codeGroupeArticle;

                // Regarde si il a une remise
                $remiseRequest = DB::table('STAUFF_webshop_remises')
                            ->select('remise')
                            ->where('groupeArticle', '=', $groupeArticle)
                            ->where('CardCode', '=', $cardCode)
                            ->get();


                // Si il a une remise
                if (!$remiseRequest->isEmpty()) {

                    // On recupere la remise
                    $remise = intval($remiseRequest[0]->remise);

                    // Calcul de la remise
                    $prix = $prixTarif*((100-$remise)/100);

                    return $prix;

                // Si il n'a pas de remise
                } else {
                    $prix = $prixTarif;
                    return $prix;
                }

                // Si il n'y a pas de prix tarif
            } else {
                return false;
            }
        }
    }

    public static function getProduitDetails($itemCode)
    {
        $pere = DB::table('STAUFF_webshop_nomenclature')
                    ->select('codePere', 'nomPere', 'cond', 'deb', 'pays')
                    ->where('codePere', '=', $itemCode)
                    ->distinct()
                    ->get();


        if (!$pere->isEmpty()) {
            $fils =  DB::table('STAUFF_webshop_nomenclature_groupby')
                         ->select('codeFils', 'nomFils', 'poidsFils', 'quantite')
                         ->where('codePere', '=', $itemCode)
                         ->get();

            $poidsTotal = 0.0;
            foreach ($fils as $f) {
                if ($f->poidsFils != 0.0) {
                    $poidsTotal+=$f->poidsFils;
                } else {
                    $poidsTotal = 0;
                    break;
                }
            }
            $nbLine = 6 + count($fils);
        } else {
            $pere = DB::table('OITM')
                        ->select('itemCode as codePere', 'itemName as nomPere', 'salfactor2 as cond', 'SWeight1 as poids', DB::raw('LEFT(U_BNComCod, 8) as deb'), 'U_BNOriCtr as pays')
                        ->where('frozenFor', '=', 'n')
                        ->where('itemCode', '=', $itemCode)
                        ->get();
            $fils = false;
            $poidsTotal = $pere[0]->poids;
            $nbLine = 6;
        }


        $poidsTotal =  $poidsTotal/1000;

        return array('pere' => $pere,
                     'fils' => $fils,
                     'poidsTotal' => $poidsTotal,
                     'nbLine' => $nbLine
                     );
    }

    public static function getCond($itemCode)
    {
        return intval(InfoArticleRepository::getProduitDetails($itemCode)['pere'][0]->cond);
    }

    public static function getClientMatNo($itemCode, $cardCode)
    {
        $list = DB::table('OSCN')
                    ->select('ItemCode', 'substitute')
                    ->where('CardCode', '=', $cardCode)
                    ->where('ItemCode', '=', $itemCode)
                    ->get();

        if ($list->isEmpty()) {
            return '';
        } else {
            return strval($list[0]->substitute);
        }
    }
}
