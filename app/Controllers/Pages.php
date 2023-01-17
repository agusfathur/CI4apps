<?php

namespace App\Controllers;

class Pages extends BaseController
{
    public function index()
    {   
        $data = [
            'title' => 'Home | Web Agus Fathur',
            'tes' => ['satu', 'dua','tiga']
        ];
        return view('pages/Home', $data);
    }
    public function about()
    {
        $data = [
            'title' => 'About Me'
        ];
        return view('pages/about',$data);
    }
    public function contact()
    {
        $data = [
            'title' => 'Contact Us',
            'alamat' => [
            [
                'tipe' => 'Rumah',
                'alamat' => 'Jl abc No 123',
                'kota' => 'Bandung'
            ],
            [
                'tipe' => 'kantor',
                'alamat' => 'Jl. Stiabudi No 193',
                'kota' =>'Bandung'
            ]
        ]
    ];
        return view('pages/contact',$data);
    }
}
