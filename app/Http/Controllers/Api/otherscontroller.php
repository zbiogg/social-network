<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\photos;
use App\User;
use App\posts;
use App\Http\Resources\post as postResource;
use App\Http\Resources\user as userResource;
class otherscontroller extends Controller
{
    public function getUserAvts($id){
        $photos =photos::where('userID',$id)->where('type',1)->orderByDesc('id')->get()->pluck('image');
        return $photos;
    }
    public function getUserData($id){
        $photos =photos::where('userID',$id)->orderByDesc('id')->paginate(9)->pluck('image');
        $arrphotos = [
            "https://picsum.photos/1366/768",
            "https://picsum.photos/1366/767",
            "https://picsum.photos/1366/766",
            "https://picsum.photos/1366/765",
            "https://picsum.photos/1366/764",
            "https://picsum.photos/1366/763",
            "https://picsum.photos/1366/762",
            "https://picsum.photos/1366/761",
            "https://picsum.photos/1366/760",
            
        ];
        foreach($photos as $key => $p){
            $arrphotos[$key]=$p;
        }
        return [
            'success' => true,
            'user' => userResource::collection(User::where('id',$id)->get()),
            'photos' =>$arrphotos,
            'lengphoto' =>count($photos),
            'data' => postResource::collection(posts::where('userID',$id)->where('status',0)->orderBy('postID','desc')->paginate(20)->sortByDesc('postID'))];
    }
}
