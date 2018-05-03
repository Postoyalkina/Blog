<?php

namespace App\Http\Controllers;

use App\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CommentController extends Controller
{
    protected $redirectTo = '/home';

    public function create(int $postId,Request $request){
        if($request->input('id')){
            $objComment = Comment::find($request->input('id'));
            if(!$objComment){
                return abort(404);
            }
            $objComment->body = $request->input('body');
            if($objComment->save()) {
                return redirect()->route('showPost',['id'=>$postId]);
            }
        }
        $data = array(
            'user_id' => \Auth::user()->id,
            'post_id' => $postId,
            'body' => $request->input('body'),
        );
        $comment = new Comment();
        $comment->create($data);
        return redirect()->route('showPost',['id'=>$postId]);
    }

    public function edit(int $id){
        $objComment = Comment::find($id);
        if(!$objComment){
            return abort(404);
        }
        if($objComment->user_id != \Auth::user()->id){
            return redirect(route('account'));
        }
        return view('account.edit',[
            'post'=>$objComment
        ]);
    }

    public function editRequest(int $id, Request $request){

        $objComment= Comment::find($id);
        if(!$objComment) {
            return abort(404);
        }
        $objComment->body = $request->input('body');
        if($objComment->save()) {
            return redirect()->route('showPost',['id'=>$objComment->id]);
        }
    }

    public function delete(int $id)
    {
        $postId = Comment::find($id)->post_id;
        if(Comment::find($id)->user_id == \Auth::user()->id) {
            DB::delete('delete from comments where id = ?', [$id]);
        }
        return redirect()->route('showPost',['id'=>$postId]);
    }
}
