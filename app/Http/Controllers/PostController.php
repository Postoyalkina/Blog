<?php

namespace App\Http\Controllers;

use App\Post;
use App\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    protected $redirectTo = '/home';

    public function showPost(int $id){
        $post = Post::find($id);
        $comments = Comment::orderBy('created_at')
            ->where('post_id',$id)
            ->get();
        return view('post',['post'=>$post,'comments'=>$comments]);
    }

    public function create(Request $request){
        $data = array(
            'user_id' => \Auth::user()->id,
            'title' => $request->input('title'),
            'body' => $request->input('body'),
        );
        $post = new Post();
        $post->create($data);
        return redirect(route('account'));
    }

    public function edit(int $id){
        $objPost = Post::find($id);
        if(!$objPost){
            return abort(404);
        }
        if($objPost->user_id != \Auth::user()->id){
            return redirect(route('account'));
        }
        return view('account.edit',[
            'post'=>$objPost
        ]);
    }

    public function editRequest(int $id, Request $request){

        $objPost = Post::find($id);
        if(!$objPost) {
            return abort(404);
        }
        $objPost->title = $request->input('title');
        $objPost->body = $request->input('body');
        if($objPost->save()) {
            return redirect()->route('showPost',['id'=>$objPost->id]);
        }
    }

    public function delete(int $id)
    {
        if(Post::find($id)->user_id == \Auth::user()->id) {
            DB::delete('delete from posts where id = ?', [$id]);
            DB::delete('delete from comments where post_id = ?', [$id]);
        }
        return redirect()->route('account');
    }
}
