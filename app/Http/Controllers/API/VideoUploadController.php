<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\VideoUpload;

use Storage;

class VideoUploadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $videos = VideoUpload::all();

        return response()->json([
            'videos' => $videos
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // return $request->url_path;
        $file_name =  time().basename($request->url_path);
        $content = file_get_contents($request->url_path);
        // return 'img/videos/' + $file_name;
        $path = 'img/videos/';
        if (!file_exists($path)) {
            mkdir($path, 0755, true);
        }
        file_put_contents($path.$file_name, $content);

        VideoUpload::create([
            'file_name' => $file_name,
            'path'      => $path
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $video = VideoUpload::find($id);
        
        unlink(public_path($video->path . $video->file_name));

        $video->delete();
    }
}
