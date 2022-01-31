<?php

namespace Tests\Unit;


use Tests\TestCase;
use App\Helpers\TranslationHelper as Trans;

class TranslationHelperTest extends TestCase {

	public function testTranslate() {
		// there is no real translation from en to en
		$this->assertEquals('', Trans::translate(''));
		$this->assertEquals('key', Trans::translate('key'));
		$this->assertEquals('Key', Trans::translate('key', "en", true));
		
		return;  // Following tests are disabled to avoid tranlation costs

		$this->assertEquals('Clé', Trans::translate('key', "fr", true));
		
		echo Trans::translate('The :attribute must be between :min and :max.', "fr", true);
		echo Trans::translate('The :attribute must be a string.', "fr", true);
		
	}
	
	public function test_pretty_print() {
		$v = [];
		$pretty = Trans::pretty_print($v);
		$expected = '[]';
		$this->assertEquals($expected, $pretty);
		
		$v =
		[
		
		/*
		 |--------------------------------------------------------------------------
		 | Menu strings to translate (English version)
		 |--------------------------------------------------------------------------
		 |
		 |
		 */
		
		'title' => "Sauvegardes sur le serveur",
		'number' => 'Numéro',
		'restore' => 'Restaurer',
		'backup' => 'Sauvegarde',
		'new' => 'Faire une sauvegarde',
		'not_found' => 'Sauvegarde :id inconnue',
		'deleted' => 'Sauvegarde :id supprimée',
		'restored' => 'Sauvegarde :id restaurée',
		'created' => 'Sauvegarde :id réalisée',
		'error' => 'Erreur durant la sauvegarde :id',
		
		'upload_backup' => 'Charger une sauvegarde',
		];
		
		$pretty = Trans::pretty_print($v);
		// echo $pretty;
		$expected = "[
\t\"title\" => \"Sauvegardes sur le serveur\",
\t\"number\" => \"Numéro\",
\t\"restore\" => \"Restaurer\",
\t\"backup\" => \"Sauvegarde\",
\t\"new\" => \"Faire une sauvegarde\",
\t\"not_found\" => \"Sauvegarde :id inconnue\",
\t\"deleted\" => \"Sauvegarde :id supprimée\",
\t\"restored\" => \"Sauvegarde :id restaurée\",
\t\"created\" => \"Sauvegarde :id réalisée\",
\t\"error\" => \"Erreur durant la sauvegarde :id\",
\t\"upload_backup\" => \"Charger une sauvegarde\"
]";
		$this->assertEquals($expected, $pretty);

		$v =
		[				
				'title' => "Sauvegardes sur le serveur",
				'colors' => ['blue', 'red', 'green'],
				'restore' => 'Restaurer'
		];
		
		$pretty = Trans::pretty_print($v);
		$expected = "[
\t\"title\" => \"Sauvegardes sur le serveur\",
\t\"colors\" => [\"blue\", \"red\", \"green\"],
\t\"restore\" => \"Restaurer\"
]";
		echo $pretty;
		$this->assertEquals($expected, $pretty);
		
	}
}
