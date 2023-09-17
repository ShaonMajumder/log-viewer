<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class LogViewerController extends Controller
{
    public function index(){
        $logFiles = [];
        $log_directory = storage_path('logs');

        $files = File::allFiles($log_directory);

        foreach ($files as $file) {
            $extension = pathinfo($file, PATHINFO_EXTENSION);

            if ($extension === 'log') {
                $logFiles[] = str_replace($log_directory . '/', '', $file->getPathname());
            }
        }
        return view('log', compact('logFiles'));
    }

    public function getFileContent(Request $request)
    {
        $filePath = $request->get('file');
        $filePath = urldecode($filePath);
        $filePath = storage_path('logs') . '/' . $filePath;

        if (!File::exists($filePath)) {
            return response()->json(['error' => 'File not found.']);
        }

        $fileContent = File::get($filePath);
        $fileContent = nl2br( $fileContent );
        
        return response()->json(['content' => $fileContent]);
    }
}
