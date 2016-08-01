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

    'accepted'             => 'The :attribute must be accepted.',
    'active_url'           => 'The :attribute is not a valid URL.',
    'after'                => 'The :attribute must be a date after :date.',
    'alpha'                => 'The :attribute may only contain letters.',
    'alpha_dash'           => 'The :attribute may only contain letters, numbers, and dashes.',
    'alpha_num'            => 'The :attribute may only contain letters and numbers.',
    'array'                => 'The :attribute must be an array.',
    'before'               => 'The :attribute must be a date before :date.',
    'between'              => [
        'numeric' => 'The :attribute must be between :min and :max.',
        'file'    => 'The :attribute must be between :min and :max kilobytes.',
        'string'  => 'The :attribute must be between :min and :max characters.',
        'array'   => 'The :attribute must have between :min and :max items.',
    ],
    'boolean'              => 'The :attribute field must be true or false.',
    'confirmed'            => 'The :attribute confirmation does not match.',
    'date'                 => 'The :attribute is not a valid date.',
    'date_format'          => 'The :attribute does not match the format :format.',
    'different'            => 'The :attribute and :other must be different.',
    'digits'               => 'The :attribute must be :digits digits.',
    'digits_between'       => 'The :attribute must be between :min and :max digits.',
    'distinct'             => 'The :attribute field has a duplicate value.',
    'email'                => 'Nije uneta validna e-mail adresa.',
    'exists'               => 'The selected :attribute is invalid.',
    'filled'               => 'The :attribute field is required.',
    'image'                => 'The :attribute must be an image.',
    'in'                   => 'The selected :attribute is invalid.',
    'in_array'             => 'The :attribute field does not exist in :other.',
    'integer'              => 'The :attribute must be an integer.',
    'ip'                   => 'The :attribute must be a valid IP address.',
    'json'                 => 'The :attribute must be a valid JSON string.',
    'max'                  => [
        'numeric' => 'The :attribute may not be greater than :max.',
        'file'    => 'The :attribute may not be greater than :max kilobytes.',
        'string'  => 'The :attribute may not be greater than :max characters.',
        'array'   => 'The :attribute may not have more than :max items.',
    ],
    'mimes'                => 'The :attribute must be a file of type: :values.',
    'min'                  => [
        'numeric' => 'The :attribute must be at least :min.',
        'file'    => 'The :attribute must be at least :min kilobytes.',
        'string'  => 'The :attribute must be at least :min characters.',
        'array'   => 'The :attribute must have at least :min items.',
    ],
    'not_in'               => 'The selected :attribute is invalid.',
    'numeric'              => 'The :attribute must be a number.',
    'present'              => 'The :attribute field must be present.',
    'regex'                => 'The :attribute format is invalid.',
    'required'             => 'The :attribute field is required.',
    'required_if'          => 'The :attribute field is required when :other is :value.',
    'required_unless'      => 'The :attribute field is required unless :other is in :values.',
    'required_with'        => 'The :attribute field is required when :values is present.',
    'required_with_all'    => 'The :attribute field is required when :values is present.',
    'required_without'     => 'The :attribute field is required when :values is not present.',
    'required_without_all' => 'The :attribute field is required when none of :values are present.',
    'same'                 => 'The :attribute and :other must match.',
    'size'                 => [
        'numeric' => 'The :attribute must be :size.',
        'file'    => 'The :attribute must be :size kilobytes.',
        'string'  => 'The :attribute must be :size characters.',
        'array'   => 'The :attribute must contain :size items.',
    ],
    'string'               => 'The :attribute must be a string.',
    'timezone'             => 'The :attribute must be a valid zone.',
    'unique'               => 'Uneti :attribute već postoji.',
    'url'                  => 'The :attribute format is invalid.',

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
        'email' => [
            'required' => 'Niste uneli e-mail adresu!',
            'unique' => 'Već postoji korisnik sa unetom e-mail adresom!',
            'max' => 'E-mail adresa mora da sadrži najviše 255 karaktera!',
        ],
        'password' => [
            'required' => 'Niste uneli lozinku!',
            'min' => 'Lozinka mora da sadrži najmanje 8 karatera!',
            'max' => 'Lozinka mora da sadrži najviše 255 karaktera!',
            'confirmed' => 'Ne poklapaju se lozinka i potvrda lozinke!',
        ],
        'fname' => [
            'required' => 'Niste uneli ime!',
            'alpha' => 'Ime mora da sadrži isključivo slova!',
            'max' => 'Ime mora da sadrži najviše 50 karaktera!',
        ],
        'lname' => [
            'required' => 'Niste uneli prezime!',
            'alpha' => 'Prezime mora da sadrži isključivo slova!',
            'max' => 'Prezime mora da sadrži najviše 50 karaktera!',
        ],
        'title' => [
            'required' => 'Niste uneli zvanje!',
            'different' => 'Niste uneli zvanje!'
        ],
        'department' => [
            'required' => 'Niste uneli katedru!',
            'different' => 'Niste uneli katedru!',
        ],
        'token' => [
            'required' => 'Nije dostavljen token!'
        ],
        'naslov' => [
            'required' => 'Niste uneli naslov!',
            'max' => 'Naslov mora da sadrži najviše 100 karaktera!',
            'unique' => 'Već postoji glasanje sa unetim imenom!',
        ],
        'opis' => [
            'required' => 'Niste uneli opis!',
            'max' => 'Opis mora da sadrži najviše 65535 karaktera!',
        ],
        'vreme1' => [
            'required' => 'Niste uneli vreme početka!',
            'after' => 'Vreme početka nije validno!',
        ],
        'vreme2' => [
            'required' => 'Niste uneli vreme završetka!',
            'after' => 'Vreme završetka nije posle vremena početka!',
        ],
        'odgovori' => [
            'required' => 'Potrebno je uneti bar 2 odgovora!',
            'min' => 'Potrebno je uneti bar 2 odgovora!',
        ],
        'glasaci' => [
            'required' => 'Niste uneli glasače!',
            'min' => 'Potrebno je uneti bar 1 glasača!',
        ],
        'ticket' => [
            'required' => 'Niste uneli tiket!',
            'exists' => 'Uneti tiket nije validan!',
        ],
        'optionsRadios' => [
            'required' => 'Niste uneli odgovor!',
        ],
        'optionsCheckbox' => [
            'required' => 'Niste uneli odgovor!',
        ],
        'relacija' => [
            'required' => 'Niste uneli relaciju!',
            'different' => 'Niste uneli relaciju!',
        ],
        'vrednost' => [
            'required' => 'Niste uneli vrednost!',
            'integer' => 'Vrednost nije ceo broj!',
            'between' => 'Vrednost mora biti u okviru broja glasača!'
        ],
        'criteriumRadios' => [
            'required' => 'Niste odabrali kriterijum!',
        ],
        'opcija' => [
            'required' => 'Niste odabrali opciju za kriterijum!',
            'different' => 'Niste odabrali opciju za kriterijum!',
        ],
        'opcija_odg' => [
            'required' => 'Ne postoji unet takav odgovor za kriterijum!',
        ],
        'min_max' => [
            'required' => 'Minimum nije manji od maksimuma!',
        ],
        'minimum' => [
            'array' => 'Minimum mora da bude veći od 0 i manji ili jednak broju odgovora!',
        ],
        'maximum' => [
            'array' => 'Maksimum mora da bude manji ili jednak broju odgovora!',
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

    'attributes' => [],

];
