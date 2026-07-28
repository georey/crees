<?php

return [

	/*
	|--------------------------------------------------------------------------
	| Validation Language Lines
	|--------------------------------------------------------------------------
	|
	| The following language lines contain the default error messages used by
	| the validator class. Some of these rules have multiple versions such
	| as the size rules. Feel free to tweak each of these messages here.
	|
	*/

	"accepted"             => "El :attribute debe ser aceptado.",
	"active_url"           => "El :attribute no es una URL válida.",
	"after"                => "El :attribute debe ser una fecha posterior a :date.",
	"alpha"                => "El :attribute solo puede contener letras.",
	"alpha_dash"           => "El :attribute solo puede contener letras, números y guiones.",
	"alpha_num"            => "El :attribute solo puede contener letras y números.",
	"array"                => "El :attribute debe ser un arreglo.",
	"before"               => "El :attribute debe ser una fecha anterior a :date.",
	"between"              => [
		"numeric" => "El :attribute debe estar entre :min y :max.",
		"file"    => "El :attribute debe pesar entre :min y :max kilobytes.",
		"string"  => "El :attribute debe tener entre :min y :max caracteres.",
		"array"   => "El :attribute debe tener entre :min y :max elementos.",
	],
	"boolean"              => "El :attribute debe ser verdadero o falso.",
	"confirmed"            => "La confirmación de :attribute no coincide.",
	"date"                 => "El :attribute no es una fecha válida.",
	"date_format"          => "El :attribute no coincide con el formato :format.",
	"different"            => "El :attribute y :other deben ser diferentes.",
	"digits"               => "El :attribute debe tener :digits dígitos.",
	"digits_between"       => "El :attribute debe tener entre :min y :max dígitos.",
	"email"                => "El :attribute debe ser una dirección de correo válida.",
	"filled"               => "El campo :attribute es obligatorio.",
	"exists"               => "El :attribute seleccionado no es válido.",
	"image"                => "El :attribute debe ser una imagen.",
	"in"                   => "El :attribute seleccionado no es válido.",
	"integer"              => "El :attribute debe ser un entero.",
	"ip"                   => "El :attribute debe ser una dirección IP válida.",
	"max"                  => [
		"numeric" => "El :attribute no debe ser mayor que :max.",
		"file"    => "El :attribute no debe pesar más de :max kilobytes.",
		"string"  => "El :attribute no debe tener más de :max caracteres.",
		"array"   => "El :attribute no debe tener más de :max elementos.",
	],
	"mimes"                => "El :attribute debe ser un archivo de tipo: :values.",
	"min"                  => [
		"numeric" => "El :attribute debe ser al menos :min.",
		"file"    => "El :attribute debe pesar al menos :min kilobytes.",
		"string"  => "El :attribute debe tener al menos :min caracteres.",
		"array"   => "El :attribute debe tener al menos :min elementos.",
	],
	"not_in"               => "El :attribute seleccionado no es válido.",
	"numeric"              => "El :attribute debe ser un número.",
	"regex"                => "El formato de :attribute no es válido.",
	"required"             => "El campo :attribute es obligatorio.",
	"required_if"          => "El campo :attribute es obligatorio cuando :other es :value.",
	"required_with"        => "El campo :attribute es obligatorio cuando :values está presente.",
	"required_with_all"    => "El campo :attribute es obligatorio cuando :values están presentes.",
	"required_without"     => "El campo :attribute es obligatorio cuando :values no está presente.",
	"required_without_all" => "El campo :attribute es obligatorio cuando ninguno de :values está presente.",
	"same"                 => "El :attribute y :other deben coincidir.",
	"size"                 => [
		"numeric" => "El :attribute debe ser :size.",
		"file"    => "El :attribute debe pesar :size kilobytes.",
		"string"  => "El :attribute debe tener :size caracteres.",
		"array"   => "El :attribute debe contener :size elementos.",
	],
	"unique"               => "El :attribute ya ha sido tomado.",
	"url"                  => "El formato de :attribute no es válido.",
	"timezone"             => "El :attribute debe ser una zona horaria válida.",

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
			'rule-name' => 'custom-message',
		],
	],

	/*
	|--------------------------------------------------------------------------
	| Custom Validation Attributes
	|--------------------------------------------------------------------------
	|
	| The following language lines are used to swap attribute place-holders
	| with something more reader friendly such as E-Mail Address instead
	| of "email". This simply helps us make messages a little cleaner.
	|
	*/

	'attributes' => [
		'correo' => 'correo',
	],

];
