<?php

namespace App\Http\Controllers;

use App\Battle;
use App\User;
use App\Post;
use App\Credit;
use Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Notifications\NewDual;
use Illuminate\Http\Request;

class BattleController extends Controller
{
  
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $new_battles = Battle::where('invite_id',Auth::user()->id)->get();
        Auth::user()->notifications()
        ->where('type','App\Notifications\NewDual')
        ->delete();
        return view('sign-in.battles',compact('new_battles'));
    }

    
    public function store(Request $request)
    {
        //
         $validator = Validator::make($request->all(), [
         'content' => 'required|string',
        ]);

        if ($validator->fails()){

        return [trans('words.invite-fields-error')];

        }
        else if(Auth::user()->credits->value < 50){
         
        return [trans('words.no-credits')];

        }
        else{
          $content = $request->content;
          $invite_id = $request->invite_id;
          Battle::create([
                'user_id' => Auth::user()->id,
                'invite_id' => $invite_id,
                'content' => $content,   
            ]);

          Credit::where('user_id',Auth::user()->id)->decrement('value', 50);
          User::find($invite_id)->notify(new NewDual());
          return ['success',trans('words.success-invite')];




          
        }
    }

    public function accept(Request $request)
    {
        //
         $id = $request->id;
         $battle = Battle::find($id);

         Post::create([
                'user_id' => $battle->user_id,
                'message' => $battle->content,
                'battle_id' => Auth::user()->id,
                'type' => 'duel'
            ]);

         $battle->delete();

         return 'success';
    }

   
 
    public function cancel(Request $request)
    {
        //
         $id = $request->id;
         $battle = Battle::find($id);
         Credit::where('user_id',$battle->user_id)->increment('value', 50);
         $battle->delete();

         return 'success';
    }


    public function vote(Request $request)
    {

        $duel = Post::find($request->duel_id);

        $remaining_time = new RemainingController;

        if ($remaining_time->get($duel->created_at)>0) {
           Auth::user()->upVote($duel, $request->for_id);
        }

        return 'success';
        
        
    }
}
