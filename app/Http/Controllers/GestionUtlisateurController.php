<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Notifications\WelcomeUser;
use App\User;
use DB;
use Auth;
use Carbon\Carbon;
use Illuminate\Validation\Rule;

class GestionUtlisateurController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = '/monCompte/nouvelUtilisateur';

    public function __construct()
    {
        $this->middleware('admin');
    }

    public function afficheCreate(Request $request)
    {
        $finEmail = stristr(Auth::user()->email, '@');

        return view('onglet.account.newUser')->withFin($finEmail);
    }

    /**
     * Get a validator for an incoming registration request.jJnCCeRE
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $data['email'].=$data['finEmail'];

        return Validator::make($data, [
            'nom' => ['required', 'string', 'max:255'],
            'prenom' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string','email', 'max:50', 'unique:STAUFF_Users'],
            'telephone_fixe' => ['required', 'digits:10'],
            'telephone_portable' => ['regex:/^([0-9]{10})$/','nullable'],
            'service' => ['required', 'string', 'max:255'],
            'fonction' => ['required', 'string', 'max:255'],
        ]);
    }

    public function create(array $data)
    {
        if ($data['telephone_portable'] !== null) {
            if (trim($data['telephone_portable']) !== '') {
                $portable = $data['telephone_portable'];
            } else {
                $portable = null;
            }
        } else {
            $portable = null;
        }

        $u = User::create([
            'nom' => $data['nom'],
            'prenom' => $data['prenom'],
            'email' => $data['email'] . $data['finEmail'],
            'telephone_fixe' => $data['telephone_fixe'],
            'telephone_portable' => $portable,
            'service' => $data['service'],
            'fonction' => $data['fonction'],
            'password' => bcrypt($data['password']),
            'CardCode' => Auth::user()->CardCode,
            'acces_panier' => isset($data['panier']) ? 1 : 0,
            'acces_demande' => isset($data['demande']) ? 1 : 0,
            'acces_condition' => isset($data['condition']) ? 1 : 0,
            'acces_suivi' => isset($data['suivi']) ? 1 : 0,
            'acces_prix' => isset($data['prix']) ? 1 : 0,
        ]);

        $user = new User();
        $user->email = $data['email'] . $data['finEmail'];
        $user->notify(new WelcomeUser($data));

        return $u;
    }

    public function afficheModif(Request $request)
    {
        $listUser = DB::table('STAUFF_Users')
                        ->select('id', 'nom', 'prenom', 'email', 'telephone_fixe as fixe', 'telephone_portable as portable', 'fonction', 'service', 'acces_panier as panier', 'acces_demande as demande', 'acces_prix as prix', 'acces_suivi as suivi', 'acces_condition as condition')
                        ->where('CardCode', '=', Auth::user()->CardCode)
                        //->where('admin', '!=', '1')
                        ->get();

        $finEmail = stristr(Auth::user()->email, '@');

        return view('onglet.account.modifProfil')->with(array('listUser' => $listUser, 'fin' => $finEmail));
    }

    public function modifier(Request $request)
    {
        $this->validate($request, [
            'nom' => ['required', 'string', 'max:255'],
            'prenom' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string','email', 'max:50', Rule::unique('STAUFF_Users')->ignore($request['currentId'])],
            'telephone_fixe' => ['required', 'digits:10'],
            'telephone_portable' => ['regex:/^([0-9]{10})$/','nullable'],
            'service' => ['required', 'string', 'max:255'],
            'fonction' => ['required', 'string', 'max:255']]);

        $date = Carbon::now()->setTimezone('Europe/Paris');

        DB::table('STAUFF_Users')
            ->where('id', '=', $request['currentId'])
            ->update(['nom' => $request->nom,
                      'prenom' => $request->prenom,
                      'email' => $request->email,
                      'telephone_fixe' => $request->telephone_fixe,
                      'telephone_portable' => $request->telephone_portable,
                      'service' => $request->service,
                      'fonction' => $request->fonction,
                      'acces_condition' => $request->acces_condition === 'true' ? 1 : 0,
                      'acces_demande' => $request->acces_demande === 'true' ? 1 : 0,
                      'acces_panier' => $request->acces_panier === 'true' ? 1 : 0,
                      'acces_prix' => $request->acces_prix === 'true' ? 1 : 0,
                      'acces_suivi' => $request->acces_suivi === 'true' ? 1 : 0,
                      'updated_at' => $date
                      ]);

        return response()->json([
                              'message' => "Le profil a bien été modifié"
                          ]);
    }
}
