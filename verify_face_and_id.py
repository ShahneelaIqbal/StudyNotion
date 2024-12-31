import cv2
import pytesseract
import json
import sys
from pathlib import Path

# Specify the path to Tesseract executable (adjust path if needed)
pytesseract.pytesseract.tesseract_cmd = r'C:\Program Files\Tesseract-OCR\tesseract.exe'

def verify_face_and_id(photo_path):
    # Load the image
    image = cv2.imread(photo_path)

    # Initialize the face detector
    face_cascade = cv2.CascadeClassifier(cv2.data.haarcascades + 'haarcascade_frontalface_default.xml')

    # Convert to grayscale for face detection
    gray = cv2.cvtColor(image, cv2.COLOR_BGR2GRAY)

    # Detect faces
    faces = face_cascade.detectMultiScale(gray, scaleFactor=1.1, minNeighbors=5, minSize=(30, 30))
    
    # Log OCR output and face detection result for debugging
    print("OCR Output: ", pytesseract.image_to_string(image))  # OCR output for debugging
    print("Face Detection Result: ", len(faces))  # Log number of faces detected for debugging

    if len(faces) == 0:
        return {"status": False, "message": "No face detected in the image."}

    # OCR for ID card text extraction
    extracted_text = pytesseract.image_to_string(image)

    # Check for presence of key ID patterns
    if "ID" not in extracted_text and len(extracted_text) < 10:  # Simplified pattern check
        return {"status": False, "message": "ID card verification failed."}

    # If both face and text are verified
    return {"status": True, "message": "Face and ID verified successfully."}

if __name__ == "__main__":
    file_path = sys.argv[1]

    # Check if file exists
    if not Path(file_path).is_file():
        print(json.dumps({"status": False, "message": "File not found."}))
        sys.exit(1)

    # Perform verification
    result = verify_face_and_id(file_path)

    # Output result as JSON
    print(json.dumps(result))
