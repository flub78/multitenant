<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\Config;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use App\Mail\Invitation;
use Illuminate\Support\Facades\App;


class TestController extends Controller
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
        $vars = ['locale' => Config::config('app.locale'),
            'url' => URL::to('/')];
        
        $vars['app_locale'] = App::getLocale();
        $vars['route'] = route('calendar_event.index');
        $vars['central_db'] = env ( 'DB_DATABASE' );
        
        return view('test/test',$vars);
    }
    
    public function info() {
    	phpinfo();
    }
    
    public function email () {
    
    	$name = 'Fred';
    	Mail::to('frederic.peignot@free.fr')->send(new Invitation($name));
    	
    	return 'Email was sent';
    }
    
    public function menu () {
        // Experiment on mega menu, big navigation pages, ect
        
        $section1 = [
            'title' => "Membres",
            'entries' => [
                ['url' => 'http://locahost', 'label' => 'Calendrier', 'icon' => "calendar.PNG", "text" => "Calendar"],
                ['url' => 'http://locahost', 'label' => 'Reserver un vol ULM', 'icon' => "calendar.PNG", "text" => ""],
                ['url' => 'http://locahost', 'label' => 'Reserver un vol avion', 'icon' => "", "text" => ""],
                ['url' => 'http://locahost', 'label' => 'Ma facture', 'icon' => "", "text" => ""],
                ['url' => 'http://locahost', 'label' => 'Mes vols planeur', 'icon' => "", "text" => ""],
                ['url' => 'http://locahost', 'label' => 'Mes vols ULM', 'icon' => "", "text" => ""],
                ['url' => 'http://locahost', 'label' => 'Mes vols avion', 'icon' => "", "text" => ""],
                ['url' => 'http://locahost', 'label' => 'Mon profil', 'icon' => "", "text" => ""],
                ['url' => 'http://locahost', 'label' => 'Contacter', 'icon' => "", "text" => ""],
                ['url' => 'http://locahost', 'label' => 'Discuter en ligne', 'icon' => "", "text" => ""],
                ['url' => 'http://locahost', 'label' => 'Changer mon mot de passe', 'icon' => "", "text" => ""],
            ]    
        ];
        
        $section2 = [
            'title' => "Les vols",
            'entries' => [
                ['url' => 'http://locahost', 'label' => 'Ajouter un vol planeur', 'icon' => "", "text" => ""],
                ['url' => 'http://locahost', 'label' => 'Ajouter un vol ULM', 'icon' => "", "text" => ""],
                ['url' => 'http://locahost', 'label' => 'Ajouter un vol avion', 'icon' => "", "text" => ""],
                ['url' => 'http://locahost', 'label' => 'Liste des vols planeur', 'icon' => "", "text" => ""],
                ['url' => 'http://locahost', 'label' => 'Liste des vols ULM', 'icon' => "", "text" => ""],
                ['url' => 'http://locahost', 'label' => 'Liste des vols avion', 'icon' => "", "text" => ""],
                ['url' => 'http://locahost', 'label' => 'Planche automatique', 'icon' => "", "text" => ""],
                ['url' => 'http://locahost', 'label' => 'Export GESASSO', 'icon' => "", "text" => ""],
            ]
        ];
        
        $section3 = [
            'title' => "Comptabilité",
            'entries' => [
                ['url' => 'http://locahost', 'label' => 'Saisie écriture', 'icon' => "", "text" => ""],
                ['url' => 'http://locahost', 'label' => 'Journaux', 'icon' => "", "text" => ""],
                ['url' => 'http://locahost', 'label' => 'Balance', 'icon' => "", "text" => ""],
                ['url' => 'http://locahost', 'label' => 'Resultats', 'icon' => "", "text" => ""],
                ['url' => 'http://locahost', 'label' => 'Bilan', 'icon' => "", "text" => ""],
                ['url' => 'http://locahost', 'label' => 'Vente', 'icon' => "", "text" => ""],
                ['url' => 'http://locahost', 'label' => 'Trésorerie', 'icon' => "", "text" => ""],
                
                ['url' => 'http://locahost', 'label' => 'Tarifs', 'icon' => "", "text" => ""],
                ['url' => 'http://locahost', 'label' => 'Règles de facturation', 'icon' => "", "text" => ""],
                ['url' => 'http://locahost', 'label' => "Cloture de l'exercise", 'icon' => "", "text" => ""],
                ['url' => 'http://locahost', 'label' => "Plan comptable", 'icon' => "", "text" => ""],
            ]
        ];
        
        $section4 = [
            'title' => "Maintenance",
            'entries' => [
                ['url' => 'http://locahost', 'label' => 'Définir le plan de maintenance', 'icon' => "", "text" => ""],
                ['url' => 'http://locahost', 'label' => 'Saisie opération de maintenance', 'icon' => "", "text" => ""],
                ['url' => 'http://locahost', 'label' => 'Rapport de maintenance', 'icon' => "", "text" => ""],
            ]
        ];

        $section4 = [
            'title' => "Gestion",
            'entries' => [
                ['url' => 'http://locahost', 'label' => 'Les terrains', 'icon' => "", "text" => ""],
                ['url' => 'http://locahost', 'label' => 'Parc planeur', 'icon' => "", "text" => ""],
                ['url' => 'http://locahost', 'label' => 'Parc ULM', 'icon' => "", "text" => ""],
                ['url' => 'http://locahost', 'label' => 'Parc avion', 'icon' => "", "text" => ""],
                
                ['url' => 'http://locahost', 'label' => 'Gestion des utilisateurs', 'icon' => "", "text" => ""],
                ['url' => 'http://locahost', 'label' => 'Gestion des rôles', 'icon' => "", "text" => ""],
                ['url' => 'http://locahost', 'label' => "Affectation des permissions", 'icon' => "", "text" => ""],
            
                ['url' => 'http://locahost', 'label' => "Envoie d'emails", 'icon' => "", "text" => ""],
                
                ['url' => 'http://locahost', 'label' => "Configuration du logiciel", 'icon' => "", "text" => ""],
                ['url' => 'http://locahost', 'label' => "Création d'une sauvegarde", 'icon' => "", "text" => ""],
                ['url' => 'http://locahost', 'label' => "Restauration d'une sauvegarde", 'icon' => "", "text" => ""],

                ['url' => 'http://locahost', 'label' => "Import HEVA", 'icon' => "", "text" => ""],
            ]
        ];

        $section4 = [
            'title' => "Admin",
            'entries' => [
                ['url' => 'http://locahost', 'label' => 'Saisie écriture', 'icon' => "", "text" => ""],
                ['url' => 'http://locahost', 'label' => 'Journaux', 'icon' => "", "text" => ""],
                ['url' => 'http://locahost', 'label' => 'Balance', 'icon' => "", "text" => ""],
                ['url' => 'http://locahost', 'label' => 'Resultats', 'icon' => "", "text" => ""],
                ['url' => 'http://locahost', 'label' => 'Bilan', 'icon' => "", "text" => ""],
                ['url' => 'http://locahost', 'label' => 'Vente', 'icon' => "", "text" => ""],
                ['url' => 'http://locahost', 'label' => 'Trésorerie', 'icon' => "", "text" => ""],
                
                ['url' => 'http://locahost', 'label' => 'Tarifs', 'icon' => "", "text" => ""],
                ['url' => 'http://locahost', 'label' => 'Règles de facturation', 'icon' => "", "text" => ""],
                ['url' => 'http://locahost', 'label' => "Cloture de l'exercise", 'icon' => "", "text" => ""],
                ['url' => 'http://locahost', 'label' => "Plan comptable", 'icon' => "", "text" => ""],
            ]
        ];
        
        /*
         * Ou ecriture une seule entrée avec navigation secondaire
         *      Recette
         *      Paiement pilote
         *      Facturation manuelle
         *      Avoir fournisseur
         *      Dépense
         *      Note de frais
         *      Rembourssement avance pilote
         *      Paiment par avoir fournisseur
         *      Virement bancaire
         *      
         *      (remarque GVV avait un menu statique dépendant du contenu de la base de données.
         *      Cela veut dire que le porgramme ne fonctionnait pas sans initialisation cohérente de la base de données.
         *      Est-ce qu'on peut fair mieux ?)
         */
        
        return view('tenants/mega_menu')->with('section1', $section1);
        
    }
    
}
