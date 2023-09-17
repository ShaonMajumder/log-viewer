<?php

namespace Shaon\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Routing\Controller;

class LogViewerController extends Controller
{
    public function index(){
        $log_directory = storage_path('logs');
        return view('log-viewer::log', [ 'log_directory' => $log_directory]);
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

    public function searchLogFiles(Request $request)
    {
        $query = $request->input('term'); // The search query
        $results = [];

        // Perform your file search logic here, and populate the $results array
        $allFiles = File::allFiles(storage_path('logs'));
        // dd($allFiles);

        foreach ($allFiles as $file) {
            $fileName = $file->getRelativePathname();
            if (str_contains($fileName, $query)) {
                $results[] = ['id' => $file, 'text' => $fileName];
            }
        }

        return response()->json($results);
    }


}
