<?php

namespace App\Http\Controllers;

use App\Service\BasicDisplayApi;
use Illuminate\Http\Request;

class PostController extends Controller
{
    
    public function index()
    {
        $basicDisplayApi = new BasicDisplayApi();
        $posts = $basicDisplayApi->getMediasDatas();

        return view('welcome', compact('posts'));
    }
}
