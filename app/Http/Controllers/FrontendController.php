<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class FrontendController extends Controller
{
    //
    public function getHome()
    {
        $data['featured'] = Product::where('featured', config('constant.one'))->orderBy('id', 'desc')->take(config('constant.eight'))->get();
        $data['new_product'] = Product::orderBy('id', 'desc')->take(config('constant.sixteen'))->get();

        return view('frontend.home', $data);
    }

    public function getDetail($id)
    {
        $data['item'] = Product::findOrFail($id);

        return view('frontend.details', $data);
    }
}
