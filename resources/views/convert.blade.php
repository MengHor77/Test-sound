        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8" />
            <title>PDF to Audio Converter </title>
            <style>
                body { font-family: Arial, sans-serif; max-width: 600px; margin: 30px auto; }
                .error { color: red; }
                .success { color: green; }
                audio { margin-top: 10px; width: 100%; }
            </style>
        </head>
        <body>
            <h1>PDF to Audio Converter</h1>

            @if($errors->any())
                <div class="error">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(session('success'))
                <div class="success">{{ session('success') }}</div>
                <audio controls>
                    <source src="{{ asset('audios/' . session('audio')) }}" type="audio/mpeg" />
                    Your browser does not support the audio element.
                </audio>
                <p>
                    <a href="{{ route('convert.download', session('audio')) }}">Download Audio</a>
                </p>
            @endif

            <form method="POST" action="{{ route('convert.process') }}" enctype="multipart/form-data">
                @csrf
                <label for="file">Select PDF File:</label><br />
                <input type="file" id="file" name="file" accept="application/pdf" required /><br /><br />
                <button type="submit">Convert to Audio</button>
            </form>
        </body>
        </html>
