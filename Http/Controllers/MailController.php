<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    //
     public function mail(Request $request){
    	
          $validator = Validator::make($request->all(), [
         'email' => 'email|required|string',
         'name' => 'required|string',
         'subject' => 'required|string',
         'message' => 'required|string',
          ]);


          if ($validator->fails())  {  

            // ['error' => $validator->errors()->toArray()];
            return redirect('contact')->withErrors($validator);

          }else{

          $mail = $request->email;
          $name = $request->name;
          $message1 =$request->message;
          $subject = $request->subject;

           Mail::send('mail.send', ['title' => $name, 'content' => $message1,'sender' =>$mail], function ($message) use($mail,$name,$subject)
           {

            $message->from($mail,$name);

            $message->to('m.virbickis88@gmail.com')->subject($subject);

            $message->sender($mail, $name);

            $message->replyTo($mail, $name = null);

            $message->cc($mail, $name = null);
            $message->bcc($mail, $name = null);

            });


            return redirect('contact')->with('success', 'Sent Success');

          }
        
    }
}
