<?php

namespace App\Http\Controllers;

use App\Photo;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\User;

class PhotoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $id = Auth::user()->id;
        //$photos = Photo::where('user_id',$id)->get();
        $photos = User::find(Auth::id())->photos;

        return view('sign-in.photo-gallery',compact('photos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        if ($request->hasFile('files')) {

            $photos = count([$request->all()]);
            foreach(range(0, $photos) as $index) {
            $rules['files.' . $index] = 'image|mimes:jpeg,bmp,png|max:10000';
            }

            $validation = Validator::make($request->all(),$rules);

            if ($validation->fails())  {  

             //return ['error' => $validation->errors()->toArray()];
               return trans('words.photos_error');

            }else if(Photo::where('user_id',Auth::user()->id)->count()>9){

            return trans('words.limited');

            }else{

            foreach ($request->file('files') as $photo) {
            $filename = Storage::disk('public')->put('galleries/'.Auth::user()->id.'/', $photo);
            Photo::create([
                'user_id' => Auth::user()->id,
                'photo' => $filename
            ]);
             }

            //return ['success' => $results->toArray()];
            return ['success' => 'success'];

            }
    //   
            
         }else{

            return trans('words.no-select');
         }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Photo  $photo
     * @return \Illuminate\Http\Response
     */

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Photo  $photo
     * @return \Illuminate\Http\Response
     */
    public function change(Request $request)
    {
        //
        $id = $request->id;
        $photo = $request->photo;
        $new = Photo::find($id);
        $old = Photo::where('user_id',Auth::user()->id)->first();

        $new->photo = $old->photo;
        $old->photo = $photo;

        $new->save();
        $old->save();
        return 1;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Photo  $photo
     * @return \Illuminate\Http\Response
     */

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Photo  $photo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        //
             $photos = $request->data;

              foreach($photos as $photo) {
              $gallery = Photo::find($photo);
              Storage::disk('public')->delete($gallery->photo);
              $gallery->delete();
            }

            return 1;
    }
}
