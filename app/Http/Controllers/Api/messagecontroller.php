<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use  Illuminate\Support\Facades\Auth;
use App\User;
use Illuminate\Support\Facades\DB;
use App\message;
use App\Http\Resources\message as messResource;
class messagecontroller extends Controller
{
    public function getAllMessage(){
     
        $messages = message::where('fromID',Auth::user()->id)
        ->orWhere('toID',Auth::user()->id)
        ->orderBy('created_at','desc')
        ->get()->unique('roomID');
        return [
            'success' =>true,
            'messages' => messResource::collection($messages) 
        ];
    }

    public function getMessage($id){
        $m=message::where(function ($query) use ($id) {
            $query->where('fromID', Auth::user()->id)
                  ->where('toID',$id);
        })->orWhere(function($query) use ($id){
            $query->where('fromID', $id)
                  ->where('toID',Auth::user()->id);
        })->get();
        return [
            "user" => User::find($id),
            "message" =>messResource::collection($m) ];
    }
    public function sendMessage(Request $request){
        $message = new message;
        if(Auth::user()->id<$request->toID){
            $message->roomID=Auth::user()->id."_".$request->toID;
        }else{
            $message->roomID=$request->toID."_".Auth::user()->id;
        }
        $message->fromID=Auth::user()->id;
        $message->toID=$request->toID;
        $message->message = $request->message;
        if($request->message_Image!=''){
            $url_file=time().'_mobile';
            file_put_contents('img/messages/'.$url_file,base64_decode($request->message_Image));
            $message->message_Image=$url_file;
        }
        if($request->message!=''||$request->message_Image!=''){
            $message->save();
            $message_id=$message->id;
            
        }
        return response()->json([
            'success' => true,
             'message' =>messResource::collection(message::where('id',$message->id)->get())
        ]);
    }

    public function getDetailMessage($id){
        $message = message::find($id);
        $fromID = $message->fromID;
        return [
            "success"=> true,
            "from_user" => User::find($fromID) ,
            "mess" => messResource::collection(message::where('id',$id)->get())
        ];
    }

}
