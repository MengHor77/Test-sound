<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class AudioController extends Controller
{
    public function index()
    {
        return view('convert');
    }

    public function convert(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf|max:20480', // max 20MB
        ]);

        $uploadedFile = $request->file('file');

        // Create directories if not exist
        if (!File::exists(public_path('uploads'))) {
            File::makeDirectory(public_path('uploads'), 0755, true);
        }
        if (!File::exists(public_path('audios'))) {
            File::makeDirectory(public_path('audios'), 0755, true);
        }

        // Save uploaded PDF
        $pdfFilename = time() . '_' . preg_replace('/\s+/', '_', $uploadedFile->getClientOriginalName());
        $uploadedFile->move(public_path('uploads'), $pdfFilename);

        $pdfPath = public_path('uploads/' . $pdfFilename);
        $audioFilename = pathinfo($pdfFilename, PATHINFO_FILENAME) . '.mp3';
        $audioPath = public_path('audios/' . $audioFilename);

        // Build the Python command
            $pythonPath = 'C:\\Users\\USER\\AppData\\Local\\Programs\\Python\\Python312\\python.EXE'; 
            $scriptPath = base_path('python/text_to_audio.py');

        $process = new Process([
            $pythonPath,
            $scriptPath,
            $pdfPath,
            $audioPath,
        ]);

        $process->setTimeout(60); // 1 minute max timeout

        try {
            $process->mustRun();
        } catch (ProcessFailedException $exception) {
            // Clean up uploaded file if conversion fails
            if (File::exists($pdfPath)) {
                File::delete($pdfPath);
            }
            return back()->withErrors(['error' => 'Conversion failed: ' . $exception->getMessage()]);
        }

        return back()->with('success', 'Audio created successfully!')->with('audio', $audioFilename);
    }

public function download($filename)
{
    $filePath = public_path('audios/' . $filename);

    if (!File::exists($filePath)) {
        abort(404, 'Audio file not found.');
    }

    return response()->download($filePath, $filename, [
        'Content-Type' => 'audio/mpeg',
    ]);
}

}
