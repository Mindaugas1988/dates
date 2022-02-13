<?php

namespace App\Http\Controllers;

use App\Post_comment;
use App\User;
use App\Post;
use App\Block;
use App\Credit;
use Carbon\Carbon;
use Validator;
use Illuminate\Support\Facades\Auth;
use App\Notifications\Comments;
use Illuminate\Http\Request;



class PostCommentController extends Controller
{

    public function __construct()
    {

    $this->middleware(function ($request, $next) {


    $result = Block::where('user_id', $request->post_user)
        ->where('block_id', Auth::user()->id)
        ->exists();
    // ...
    if ($result) {

    return response('Jus esate uzblokuotas.');

    }

    return $next($request);


    })->only('store');

    }
    

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
         'comment' => 'required|string',
        ]);

        if ($validator->fails()){

         return ['error' => $validator->errors()->toArray()];
        }else{
          $text = $request->comment;
          include(app_path() . '\functions\smiles.php');

          Post_comment::create([
                'post_id' => $request->post_id,
                'user_id' => Auth::user()->id,
                'comment' => $text
            ]);

          Post::where('id', $request->post_id)
          ->update(['updated_at' => Carbon::now()]);

          Credit::where('user_id',Auth::user()->id)->increment('value', 5);

          $this->add_notification($request->post_id);
           

          return 'success';
        }
    }



    public function up_vote(Request $request){

        $comm = Post_comment::find($request->id);

        if (!Auth::user()->hasDownVoted($comm)) {
           Auth::user()->upVote($comm);

           Credit::where('user_id',$comm->user_id)->increment('value', 2);

        }
 
        return $comm->countUpVoters();

    }


    public function down_vote(Request $request){

        $comm = Post_comment::find($request->id);

        if (!Auth::user()->hasUpVoted($comm)) {
          Auth::user()->downVote($comm);
        }

        return $comm->countDownVoters();

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Post_comment  $post_comment
     * @return \Illuminate\Http\Response
     */
   
    public function destroy(Request $request)
    {
        //
        $comment = Post_comment::find($request->id);
        $comment->delete();
        return 'success';
    }

    private function add_notification($post){

     $post = Post::find($post);
     $item = array();

     if ($post->user->id !== Auth::user()->id) {
         
         User::find($post->user->id)->notify(new Comments());
     }

     

     foreach ($post->comments as $comment) {

        if ($post->user->id === $comment->user_id) {
            continue;
        }
        array_push($item,$comment->user_id);
     }


     foreach (array_unique($item) as $id) {

         User::find($id)->notify(new Comments());
     }

    }
}
