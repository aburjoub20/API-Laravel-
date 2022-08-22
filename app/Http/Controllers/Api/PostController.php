<?php

namespace App\Http\Controllers\Api;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    use ApiResponseTrait;

    public function index(){
        // $posts = Post::get(); // to get Data 
        $posts = PostResource::Collection(Post::get()); // I Used Collection  When I Return More Than 1 Item 
        return $this->apiResponse($posts,'ok',200);
    }

    public function show($id){

        // $post =  new PostResource(Post::find($id)); //I used New when i wann Return just one Item 
$post = Post::find($id);
        if($post ){
            return $this->apiResponse(new PostResource($post),'ok',200); // i use This Resurce To Return Error Msg(Exception Error)/
        }

        return $this->apiResponse(null,'The Post Not Found',404);

    }
    public function store(Request $request){
        $validator = Validator::make($request->all(), [ // Validatiom 
            'title' => 'required|max:255',
            'body' => 'required',
        ]);
 
        if ($validator->fails()) {
            return $this->apiResponse(null,$validator->errors(),401); // Return Error msg on API 

        }
 
        $post = Post::create( $request->all()); // Get all Requst 
        if($post){
            return $this->apiResponse(new PostResource($post),'ok',200);
        }
        return $this->apiResponse(null,'The Post Not Found',401);

    }
    public function update(Request $request , $id){
        $validator = Validator::make($request->all(), [ // Validatiom 
            'title' => 'required|max:255',
            'body' => 'required',
        ]); 
        if ($validator->fails()) {
            return $this->apiResponse(null,$validator->errors(),401); // Return Error msg on API 
        }
        $post = Post::find($id); // Get Item 
        if(!$post){
            return $this->apiResponse(null,'The Post Not Found',404); // check if Item was Empty 
        }

        $post->update($request->all()); //Update 

        if($post){
            return $this->apiResponse(new PostResource($post),'ok',200); //Chack If Updated
        }

    }
    public function destroy($id){
 $post = Post::find($id);

 if(!$post){
            return $this->apiResponse(null,'The Post Not Found',404); // check if Item was Empty 
        }

        $post->delete($id);
        if($post){
            return $this->apiResponse(null,'Deleted Successfully',201); //Chack If Updated
        }

    }
}
