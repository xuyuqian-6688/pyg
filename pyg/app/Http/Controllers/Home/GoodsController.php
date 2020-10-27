<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GoodsController extends Controller
{
    //
    public function index()
    {
        return view("home.goods.index");
    }

    //
    public function detail(){
        return view("home.goods.detail");
    }
}
