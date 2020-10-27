<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CartController extends Controller
{
    public function index()
    {
        return view("home.cart.index");
    }

    //
    public function addcart()
    {
        return view("home.cart.addcart");
    }


}
