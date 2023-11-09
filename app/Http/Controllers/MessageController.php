<?php

namespace App\Http\Controllers;

use App\Events\Message;
use App\Models\Message as ModelsMessage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
   
    public function index(Request $request){
        $test = User::where('name', $request->data['*'])->first();
        if($test->id == Auth::user()->id){
            $mess= [];
        }else{
            $mess = ModelsMessage::where('sender_user_id', Auth::user()->id)->orWhere(function($query) use ($test) {
                $query->where('sender_user_id', $test->id);
            })->get();
        }
        
        
        return  $mess;   
    }
    public function create(Request $request){
        $mess = new ModelsMessage($request->all());
        $mess->sender_user_id = Auth::user()->id;
        // $mess->receiver_user_id =$request->receiver_user_id; // mở ra test   post man
        // $mess->content =$request->content; // mở ra test   post man
        $mess->receiver_user_id =$request->data['receiver_user_id']; // mở ra test ở màn hình chính
        $mess->content =$request->data['content'];  // mở ra test ở màn hình chính
        $mess->save();
        broadcast(new Message( $mess,$mess->receiver_user_id))->toOthers();
        return $mess;
    }
}
