<?php

namespace App\Http\Controllers;

use App\Models\Competicion;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function contactos()
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
        return view('home.contactos', compact('contact'));
    }

    public function clasificados(Request $request) {
        return view('home.clasificados');
    }

    public function etapas(Request $request)
    {
        // Trae la última competición (o filtra por ?competicion_id=)
        $competicion = Competicion::with(['etapas' => fn($q) => $q->orderBy('orden')])
            ->when($request->integer('competicion_id'), fn($q,$id)=>$q->where('id',$id))
            ->latest('anio')
            ->first();

        $etapas = $competicion?->etapas ?? collect();

        return view('home.etapas', compact('competicion','etapas'));
    }
}
