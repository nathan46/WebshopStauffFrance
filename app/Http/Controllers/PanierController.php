<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;
use Mail;
use File;
use App\Repositories\InfoArticleRepository;
use Carbon\Carbon;
use PHPMailer\PHPMailer\PHPMailer;

class PanierController extends Controller
{
    public function __construct()
    {
        $this->middleware('panier');
    }

    public function affichePanier(Request $request)
    {
        //dump(Auth::user());
        //die();
        /*$date = Carbon::now()->setTimezone('Europe/Paris');
        dump($date->format('Ymdhis'));
        die();*/
        $adresses = DB::table('STAUFF_webshop_client_adresse')
                        ->select('ShipToCode as nom', 'ShipToStreet as adresse1', 'ShipToCounty as adresse2', 'ShipToCity as ville', 'ShipToZipCode as codePostal', 'Defaut as defaut')
                        ->where('CardCode', '=', Auth::user()->CardCode)
                        ->get();

        if (session('panier') !== null) {
            if (isset($request->delete)) {
                $index = $request->delete;
                $oldPanier = session('panier');
                $request->session()->forget('panier');

                if ($index !== 'all') {
                    $i = -1;
                    foreach ($oldPanier as $o) {
                        $i++;
                        if ($i !== intval($index)) {
                            $request->session()->push('panier', array('itemCode' => $o['itemCode'], 'desc' => $o['desc'], 'qty' => $o['qty'], 'complete' => $o['complete']));
                        }
                    }
                }

                return redirect()->route('affichepanier');
            }
            $panier = session('panier');

            $i =-1;
            foreach ($panier as &$p) {
                $i++;
                $tmpCond = InfoArticleRepository::getCond($p['itemCode']);
                $cond[$i] = $tmpCond > 1 ? $tmpCond : null;

                $p['qty'] = $this->multipleSuivant(intval($p['qty']), $tmpCond);
            }

            session()->put('panier', $panier);
            unset($p);



            return view('onglet.panier.panier')->with(array('panier' => $panier, 'customerNo' => $this->getCustomerNo(),'cond' => $cond, 'adresses' => $adresses));
        } else {
            return view('onglet.panier.panier')->with(array('adresses' => $adresses));
            ;
        }
    }

    public function verifierEtCommander(Request $request)
    {
        $i = -1;
        $panier = session('panier');
        $prixTotal = 0.0;
        $poidsTotal = 0.0;
        $checked = array();
        $cond = array();
        foreach ($panier as &$p) {
            $i++;

            // Affichage des conditionnements
            $tmpCond = InfoArticleRepository::getCond($p['itemCode']);
            $cond[$i] = $tmpCond > 1 ? $tmpCond : null;

            // Mettre a jour le panier 1/2 (avec conditionnement)
            $p['qty'] = $this->multipleSuivant(intval(request('qty'.$i)), $tmpCond);
            $p['complete'] = request('complete'.$i) === null ? 'false' : 'true';
            // Calcul les prix
            $listPrix[$i] = InfoArticleRepository::getPrix($p['itemCode'], Auth::user()->CardCode);

            // Calcul les totaux
            $prixTotal += floatval(number_format(floatval(str_replace(',', '.', $listPrix[$i])) * $p['qty'], 2, '.', ''));
            $poidsTotal += InfoArticleRepository::getProduitDetails($p['itemCode'])['poidsTotal'] * $p['qty'];

            // Cherche si il y a un poids null
            if (InfoArticleRepository::getProduitDetails($p['itemCode'])['poidsTotal']*10000 === 0) {
                $poidsNul = true;
            }

            // Calcul les dates
            $listDate[$i] = InfoArticleRepository::getDate($p['itemCode'], $p['qty']);

            // Si ligne complete
            if (request('complete'.$i) !== null) {
                $listDate[$i]['dates'] = array(end(InfoArticleRepository::getDate($p['itemCode'], $p['qty'])['dates']));
                $listDate[$i]['qty'] = array($p['qty']);
            }
        }

        // Si commande complete
        if ($request->CompleteOrder !== null) {
            $checked['all'] = true;
            $dateMax = Carbon::now()->setTimezone('Europe/Paris');
            foreach ($listDate as $list) {
                $i = -1;
                foreach ($list['dates'] as $date) {
                    $i++;
                    if ($list['qty'][$i] !== 0) {
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
            }

            for ($i=0; $i < count($listDate); $i++) {
                $listDate[$i]['dates'] = ($date === false) ? array(false) : array($dateMax->format('d/m/Y'));
                $listDate[$i]['qty'] = array(request('qty'.$i));
            }
        }


        // Mettre a jour le panier 2/2
        session()->put('panier', $panier);
        unset($p);

        $adresses = DB::table('STAUFF_webshop_client_adresse')
                        ->select('ShipToCode as nom', 'ShipToStreet as adresse1', 'ShipToCounty as adresse2', 'ShipToCity as ville', 'ShipToZipCode as codePostal', 'Defaut as defaut')
                        ->where('CardCode', '=', Auth::user()->CardCode)
                        ->get();

        if ($request->action == 'check') {
            return view('onglet.panier.panier')->with(array('panier' => session('panier'),
                                                            'customerNo' => $this->getCustomerNo(),
                                                            'check' => true,
                                                            'listPrix' => $listPrix,
                                                            'listDate' => $listDate,
                                                            'prixTotal' => number_format($prixTotal, 2, ',', ' '),
                                                            'poidsTotal' => isset($poidsNul) ? 0 : number_format($poidsTotal, 4, ',', ' '),
                                                            'checked' => $checked,
                                                            'cond' => $cond,
                                                            'adresses' => $adresses
                                                             ));
        } elseif ($request->action == 'order') {
            $infoCommande = $this->writeCSV($request);
            $nom = $infoCommande['nom'];

            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = 'smtp.office365.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'webshop@stauffsa.com';
            $mail->Password = 'Qal98655';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;
            $mail->setFrom('webshop@stauffsa.com', 'STAUFF');
            $mail->addAddress('isabelle.legras@stauffsa.com', 'Legras Isabelle');
            //$mail->addAddress('informatique.stagiaire@stauffsa.com', 'Auger Nathan');
            $mail->addAttachment($infoCommande['entete'], 'entete.csv');
            $mail->addAttachment($infoCommande['lignes'], 'ligne.csv');
            $mail->Subject = 'Commande WebShop';
            $mail->Body = "Voici la commande de $nom";
            $mail->isHTML(true);

            if (true) { //$mail->send()
                $request->session()->forget('panier');
            }
            return redirect()->route('affichepanier');

            //array_map('unlink', glob("CSV/achat/*.csv"));
        }
    }

    public function importCommandeAffiche(Request $request)
    {
        return view('onglet.panier.import');
    }

    public function importCommande(Request $request)
    {
        //dump($request);
        //die();
        $clearFirst = $request->clearfirst;
        $header = null;
        $data = array();
        for ($i=0; $i < count($request->file('FileUpload')) ; $i++) {
            $file[$i] = $request->file("FileUpload")[$i];
        }
        //die();
        //$filename = $request->file('FileUpload')->getRealPath();

        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = 'smtp.office365.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'webshop@stauffsa.com';
        $mail->Password = 'Qal98655';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;
        $mail->setFrom('webshop@stauffsa.com', 'STAUFF');
        $mail->addAddress('informatique.stagiaire@stauffsa.com', 'Auger Nathan');
        foreach ($file as $f) {
            $mail->addAttachment($f->getRealPath(), $f->getClientOriginalName());
        }
        $mail->Subject = 'Commande WebShop';
        $mail->Body = "Voici la commande de ";
        $mail->isHTML(true);

        $mail->send();
        die();

        if (($handle = fopen($filename, 'r')) !== false) {
            while (($row = fgetcsv($handle, 1000, ';')) !== false) {
                if (!$header) {
                    $header = $row;
                } else {
                    $data[] = array_combine($header, $row);
                }
            }
            fclose($handle);
        }

        foreach ($data as &$d) {
            $nom = DB::table('OITM')
                ->select('itemName')
                ->where('itemCode', '=', $d['code'])
                ->get();

            $d['desc'] = $nom[0]->itemName;
        }
        unset($d);

        if ($clearFirst !== null) {
            $request->session()->forget('panier');
        }

        foreach ($data as $d) {
            $request->session()->push('panier', array('itemCode' => $d['code'], 'desc' => $d['desc'], 'qty' => $d['quantite'], 'complete' => false));
        }


        //dump(session('panier'));
        //die();
        return view('onglet.panier.import');
    }

    private function writeCSV($request)
    {
        $client = DB::table('STAUFF_webshop_client_adresse')
                    ->select('CardName as nom', 'SalesPersoncode', 'PaymentGroupCode', 'TransportationCode')
                    ->where('CardCode', '=', Auth::user()->CardCode)
                    ->get();

        $date = Carbon::now()->setTimezone('Europe/Paris');
        $premierJanvier = new Carbon($date);
        $premierJanvier->day = 1;
        $premierJanvier->month = 1;

        // Entete -----------------------------------------------------------------------------------------------
        $fileName = str_replace(' ', '_', $client[0]->nom) . ';'. 'entete;' . $date->format('Ymdhis') . '.csv';

        $chemin1 = "CSV/achat/$fileName";
        $delimitateur = ';';
        $enclosure = '*';
        $fichier_csv = fopen($chemin1, 'w+');
        fprintf($fichier_csv, chr(0xEF).chr(0xBB).chr(0xBF));

        $titres = array("RecordKey",
                        "DocType",
                        "DocObjectCode",
                        "ShipToCode",
                        "CardCode",
                        "ShipToStreet",
                        "ShipToCity",
                        "ShipToCounty",
                        "ShipToZipCode",
                        "DocDate",
                        "DocDueDate",
                        "PartialSupply",
                        "NumAtCard",
                        "SalesPersonCode",
                        "PaymentGroupCode",
                        "TransportationCode",
                        "U_code_corres",
                        "U_NOMCORRE",
                        "U_NBS_UDP",
                        "U_AppPL"
                        );

        fputcsv($fichier_csv, $titres, $delimitateur);
        fputcsv($fichier_csv, $titres, $delimitateur);

        $ligne = array("1",
                       "I",
                       "17",
                       $request->shipToCode,
                       Auth::user()->CardCode,
                       $request->shipToStreet,
                       $request->shipToCity,
                       $request->shipToCounty !== null ? $request->shipToCounty : '',
                       $request->shipToZipCode,
                       $date->format('Ymd'),
                       $premierJanvier->format('Ymd'),
                       isset($request->CompleteOrder) ? 'tNO' : 'tYES',
                       $request->numCommande,
                       $client[0]->SalesPersoncode,
                       $client[0]->PaymentGroupCode,
                       $client[0]->TransportationCode,
                       "42",
                       "WebShop",
                       "1",
                       "N"
                       );
        fputcsv($fichier_csv, $ligne, $delimitateur, $enclosure);
        fclose($fichier_csv);

        // Ligne -----------------------------------------------------------------------------------------------

        $fileName = str_replace(' ', '_', $client[0]->nom) . ';'. 'ligne;' . $date->format('Ymdhis') . '.csv';

        $chemin2 = "CSV/achat/$fileName";
        $fichier_csv = fopen($chemin2, 'w+');
        fprintf($fichier_csv, chr(0xEF).chr(0xBB).chr(0xBF));

        $titres = array("RecordKey",
                        "LineNum",
                        "ItemCode",
                        "Quantity",
                        "ShipDate",
                        "U_STPREPA",
                        "U_u_prix_vte",
                        "BackOrder",
                        "U_NUMCAT",
                        "ShipToCode"
                        );

        fputcsv($fichier_csv, $titres, $delimitateur);
        fputcsv($fichier_csv, $titres, $delimitateur);

        $panier = session('panier');
        $i = -1;
        foreach ($panier as $article) {
            $i++;
            $ligne = array("1",
                           "$i",
                           $article['itemCode'],
                           $article['qty'],
                           $premierJanvier->format('Ymd'),
                           "1",
                           "le cent",
                           $article['complete'] === 'true' ? 'tNO' : 'tYES',
                           InfoArticleRepository::getClientMatNo($article['itemCode'], Auth::user()->CardCode),
                           $request->shipToCode
                           );
            fputcsv($fichier_csv, $ligne, $delimitateur, $enclosure);
        }
        fclose($fichier_csv);

        $replace1 = str_replace("*", "", file_get_contents($chemin1));
        file_put_contents($chemin1, $replace1);

        $replace2 = str_replace("*", "", file_get_contents($chemin2));
        file_put_contents($chemin2, $replace2);

        return array('entete' => $chemin1, 'lignes' => $chemin2, 'nom' => $client[0]->nom);
    }

    private function getCustomerNo()
    {
        return DB::table('OSCN')
                  ->select('ItemCode', 'substitute')
                  ->where('CardCode', '=', Auth::user()->CardCode)
                  ->get();
    }

    public function multipleSuivant($nombre, $cond)
    {
        if ($nombre <= $cond) {
            return $cond;
        }

        if ($nombre > 100000 - $cond) {
            return 100000 - $cond;
        }

        for ($i = 1; true; $i++) {
            if ($i*$cond >= $nombre) {
                return $i*$cond;
            }
        }
    }
}
