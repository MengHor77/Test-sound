import sys
import fitz  # PyMuPDF
import pyttsx3
import os

def pdf_to_text(pdf_path):
    try:
        doc = fitz.open(pdf_path)
        text = ""
        for page in doc:
            text += page.get_text()
        return text.strip()
    except Exception as e:
        return f"Error reading PDF: {str(e)}"

def text_to_speech(text, output_audio_path):
    try:
        engine = pyttsx3.init()
        engine.save_to_file(text, output_audio_path)
        engine.runAndWait()
        return True
    except Exception as e:
        print(f"Text-to-speech error: {e}")
        return False

if __name__ == "__main__":
    if len(sys.argv) < 3:
        print("Usage: python text_to_audio.py <pdf_path> <output_audio_path>")
        sys.exit(1)

    pdf_path = sys.argv[1]
    output_audio_path = sys.argv[2]

    if not os.path.isfile(pdf_path):
        print(f"File not found: {pdf_path}")
        sys.exit(1)

    text = pdf_to_text(pdf_path)
    if not text:
        print("No text found in the PDF or failed to read.")
        sys.exit(1)

    success = text_to_speech(text, output_audio_path)
    if success:
        print(f"Audio saved successfully to {output_audio_path}")
    else:
        print("Failed to convert text to audio.")
