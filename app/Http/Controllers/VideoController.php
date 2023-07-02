<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Jobs\ProcessSavingVideo;
use App\Models\Video;
use URL;
use App\Events\PusherBroadcast;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;

class VideoController extends Controller
{
    public function getData(Request $request){
        $offset = (int)$request->input('offset');
        $limit = (int)$request->input('limit');

        $videos = Video::where('status','1')->limit($limit)->offset($offset)->get();

        return response()->json($videos);
    }

    public function remove(Request $request, $id){
        $video = Video::find($id);
        $video->status = 0;
        $video->save();

        if(Storage::exists('public/'.$video->filename)){
            Storage::delete('public/'.$video->filename);
        }

        if(Storage::exists('public/720p_'.$video->filename)){
            Storage::delete('public/720p_'.$video->filename);
        }

        if(Storage::exists('public/1080p_'.$video->filename)){
            Storage::delete('public/1080p_'.$video->filename);
        }        
    }

    public function save(Request $request){
        $request->validate([
            "file"=>"required | mimes:mp4,x-flv,x-mpegURL,MP2T,3gpp,quicktime,x-msvideo,x-ms-wmv |max:10240"
        ]);
        
        $file = $request->file('file');        
        $path = $file->storeAs('',$file->getClientOriginalName(), ['disk' => 'public']);
        $fullpath = storage_path().'/app/public/';

        $getID3 = new \getID3;
        $info = $getID3->analyze($fullpath.$file->getClientOriginalName());

        $duration = $info['playtime_seconds'];
        if($duration > 15){
            return response('',403);
        }

        dispatch(new ProcessSavingVideo($file->getClientOriginalName(),$fullpath));
    }
}
