<?php

namespace App\Http\Controllers;

use App\Post;
use App\Credit;
use Validator;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class PostController extends Controller
{


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //

        $validator = Validator::make($request->all(), [
         'post' => 'required|string',
        ]);

        if ($validator->fails()){

         return ['error' => $validator->errors()->toArray()];
        }else{
          $text = $request->post;
          include(app_path() . '\functions\smiles.php');

          Post::create([
                'user_id' => Auth::user()->id,
                'message' => $text,
                'type' => 'post'
            ]);
          return 'success';
        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        //
        $remaining = new RemainingController;

        $carbon = new Carbon;


        Auth::user()->notifications()
        ->where('type','App\Notifications\Comments')
        ->delete();

        $smiles = Storage::disk('public')->files('smiles');
        $posts = Post::orderBy('updated_at', 'desc')->withCount('comments')->take(100)->simplePaginate(10);
        return view('sign-in.chat',compact('smiles','posts','carbon','remaining'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        
        $post = Post::find($request->id);
        $post->delete();
        return 'success';

    }


    public function up_vote(Request $request){

        $post = Post::find($request->id);

        if (!Auth::user()->hasDownVoted($post)) {
           Auth::user()->upVote($post);

           Credit::where('user_id',$post->user_id)->increment('value', 2);

        }
 
        return $post->countUpVoters();

    }


    public function down_vote(Request $request){

        $post = Post::find($request->id);

        if (!Auth::user()->hasUpVoted($post)) {
          Auth::user()->downVote($post);
        }

        return $post->countDownVoters();

    }

}
