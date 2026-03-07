<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Services\CloudFrontService;

class FileManagerController extends Controller
{
    private $disk = 's3';

    public function index(Request $request)
    {
        $path   = trim($request->get('path',''),'/');
        $search = $request->get('search','');

        $files = Storage::disk($this->disk)->files($path);
        $dirs  = Storage::disk($this->disk)->directories($path);

        $items = collect($dirs)->map(function($dir){
            return [
                'name' => basename($dir),
                'path' => $dir,
                'is_dir' => true,
                'size' => 0,
                'modified' => 0
            ];
        });

        $items = $items->merge(
            collect($files)->map(function($file){
                return [
                    'name' => basename($file),
                    'path' => $file,
                    'is_dir' => false,
                    'size' => Storage::disk('s3')->size($file),
                    'modified' => Storage::disk('s3')->lastModified($file)
                ];
            })
        );

        return view('index',[
            'items'=>$items,
            'path'=>$path,
            'search'=>$search,
            'cdnBase'=>config('files.cdn_base_url')
        ]);
    }

    public function createFolder(Request $request)
    {
        $path = trim($request->input('path',''),'/');
        $folder = preg_replace('/[^a-zA-Z0-9._-]/','_',$request->input('newfolder'));

        if(!$folder){
            return back();
        }

        $dir = $path ? $path.'/'.$folder : $folder;

        // No S3 não precisamos criar pasta física
        // Apenas redirecionamos para o prefixo

        return redirect()->route('files.index', ['path' => $dir]);
    }        

    public function local(Request $request)
    {
        $path = ltrim($request->input('path'),'/');

        if (!Storage::disk($this->disk)->exists($path)) {
            abort(404);
        }

        $url = Storage::disk($this->disk)->temporaryUrl(
            $path,
            now()->addMinutes(5)
        );

        return redirect($url);
    }    

    
    public function upload(Request $request)
    {
        $path = trim($request->input('path',''),'/');

        $paths = [];
        $cloudfront = app(CloudFrontService::class);

        if ($request->hasFile('files')) {

            foreach ($request->file('files') as $file) {

                $name = preg_replace('/[^a-zA-Z0-9._-]/','_',$file->getClientOriginalName());

                if (!$name || $name[0]=='.') continue;

                $target = $path ? $path.'/'.$name : $name;

                Storage::disk($this->disk)->putFileAs(
                    $path,
                    $file,
                    $name
                );

                $paths[] = '/'.$target;
            }
        }

        if($paths){
            $cloudfront->invalidate($paths);
        }

        return redirect()->route('files.index',['path'=>$path]);
    }
    
    public function invalidate(Request $request)
    {
        $path = trim($request->input('path'),'/');
        $file = basename($request->input('file'));

        $relative = '/'.$path.'/'.$file;

        app(CloudFrontService::class)->invalidate($relative);

        return response()->json([
            'status'=>'ok',
            'file'=>$relative
        ]);
    }

    public function invalidateFolder(Request $request)
    {
        $path = trim($request->input('path'),'/');

        $relative = '/'.$path.'/*';

        app(CloudFrontService::class)->invalidate($relative);

        return response()->json([
            'status'=>'ok',
            'folder'=>$relative
        ]);
    }
        
    public function delete(Request $request)
    {
        $path = trim($request->input('path',''),'/');
        $file = basename($request->input('file'));

        $target = $path ? $path.'/'.$file : $file;

        if (Storage::disk($this->disk)->exists($target)) {

            Storage::disk($this->disk)->delete($target);

            $relative = '/'.$target;

            logger('CloudFront invalidation delete', [$relative]);

            app(CloudFrontService::class)->invalidate($relative);
        }

        return redirect()->route('files.index',['path'=>$path]);
    }        

    public function deleteFolder(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        $path = trim($request->input('path'),'/');

        if(!$path){
            return back();
        }

        Storage::disk($this->disk)->deleteDirectory($path);

        return redirect()->route('files.index', [
            'path' => dirname($path) === '.' ? '' : dirname($path)
        ]);
    }    

    public function folderInfo(Request $request)
    {
        $path = trim($request->input('path'),'/');

        $files = Storage::disk($this->disk)->files($path);
        $dirs  = Storage::disk($this->disk)->directories($path);

        return response()->json([
            'files' => count($files),
            'dirs'  => count($dirs)
        ]);
    }
}