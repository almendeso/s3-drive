<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

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
            }

            return redirect()->route('files.index', compact('path'));
        }

        $items = collect(File::files($currentDir))
            ->merge(File::directories($currentDir))
            ->sortBy(fn($i) => basename($i));

        return view('index', [
            'items'     => $items,
            'path'      => $path,
            'search'    => $search,
            'cdnBase'   => config('files.cdn_base_url'),
        ]);
    }

    public function delete(Request $request)
    {
        $path = $request->path ?? '';
        $file = basename($request->file);

        $currentDir = realpath($this->baseDir.'/'.$path);
        $target = realpath($currentDir.'/'.$file);

        if ($target && Str::startsWith($target, $this->baseDir) && is_file($target)) {
            unlink($target);
        }

        return redirect()->route('index', compact('path'));
    }

    private function isBlocked(string $filename): bool
    {
        return in_array(
            strtolower(pathinfo($filename, PATHINFO_EXTENSION)),
            config('files.blocked_extensions')
        );
    }
}
