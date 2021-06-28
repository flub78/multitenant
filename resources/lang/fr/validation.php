<?php
return [ 

		/*
		 * |--------------------------------------------------------------------------
		 * | Validation Language Lines
		 * |--------------------------------------------------------------------------
		 * |
		 * | The following language lines contain the default error messages used by
		 * | the validator class. Some of these rules have multiple versions such
		 * | as the size rules. Feel free to tweak each of these messages here.
		 * |
		 */

		'accepted' => ':attribute doit être accepté.',
		'active_url' => ":attribute n'est pas une URL valide.",
		'after' => ':attribute doit être une date postérieure à :date.',
		'after_or_equal' => ':attribute doit être une date postérieure ou égale à :date.',
		'alpha' => ':attribute ne doit contenir que des lettres.',
		'alpha_dash' => ':attribute ne doit contenier que des lettres, des chiffres et des tirets.',
		'alpha_num' => ':attribute ne doit contenier que des lettres et des chiffres.',
		'array' => ':attribute doit être une liste.',
		'before' => ':attribute doit être une date antérieure à :date.',
		'before_or_equal' => ':attribute doit être une date antérieure ou égale à :date.',
		'between' => [ 
				'numeric' => ':attribute doit être entre :min et :max.',
				'file' => 'Le taille du fichier :attribute doit être entre :min et :max kilobytes.',
				'string' => 'La chaine :attribute doit avoir entre :min et :max caracteres.',
				'array' => 'La liste :attribute doit avoir entre :min et :max éléments.'
		],
		'boolean' => ':attribute doit être true ou false.',
		'confirmed' => 'Valeur de confirmation incorrecte pour :attribute.',
		'date' => ":attribute n'est pas une date valide.",
		'date_format' => 'Format incorrect pour :attribute format attendu :format.',
		'different' => 'Les champs :attribute et :other doivent être different.',
		'digits' => ':attribute doit avoir :digits chiffres.',
		'digits_between' => ':attribute doit avoir entre :min et :max chiffres.',
		'dimensions' => "L'image :attribute a des dimensions incorrectes.",
		'distinct' => ':attribute est dupliqué.',
		'email' => ':attribute doit être une adresse email valide.',
		'exists' => 'La selection :attribute est invalide.',
		'file' => ':attribute doit être un fichier.',
		'filled' => ':attribute ne doit pas être vide.',

		'date_equals' => ':attribute doit être une date valide égale à :date.',
		'ends_with' => ':attribute doit se terminer avec: :values.',

		'gt' => [ 
				'numeric' => ':attribute doit être supérieur à :value.',
				'file' => 'Le fichier :attribute doit faire plus de :value Ko.',
				'string' => ':attribute dot avoir plus de :value caractères.',
				'array' => 'Le tableau :attribute doit contenir plus de :value objets.'
		],
		'gte' => [ 
				'numeric' => ':attribute attribute doit être supérieur ou égale à :value.',
				'file' => 'Le fichier :attribute doit faire plus de :value Ko.',
				'string' => ':attribute doit avoir plus de :value caractères.',
				'array' => 'Le tableau :attribute doit contenir plus de :value objets.'
		],
		'image' => ':attribute doit être une image.',
		'in' => 'La selection :attribute est invalide.',
		'in_array' => ':attribute ne doit pas exister dans :other.',
		'integer' => ':attribute doit être un entier.',
		'ip' => ':attribute doit être une adresse IP valide.',
		'ipv4' => ':attribute doit être une adresse IPv4 valide.',
		'ipv6' => ':attribute doit être une adresse IPv6 valide.',
		'json' => ':attribute doit être une chaine JSON.',
		'lt' => [ 
				'numeric' => ':attribute doit être inférieur à :value.',
				'file' => 'Le fichier :attribute doit faire moins de :value Ko.',
				'string' => ':attribute dot avoir moins de :value caractères.',
				'array' => 'Le tableau :attribute doit contenir moins de :value objets.'
		],
		'lte' => [ 
				'numeric' => ':attribute attribute doit être inférieur ou égale à :value.',
				'file' => 'Le fichier :attribute doit faire moins de :value Ko.',
				'string' => ':attribute doit avoir moins de :value caractères.',
				'array' => 'Le tableau :attribute doit contenir moins de :value objets.'
		],
		'max' => [ 
				'numeric' => ':attribute doit être inférieur à :max.',
				'file' => 'Le fichier :attribute doit faire moins de :max Ko.',
				'string' => ':attribute dot avoir moins de :max caractères.',
				'array' => 'Le tableau :attribute doit contenir moins de :max objets.'
		],
		'mimes' => 'The :attribute must be a file of type: :values.',
		'mimetypes' => 'The :attribute must be a file of type: :values.',
		'min' => [ 
				'numeric' => ':attribute attribute doit être supérieur ou égale à :min.',
				'file' => 'Le fichier :attribute doit faire plus de :min Ko.',
				'string' => ':attribute doit avoir plus de :min caractères.',
				'array' => 'Le tableau :attribute doit contenir plus de :min objets.'
		],
		'multiple_of' => ':attribute doit être un multiple de :value.',

		'not_in' => ':attribute est invalide.',
		'not_regex' => 'Le format du champ :attribute est incorrect.',
		'numeric' => ':attribute doit être un nombre.',
		'present' => ':attribute ne doit pas être vide.',
		'regex' => 'Le format du champ :attribute est invalide.',
		'required' => ':attribute est obligatoire.',
		'required_if' => ':attribute est obligatoire quand :other vaut :value.',
		'required_unless' => ':attribute est obligatoire quand :other est dans :values.',
		'required_with' => ':attribute est obligatoire quand :values est present.',
		'required_with_all' => ':attribute est obligatoire quand :values est present.',
		'required_without' => ':attribute est obligatoire quand :values est absent.',
		'required_without_all' => ':attribute est obligatoire quand aucun :values est present.',
		'same' => ':attribute et :other doivent être équivalent.',
		'size' => [ 
				'numeric' => 'La taille du champ :attribute doit être :size.',
				'file' => 'La taille du fichier :attribute doit être :size kilobytes.',
				'string' => 'La longueur de la chaine :attribute doit être :size characteres.',
				'array' => 'La liste :attribute doit contenir :size éléments.'
		],

		'prohibited' => 'Le champ :attribute est interdit.',
		'prohibited_if' => 'Le champ :attribute est interdit quand :other vaut :value.',
		'prohibited_unless' => 'Le champ :attribute est interdit sauf si :other is vaut :values.',

		'starts_with' => ':attribute doit commencer avec : :values.',

		'string' => ':attribute doit être une chaine de caractères.',
		'timezone' => ':attribute doit être une timezone.',
		'unique' => ':attribute doit être unique, la valeur existe déjà.',
		'uploaded' => "Echec du téléchargement du fichier :attribute.",
		'url' => 'Le format du champ :attribute invalide.',

		'uuid' => ':attribute doit être un UUID valid.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [ 
				'attribute-name' => [ 
						'rule-name' => 'custom-message'
				]
		],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [ 
			'start' => 'Date',
    		'title' => 'Evénement',
    		'event_desc' => "Description",
    		'groupId' => 'Catégorie',
    		'allday' => 'Toute la journée',
    		'start_date' => 'Date',
    		'end_date' => 'Date de fin',
    		'start_time' => 'Heure',
    		'end_time' => 'Heure de fin',
    		'duration' => 'Durée',
    		'background_color' => 'Couleur de fond',
    		'text_color' => 'Couleur du texte'
    ]
];
