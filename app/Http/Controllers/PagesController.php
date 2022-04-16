<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PagesController extends Controller
{
    //Accueil
    public function welcome()
    {  
        return view('index');
    }

}
