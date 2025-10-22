<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * Muestra la página pública de Contactos.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Datos de contacto de la UMSS
        $contact = [
            'email'     => 'informaciones@umss.edu.bo',
            'phone'     => '(+591) 4 4525161',
            'address'   => 'Av. Oquendo y Jordan',
            'facebook'  => 'https://www.facebook.com/UmssBolOficial/',
            'instagram' => 'https://www.instagram.com/umssboloficial/?hl=es',
            'tiktok'    => 'https://www.tiktok.com/discover/universidad-mayor-de-san-sim%C3%B3n',
        ];

        // Retorna la vista resources/views/contactos.blade.php
        // usando el layout público app1.blade.php
        return view('contactos', compact('contact'));
    }
}


