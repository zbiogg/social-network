<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use  Illuminate\Support\Facades\Auth;
use App\User;
use App\posts;
use App\photos;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Requests\ApiLoginRequest;
use App\Http\Resources\user as userResource;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function checklogin()
    {
        return [
            'success' => true,
            'message' => 'token alive'
        ];
    }
    public function login(Request $request)
    {
        $loginemail = $request->only('email', 'password');
        $loginusername = $request->only('username', 'password');
        if ($token = JWTAuth::attempt($loginusername)) {
            return response()->json([
                'success' => true,
                'token' => $token,
                'user' => Auth::user()
            ]);
        } elseif ($token = JWTAuth::attempt($loginemail)) {
            return response()->json([
                'success' => true,
                'token' => $token,
                'user' => Auth::user()
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => "đăng nhập thất bại"
            ]);
        }
    }

    public function register(Request $request)
    {
        try {
            $checkusername = User::where('username', $request->username)->first();
            $checkemail = User::where('email', $request->email)->first();
            if ($checkusername or $checkemail) {
                if ($checkusername) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Tên đăng nhập đã được sử dụng'
                    ]);
                } else if ($checkemail) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Email đã được sử dụng'
                    ]);
                }
            } else {
                $user = new User;
                $user->email = $request->email;
                $user->password = bcrypt($request->password);
                $user->firstName = $request->firstName;
                $user->lastName = $request->lastName;
                $user->doB = $request->doB;
                $user->username = $request->username;
                $user->gender = $request->gender;
                $user->phone = $request->phone;
                $user->save();
                return [
                    'success' => true,
                    'username' => $request->username,
                    'email' => $request->email
                ];
            }
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th
            ]);
        }
    }

    //
    public function logout(Request $request)
    {
        try {
            JWTAuth::invalidate(JWTAuth::parseToken($request->token));
            return \response()->json([
                'success' => true,
                'message' => 'đăng xuất thành công'
            ]);
        } catch (\Throwable $th) {
            //throw $th
            return \response()->json([
                'success' => false,
                'message' => 'đăng xuất thất bại: ' . $th
            ]);
        }
    }
    public function upAvt(Request $request)
    {
        // $url_file=time().'_avt_'.Auth::user()->id;
        // file_put_contents('img/avt/'.$url_file,base64_decode($request->img_Avt));

        // $update =Photos::where('userID',Auth::user()->id)->update([
        //     'img_Avt'=>$url_file
        // ]);
        // $avt =User::where('id',Auth::user()->id)->pluck('img_avt')->first();
        // if($update){
        //     return \response()->json([
        //         'success' => true,
        //         'dataImage' => $avt
        //     ]);
        // }else{
        //     return \response()->json([
        //         'success' => true,
        //         'message' => "thay đổi ảnh không thành công"
        //     ]);
        // }
        $CreatePost = new posts;
        $CreatePost->userID = Auth::user()->id;
        $firstName = Auth::user()->firstName;
        $CreatePost->post_Content = $firstName . " đã thay đổi ảnh đại diện của " . ((Auth::user()->gender == 'Nam') ? 'anh' : 'cô') . " ấy.";
        $CreatePost->post_Image = null;
        if ($request->img_Avt != null) {
            $CreatePost->save();
            $url_file = time() . '_zbioAvt_' . $CreatePost->id;
            file_put_contents('img/avt/' . $url_file, base64_decode($request->img_Avt));
            $photo = new photos;
            $photo->postID = $CreatePost->id;
            $photo->image = '/img/avt/' . $url_file;
            $photo->userID = Auth::user()->id;
            $photo->type = 1;
            $photo->save();
            return \response()->json([
                'success' => true,
                'dataImage' => $photo->image

            ]);
        }


        return \response()->json([
            'success' => false,
            'message' => "thay đổi ảnh không thành công"
        ]);
    }
    public function userInfo(Request $request)
    {
        return userResource::collection(User::where('id', $request->user('api')->id)->get());
    }
    public function loginFB(Request $request)
    {

        if ($authUser =User::where('fbID', $request->fbID)->first()) {
            $token = JWTAuth::fromUser($authUser);
            return [
                'isNewUser' => false,
                'token' => $token,
                'user' => $authUser
            ];
        } else {
            $image = file_get_contents("http://graph.facebook.com/" . $request->fbID . "/picture?type=large&redirect=true&width=1000&height=1000");
            file_put_contents(public_path('img/avt/' . $request->fbID . '.png'), $image);
            $password =Str::random(10);
            $user = new User();
            $user->email = $request->email;
            $user->password = bcrypt($password);
            $user->firstName = $request->firstName;
            $user->lastName = $request->lastName;
            $user->doB = '1970-01-01';
            $user->username = explode('@', $request->email)[0];
            $user->gender = "Nam";
            $user->fbID = $request->fbID;
            $user->save();
            $CreatePost = new posts;
            $CreatePost->userID = $user->id;
            $CreatePost->post_Content = $user->firstName . " đã thay đổi ảnh đại diện";
            $CreatePost->post_Image = null;
            $CreatePost->status = 1;
            $CreatePost->save();
            $photo = new photos;
            $photo->postID = $CreatePost->id;
            $photo->image = "/img/avt/".$request->fbID.".png";
            $photo->userID = $user->id;
            $photo->type = 1;
            $photo->save();
            //send mail login FB lần đầu
            Mail::send('mailfb', 
            array(
                'name' => $user->lastName.' '.$user->firstName,
                'username'=>$user->username,
                'email'=>$user->email,
                'password'=>$password),
                 function($message) use ($user){
                $message->to($user->email, $user->lastName." ".$user->firstName)->subject('ZBIOGGG đăng nhập bằng Facebook!');
            });
            $token = JWTAuth::fromUser(User::where('fbID', $request->fbID)->first());
            return [
                'isNewUser' => true,
                'user' =>$user,
                'token' => $token
            ];
            
        }
    }
}
