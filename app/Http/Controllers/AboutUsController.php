<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AboutUsController extends Controller
{
    function index(string $id){
        return view('about-us', ['id'=>$id]);
 }
}
