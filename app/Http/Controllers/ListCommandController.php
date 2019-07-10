<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;
use Carbon\Carbon;

class ListCommandController extends Controller
{
    public function __construct()
    {
        $this->middleware('suivi');
    }

    public function showSelect()
    {
        $date = Carbon::now()->setTimezone('Europe/Paris');
        $dateDu = Carbon::now()->setTimezone('Europe/Paris');
        $dateDu->day = 1;
        $dateAu = Carbon::now()->setTimezone('Europe/Paris');

        return view('onglet.account.commandeList')->with(array(
                                                         'dateNow' => $date,
                                                         'dateDu' => $dateDu,
                                                         'dateAu' => $dateAu,
                                                         'status' => 'T',
                                                         'get' => true,
                                                         'itemCode' => '',
                                                         'numCommande' => ''
                                                        ));
    }

    public function showCommand(Request $request)
    {
        $date = Carbon::now()->setTimezone('Europe/Paris');
        $dateDu = new Carbon("$request->fyear-$request->fmonth-$request->fday");
        $dateAu = new Carbon("$request->tyear-$request->tmonth-$request->tday");
        $itemCode = trim($request->itemcode);
        $numCommande = trim($request->numcommande);
        $status = $request->status;

        $commandsBuilder = DB::table('STAUFF_webshop_commandes')
                               ->select('AR', 'NumCommande', 'ItemCode', 'Nom', 'Prix', 'Date', 'Quantite', 'Status', 'U_NUMCAT as codeClient', 'U_TEREX')
                               ->where('DocDate', '>=', $dateDu->format('Ymd'))
                               ->where('DocDate', '<=', $dateAu->format('Ymd'))
                               ->where('CardCode', '=', Auth::user()->CardCode)
                               ->orderBy('AR', 'asc')
                               ->orderBy('linenum', 'asc');


        // Choix du Status
        if ($status === 'O') {
            $commandsBuilder->where('LineStatus', '=', 'O');
        } elseif ($status === 'C') {
            $commandsBuilder->where('LineStatus', '=', 'C');
        }

        // Si on rentre un ItemCode
        if ($itemCode !== '') {
            $bool = true;

            if (substr($itemCode, -1) ==='*') {
                $bool = false;
            }

            if (substr_count($itemCode, '*') === 0) {
                $itemCode = $itemCode . '*';
            }

            $itemCode = str_replace('*', '%', $itemCode);

            $commandsBuilder->where('ItemCode', 'like', $itemCode);

            $itemCode = str_replace('%', '*', $itemCode);
            if ($bool) {
                if (substr($itemCode, -1) ==='*') {
                    $itemCode = substr($itemCode, 0, strlen($itemCode)-1);
                } else {
                    $itemCode = substr($itemCode, 0, strlen($itemCode));
                }
            }
        }

        // Si on rentre un numéro de commande
        if ($numCommande !== '') {
            $bool = true;

            if (substr($itemCode, -1) ==='*') {
                $bool = false;
            }

            if (substr_count($numCommande, '*') === 0) {
                $numCommande = $numCommande . '*';
            }
            $numCommande = str_replace('*', '%', $numCommande);

            $commandsBuilder->where('NumCommande', 'like', $numCommande);

            $numCommande = str_replace('%', '*', $numCommande);
            if ($bool) {
                if (substr($numCommande, -1) ==='*') {
                    $numCommande = substr($numCommande, 0, strlen($numCommande)-1);
                } else {
                    $numCommande = substr($numCommande, 0, strlen($numCommande));
                }
            }
        }

        // Retourne les valeurs
        $commands = $commandsBuilder->get();

        $fileName = $this->writeCSV($commands);

        return view('onglet.account.commandeList')->with(array(
                                                         'dateNow' => $date,
                                                         'dateDu' => $dateDu,
                                                         'dateAu' => $dateAu,
                                                         'status' => $status,
                                                         'get' => false,
                                                         'itemCode' => $itemCode,
                                                         'numCommande' => $numCommande,
                                                         'commands' => $commands,
                                                         'TEREX' => Auth::user()->CardCode === 'C002900' ? true : false,
                                                         'fileName' => $fileName
                                                     ));
    }

    private function writeCSV($commands)
    {
        $client = DB::table('STAUFF_webshop_client')
                    ->select('CardName as nom')
                    ->where('CardCode', '=', Auth::user()->CardCode)
                    ->get();
        $date = Carbon::now()->setTimezone('Europe/Paris');

        array_map('unlink', glob("CSV/listCommand/" . str_replace(' ', '_', $client[0]->nom) . "*"));

        $fileName = str_replace(' ', '_', $client[0]->nom) . '-' . $date->format('Ymdhis') . '.csv';

        $chemin = "CSV/listCommand/$fileName";
        $delimitateur = ';';
        $fichier_csv = fopen($chemin, 'w+');
        fprintf($fichier_csv, chr(0xEF).chr(0xBB).chr(0xBF));

        if (Auth::user()->acces_prix == 1) {
            $titres = array("N° d'AR",
                            "N° de commande",
                            "Code client",
                            "Code STAUFF",
                            "Désigniation",
                            "Quantité",
                            "Prix unit.",
                            "Date départ",
                            "Status"
                            );
        } else {
            $titres = array("N° d'AR",
                            "N° de commande",
                            "Code client",
                            "Code STAUFF",
                            "Désigniation",
                            "Quantité",
                            "Date départ",
                            "Status"
                            );
        }

        fputcsv($fichier_csv, $titres, $delimitateur);

        $dateAconfirmer = new Carbon($date);
        $dateAconfirmer->day = 1;
        $dateAconfirmer->month = 1;
        foreach ($commands as $command) {
            $dateCommand = new Carbon($command->Date);
            if (Auth::user()->CardCode === 'C002900') {
                $numCommande = $command->NumCommande.'- '.$command->U_TEREX;
            } else {
                $numCommande = $command->NumCommande;
            }

            if ($dateCommand->format('d/m/Y') == $dateAconfirmer->format('d/m/Y')) {
                $date = 'A confirmer';
            } else {
                $date = $dateCommand->format('d/m/Y');
            }

            if (Auth::user()->acces_prix == 1) {
                $ligne = array($command->AR,
                               $numCommande,
                               $command->codeClient,
                               $command->ItemCode,
                               $command->Nom,
                               intval($command->Quantite),
                               number_format($command->Prix, 4, ',', ' '),
                               $date,
                               $command->Status
                              );
            } else {
                $ligne = array($command->AR,
                               $numCommande,
                               $command->codeClient,
                               $command->ItemCode,
                               $command->Nom,
                               intval($command->Quantite),
                               $date,
                               $command->Status
                              );
            }
            fputcsv($fichier_csv, $ligne, $delimitateur);
        }
        fclose($fichier_csv);
        return $fileName;
    }
}
