<?php

namespace App\Http\Resources;
use App\User;
use Illuminate\Http\Resources\Json\JsonResource;
use  Illuminate\Support\Facades\Auth;
class message extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if(Auth::user()->id!=$this->fromID){
            $user = User::find($this->fromID);
        }else{
            $user = User::find($this->toID);
        }      
        return [
            'id' => $this->id,
            'roomID' =>$this->roomID,
            'fromID' => $this->fromID,
            'toID' => $this->toID,
            'fromuserfullname' => $user->lastName." ".$user->firstName,
            'fromuserAvt' => $user->img_avt,
            'message' => $this->message,
            'message_Image' =>$this->message_Image,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
