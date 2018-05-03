<?php

namespace App\Http\Controllers;

use App\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class AccountController extends Controller
{
    public function index(){
        $posts = DB::table('posts')
            ->where('user_id', \Auth::user()->id)
            ->orderBy('created_at','dec')
            ->get();
        return \View::make('account\index',['posts'=>$posts,'name'=>\Auth::user()->name]);
    }
}
