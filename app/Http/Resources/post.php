<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\user as userResource;
use App\User;
use App\like;
use  Illuminate\Support\Facades\Auth;
use App\comment;
use App\photos;
class post extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $user=User::find($this->userID);
        $like_qty=like::where('postID',$this->postID)->count();
        $cmt_qty=comment::where('postID',$this->postID)->count();
        $like=like::where('postID',$this->postID)->where('like_userID',Auth::user()->id)->count();
        $post_images = photos::where('postID',$this->postID)->get();
        $avt = photos::where('userID',$this->userID)->where('type',1)->orderByDesc('updated_at')->pluck('image')->first();
        return [
            'postID' => $this->postID,
            'userID' => $this->userID,
            'post_Content' => $this->post_Content,
            'post_Images' => $post_images,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'userfullname' => $user->lastName." ".$user->firstName,
            'userAvt' => $avt,
            'like_qty' => $like_qty,
            'cmt_qty' => $cmt_qty,
            'liked' => $like
        ];
    }
}
