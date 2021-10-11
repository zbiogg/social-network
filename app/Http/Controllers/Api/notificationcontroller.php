<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\notification;
use  Illuminate\Support\Facades\Auth;
use App\User;
use App\Http\Resources\notification as notiResource;
class notificationcontroller extends Controller
{
    public function getNotification(){
      return  [
          'success' => true,
         'notifications'=> notiResource::collection(notification::where('receiverID',Auth::user()->id)->orderBy('created_at','desc')->paginate(10))
      ];
    }
    public function getNotiUnReader(){
      return  [
        'success' => true,
       'qty'=> notification::where('receiverID',Auth::user()->id)->where('status',0)->count()
    ];
    }
    public function showNoti(Request $request){
      return[
        'success' => true,
        'noti' => notiResource::collection(notification::where('id',$request->notiID)->get())
      ];
    } 

    public function readNoti(Request $request){
          $noti= notification::where('id',$request->notiID)->update(['status'=>1]);
          return [
            'success' =>true
          ];
    }
}
