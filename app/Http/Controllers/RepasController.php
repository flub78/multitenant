<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RepasController extends Controller {
    //
    /**
     * Display the home page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index() {
        return view('repas');
    }

    /**
     * Convert from the following format
     * array (size=31)
  0 => string 'Horodateur' (length=10)
  1 => string 'Nom et Prénom' (length=14)
  2 => string 'Repas spéciaux' (length=15)
  3 => string 'Repas  [le 18 mai]' (length=18)
  4 => string 'Repas  [Le 19 mai]' (length=18)
  5 => string 'Repas  [Le 20 mai]' (length=18)
  6 => string 'Repas  [Le 21 mai (pas de diner)]' (length=33)
  7 => string 'Repas  [Ligne 6]' (length=16)
  8 => string 'Repas  [Le 27 mai]' (length=18)
  9 => string 'Repas  [Le 28 mai]' (length=18)
  10 => string 'Repas  [Le 29 mai (pas de diner)]
31/03/2023 10:05:15' (length=54)
  11 => string 'Frédéric Peignot' (length=18)
  12 => string 'Le 17 mai (offert), Le 26 mai (offert), Repas de clôture le 28 mai (30 €)' (length=76)
  13 => string 'Petit déjeuner, Déjeuner, Diner' (length=33)
  14 => string 'Petit déjeuner, Déjeuner, Diner' (length=33)
  15 => string 'Petit déjeuner, Déjeuner, Diner' (length=33)
  16 => string 'Petit déjeuner, Déjeuner' (length=26)
  17 => string 'Petit déjeuner, Déjeuner, Diner' (length=33)
  18 => string 'Petit déjeuner, Déjeuner, Diner' (length=33)
  19 => string 'Petit déjeuner, Déjeuner, Diner' (length=33)
  20 => string 'Petit déjeuner, Déjeuner
31/03/2023 10:18:48' (length=47)
  21 => string 'Agnès Peignot' (length=14)
  22 => string 'Le 17 mai (offert), Le 26 mai (offert), Repas de clôture le 28 mai (30 €)' (length=76)
  23 => string 'Petit déjeuner, Déjeuner, Diner' (length=33)
  24 => string 'Petit déjeuner, Déjeuner, Diner' (length=33)
  25 => string 'Petit déjeuner, Déjeuner, Diner' (length=33)
  26 => string 'Petit déjeuner, Déjeuner' (length=26)
  27 => string 'Petit déjeuner, Déjeuner, Diner' (length=33)
  28 => string 'Petit déjeuner, Déjeuner, Diner' (length=33)
  29 => string 'Petit déjeuner, Déjeuner, Diner' (length=33)
  30 => string 'Petit déjeuner, Déjeuner' (length=26)

  To something easier to use

  $result = array [
    ['name' => 'Frédéric Peignot',
     'reservation_date' => '31/03/2023 10:05:15',
     'barbecue_17' => true,
     'barbecue_26' => true,
     'repas_cloture' => true,
     'Petit déjeuner' => [18, 19, 20, 21, 27, 28, 29],
     'Déjeuner' => [18, 19, 20, 21, 27, 28, 29],
     'Diner' => [18, 19, 20, 27, 28]
    ],
    ...
  ]
     */
    public function convert_line($line) {

      $fields = str_getcsv ($line, ",");
      // var_dump($fields);

      $result = [];
      $result['reservation_date'] = $fields[0];
      $result['name'] = $fields[1];

      $result['barbecue_17'] = str_contains($fields[2], '17');
      $result['barbecue_26'] = str_contains($fields[2], '26');
      $result['repas_cloture'] = str_contains($fields[2], '28');
      $result['Petit déjeuner'] = $this->convert_resa_day($fields, 'Petit déjeuner');
      $result['Déjeuner'] = $this->convert_resa_day($fields, 'Déjeuner');
      $result['Diner'] = $this->convert_resa_day($fields, 'Dîner');
      $result['email'] = $fields[10];

      $result['total_ptdj'] = count($result['Petit déjeuner']);
      $result['total_dej'] = count($result['Déjeuner']);
      $result['total_diner'] = count($result['Diner']);
      $result['detail'] = $result['total_ptdj'] . ' petit déjeuner(s) à 3,0 €, ' 
        . $result['total_dej'] . ' déjeuner(s) à 9,0 €, ' . $result['total_diner'] . ' diner(s) à 14,0 €';
      $result['total'] = $result['total_ptdj'] * 3.0 + $result['total_dej'] * 9.0 + $result['total_diner'] * 14.0;
      if ($result['repas_cloture']) {
        $result['total'] += 30.0;
        $result['detail'] .= ', un repas de clôture à 30,0 €';
      }   

      // var_dump($result);
      return $result;
    }

    public function convert_resa_day($fields, $needle) {
      $convert = [
        '3' => '18',
        '4' => '19',
        '5' => '20',
        '6' => '21',
        '7' => '27',
        '8' => '28',
        '9' => '29'
      ];

      $result = [];
      for ($i = 3; $i < 10; $i++) {
        if (str_contains($fields[$i], $needle)) {
          if ($needle == 'Dîner') {
            if ($i == 6 || $i == 8 || $i == 9) continue;
          }
          $result[] = $convert[$i];
        }
      }

      return $result;
    }

    public function csv(Request $request) {
        $request->validate([
            'picture' => 'required|file|mimes:csv,txt',
        ]);
        
        $file = $request->file('picture');
        $file->storeAs('public', $file->getClientOriginalName());

        $lines = explode("\n", file_get_contents($file->getRealPath()));
        $cnt = 0;
        $result = [];
        foreach ($lines as $line) {
            if ($cnt) $result[] = $this->convert_line($line);
            $cnt++;
        }

        // var_dump($result);
        $this->display_results($result);
        $this->total($result);
        
        // return redirect()->route('repas.index')->with('success', 'File uploaded successfully');
    }

    public function display_results($results= []) {
      foreach ($results as $result) {
        print_r('<p>' . $result['name'] . ' ' . $result['total'] . ' € ' . $result['detail'] . "</p>");
      }
    }

    public function total($results= []) {
      $barbecue_17 = 0;
      $barbecue_26 = 0;
      $repas_cloture = 0;
      $petit_dej = 0;
      $dej = 0;
      $diner = 0;
      foreach ($results as $result) {
        if ($result['barbecue_17']) $barbecue_17++;
        if ($result['barbecue_26']) $barbecue_26++;
        if ($result['repas_cloture']) $repas_cloture++;
        $petit_dej += $result['total_ptdj'];
        $dej += $result['total_dej'];
        $diner += $result['total_diner'];
      }
      print_r("<p>barbecue 17 = $barbecue_17</p>");
      print_r("<p>barbecue 26 = $barbecue_26</p>");
      print_r("<p>repas de cloture = $repas_cloture</p>");
      print_r("<p>petit dej = $petit_dej</p>");
      print_r("<p>dej = $dej</p>");
      print_r("<p>diner = $diner</p>");
    }

}
