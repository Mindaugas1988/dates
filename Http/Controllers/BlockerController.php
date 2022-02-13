<?php

namespace App\Http\Controllers;

use App\Block;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class BlockerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $blocks = Auth::user()->block_list()->simplePaginate(10);
        $carbon = new Carbon();


        return view('sign-in.block_list',compact('blocks','carbon'));
    }


    public function blocked_page($id){

       return view('sign-in.blocked',compact('id'));

    }

   
     
    public function store(Request $request)
    {
        //

           $block = Block::Create(
            ['user_id' => Auth::user()->id,'block_id'=> $request->id]
          );

          return 'success';
    }

   
    
    public function destroy(Request $request)
    {
        Auth::user()->block_list()->where('block_id', $request->id)->delete();
        return 'success';
    }
}
