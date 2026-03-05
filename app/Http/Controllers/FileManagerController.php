<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use App\Services\CloudFrontService;

class FileManagerController extends Controller
{
    private string $baseDir;

    public function __construct()
    {
        $this->baseDir = storage_path('app/uploads');
    }

    public function index(Request $request)
    {
        $path   = $request->get('path', '');
        $search = $request->get('search', '');

        $currentDir = realpath($this->baseDir . '/' . $path);

        if (!$currentDir || !Str::startsWith($currentDir, $this->baseDir)) {
            $currentDir = $this->baseDir;
            $path = '';
        }

        /* NOVA PASTA */
        if ($request->filled('newfolder')) {
            $folder = preg_replace('/[^a-zA-Z0-9._-]/', '_', $request->newfolder);
            if ($folder && $folder[0] !== '.') {
                File::makeDirectory($currentDir.'/'.$folder, 0755, true, true);
            }
            return redirect()->route('files.index', compact('path'));
        }

        /* UPLOAD */
        if ($request->hasFile('files')) {

            $paths = [];
            $cloudfront = app(CloudFrontService::class);

            foreach ($request->file('files') as $file) {
                $name = preg_replace('/[^a-zA-Z0-9._-]/', '_', $file->getClientOriginalName());

                if (!$name || $name[0] === '.' || $this->isBlocked($name)) {
                    continue;
                }

                $target = $currentDir.'/'.$name;
                $i = 1;

                while (file_exists($target)) {
                    $target = $currentDir.'/'.pathinfo($name, PATHINFO_FILENAME)
                        ."_$i.".pathinfo($name, PATHINFO_EXTENSION);
                    $i++;
                }

                $file->move(dirname($target), basename($target));

                $prefixes = [
                    '/usr/local/s3-sites/html/storage/app/uploads',
                    '/var/www/html/s3-drive/storage/app/uploads'
                ];

                $relative = str_replace($prefixes, '', $target);

                $relative = '/'.ltrim($relative,'/');
                $relative = preg_replace('#/+#','/',$relative);

                $paths[] = $relative;
            }

            if ($paths) {
                logger('CloudFront invalidation', $paths);
                $cloudfront->invalidate($paths);
            }

            return redirect()->route('files.index', compact('path'));
        }                     
        
        $sort = $request->get('sort', 'name');
        $dir  = $request->get('dir', 'asc');

        $items = collect(File::files($currentDir))
            ->merge(File::directories($currentDir))
            ->map(function ($item) {
                return [
                    'name'     => basename($item),
                    'path'     => $item,
                    'is_dir'   => is_dir($item),
                    'size'     => is_dir($item) ? 0 : filesize($item),
                    'modified' => filemtime($item),
                ];
            })
            ->sortBy(function ($item) use ($sort) {
                return match ($sort) {
                    'size'     => $item['size'],
                    'modified' => $item['modified'],
                    default    => strtolower($item['name']),
                };
            })
            ->sortByDesc(fn ($item) => $item['is_dir']) // 📁 pastas primeiro
            ->when($dir === 'desc', fn ($c) => $c->reverse())
            ->values();         

        return view('index', [
            'items'     => $items,
            'path'      => $path,
            'search'    => $search,
            'cdnBase'   => config('files.cdn_base_url'),
        ]);
    }

    public function local(Request $request)
    {
        $path = $request->get('path');

        if (!$path) {
            abort(404);
        }

        $fullPath = realpath($this->baseDir.'/'.$path);

        if (!$fullPath || !Str::startsWith($fullPath, $this->baseDir) || !is_file($fullPath)) {
            abort(403);
        }

        return response()->file($fullPath);
    }

    public function invalidate(Request $request)
    {
        $path = $request->input('path');
        $file = $request->input('file');

        $currentDir = realpath($this->baseDir.'/'.$path);
        $target = realpath($currentDir.'/'.$file);

        if (!$target || !Str::startsWith($target, $this->baseDir)) {
            return response()->json(['error' => 'Invalid path'], 403);
        }

        $prefixes = [
            '/usr/local/s3-sites/html/storage/app/uploads',
            '/var/www/html/s3-drive/storage/app/uploads'
        ];

        $relative = str_replace($prefixes, '', $target);
        $relative = '/'.ltrim($relative,'/');
        $relative = preg_replace('#/+#','/',$relative);

        app(CloudFrontService::class)->invalidate($relative);

        return response()->json([
            'status' => 'ok',
            'file'   => $relative
        ]);
    }    
    
    public function invalidateFolder(Request $request)
    {
        $path = $request->input('path') ?? '';

        $relative = '/'.trim($path,'/').'/*';
        $relative = preg_replace('#/+#','/',$relative);

        logger('CloudFront invalidation folder', [$relative]);

        app(CloudFrontService::class)->invalidate($relative);

        return response()->json([
            'status' => 'ok',
            'folder' => $relative
        ]);
    }

    public function delete(Request $request)
    {
        $path = $request->path ?? '';
        $file = basename($request->file);

        $currentDir = realpath($this->baseDir.'/'.$path);
        $target = realpath($currentDir.'/'.$file);

        if ($target && Str::startsWith($target, $this->baseDir) && is_file($target)) {

            $prefixes = [
                '/usr/local/s3-sites/html/storage/app/uploads',
                '/var/www/html/s3-drive/storage/app/uploads'
            ];

            $relative = str_replace($prefixes, '', $target);
            $relative = '/'.ltrim($relative,'/');
            $relative = preg_replace('#/+#','/',$relative);

            unlink($target);

            logger('CloudFront invalidation delete', [$relative]);

            app(CloudFrontService::class)->invalidate($relative);
        }

        return redirect()->route('files.index', compact('path'));
    }

    private function isBlocked(string $filename): bool
    {
        return in_array(
            strtolower(pathinfo($filename, PATHINFO_EXTENSION)),
            config('files.blocked_extensions')
        );
    }
}
