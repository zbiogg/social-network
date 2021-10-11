<?php

namespace App\Http\Controllers\Api;

use App\posts;
use App\photos;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\post as postResource;
use App\Http\Resources\user as userResource;
use Illuminate\Support\Facades\Auth;

class postcontroller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index( Request $request)
    {
        $arrPosts =explode(",",$request->arrPosts);
        return postResource::collection(posts::where('status',0)->whereNotIn('postID',$arrPosts)->orderBy('postID','desc')->limit(10)->get()->sortByDesc('postID'));
        
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     *
    public function store(Request $request)
    {
    
         $CreatePost= new posts;
        $CreatePost->userID=Auth::user()->id;
        $CreatePost->post_Content=$request->post_Content;
        if($request->post_Image!=''){
            $url_file=time().'_mobile';
            file_put_contents('img/posts'.$url_file,base64_decode($request->post_Image));
            $CreatePost->post_Image=$url_file;
        }
        if($request->post_Content!=''||$request->post_Image!=''){
            $CreatePost->save();
            
        }
        return response()->json([
            'success' => true,
            'message' => 'oke',
             postResource::collection(posts::where('postID',$CreatePost->post_Image)->get())
        ]);
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\posts  $posts
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return [
            'success' => true,
             'data' =>postResource::collection(posts::where('postID',$id)->get())
            ];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\posts  $posts
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, posts $posts,$id)
    {
        return $posts->where('postID',$id)->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\posts  $posts
     * @return \Illuminate\Http\Response
     */
    public function destroy(posts $posts,$id)
    {
        $posts->where('postID',$id)->delete();
    }

    public function myPosts(){
        return [
            'success' => true,
            'data' => postResource::collection(posts::where('userID',Auth::user()->id)->paginate(10)->sortBy('postID'))];
    }


    
    public function addPost(Request $request)
    {
        $CreatePost= new posts;
        $CreatePost->userID=Auth::user()->id;
        $CreatePost->post_Content=$request->post_Content;
        $CreatePost->post_Image = null;
        if($request->post_Content!=''||count($request->post_Images)!=0){
            $CreatePost->save();
        }
        if(count($request->post_Images)!=0){

            foreach($request->post_Images as $key=>$img){
                $url_file=time().'_zbioPost_'.$CreatePost->id.'_'.$key;
                file_put_contents('img/posts/'.$url_file,base64_decode($img));
                $CreatePost->post_Images=$url_file;
                $photo = new photos;
                $photo->postID= $CreatePost->id;
                $photo->image = '/img/posts/'.$url_file;
                $photo->userID=Auth::user()->id;
                $photo->save();
            }  
        }
        
        return response()->json([
            'success' => true,
            'message' => 'oke',
             'data' =>postResource::collection(posts::where('postID',$CreatePost->id)->get())
        ]);
   
       
    }

    public function editPost(Request $request){
        if($request->post_Image!=''){
            $url_file=time().'_mobile';
            file_put_contents('img/posts/'.$url_file,base64_decode($request->post_Image));
    
        }
        $post= posts::where('postID',$request->postID)->update([
            'post_Content' => $request->post_Content,
            'post_Image' => $url_file
        ]);
        
        return [
            'success' => true,
            'post' => postResource::collection(posts::where('postID',$request->postID)->get())
        ];
    }

    public function deletePost(Request $request){
        $post_userID=posts::where('postID',$request->postID)->pluck('userID')->first();
        if(Auth::user()->id==$post_userID){
            posts::where('postID',$request->postID)->delete();
            if(posts::where('postID',$request->postID)->first()){
                return ['success' =>false, 'message' => "Xoá thất bại!"];
            }else{
                return ['success' =>true, 'message' => 'Xóa thành công!'];
            }
        }else{
            return ['success' => false, 'message' => "Người dùng không có quyền xóa bài người khác!"];
        }
        
    }
}
