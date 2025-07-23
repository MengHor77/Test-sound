<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TTSController extends Controller
{
    public function index()
    {
        return view('tts');
    }

    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:txt,pdf|max:2048',
        ]);

        $file = $request->file('file');
        $path = $file->storeAs('uploads', $file->getClientOriginalName(), 'local');

        $fullPath = storage_path('app/' . $path);
        $text = '';

        if ($file->getClientOriginalExtension() === 'pdf') {
            // Parse PDF using shell command (Python is safer for PDF)
            $text = shell_exec("python3 text_to_audio.py " . escapeshellarg($fullPath));
        } else {
            $text = file_get_contents($fullPath);
            shell_exec("python3 text_to_audio.py " . escapeshellarg($text));
        }

        return redirect('/')->with('success', 'Audio generated!')->with('audio', 'audio/output.mp3');
    }
}
