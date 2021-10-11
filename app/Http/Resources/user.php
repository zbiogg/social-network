<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use  Illuminate\Support\Facades\Auth;
use App\relationship;
use App\photos;
class user extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $user_id_compare=$this->id;
        $rl1 = relationship::
        where('status',1)
        ->where('userID_2',Auth::user()->id)->orderBy('userID_1','asc')->pluck('userID_1');
        $rl2 = relationship::
        where('status',1)
        ->where('userID_1',Auth::user()->id)->orderBy('userID_2','asc')->pluck('userID_2');
        $rl3 = relationship::
        where('status',1)
        ->where('userID_2',$user_id_compare)->orderBy('userID_1','asc')->pluck('userID_1');
        $rl4 = relationship::
        where('status',1)
        ->where('userID_1',$user_id_compare)->orderBy('userID_2','asc')->pluck('userID_2');
        $abc = array_merge($rl1->toArray(),$rl2->toArray());
        $def =  array_merge($rl3->toArray(),$rl4->toArray());

        $banchung = array_intersect($abc, $def);
        if(Auth::user()->id<$this->id){
            $checkfriend = relationship::where('userID_1',Auth::user()->id)->where('userID_2',$this->id)->first();
        }else{
            $checkfriend = relationship::where('userID_2',Auth::user()->id)->where('userID_1',$this->id)->first();
        }
        if($checkfriend){
           $status_friend = $checkfriend->status ;
           $action_userID = $checkfriend->action_userID;
        }else{
            $status_friend =-1;
            $action_userID=-1;
        }
       
        $avt = photos::where('userID',$this->id)->where('type',1)->orderByDesc('updated_at')->pluck('image')->first();
        $cover = photos::where('userID',$this->id)->where('type',2)->orderByDesc('updated_at')->pluck('image')->first();
        return [
            'id' =>$this->id,
            'username'=> $this->username,
            'email' => $this->email,
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'gender' => $this->gender,
            'doB' => $this->doB,
            'phone' => $this->phone,
            'city' => $this->city,
            'about' =>$this->about,
            'img_avt'=>$avt,
            'img_cover' => $cover,
            'fbID' => $this->fbID,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'status' => $this->status,
            'auth' => $this->auth,
            'mutual_friends'=>sizeof($banchung),
            'status_friend' =>$status_friend,
            'action_userID' =>$action_userID
        ];
    }
}
