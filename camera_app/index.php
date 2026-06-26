<!DOCTYPE html>
<html>
<head>
    <title>Camera Capture System</title>

    <style>
    body {
        font-family: Arial, sans-serif;
        background: linear-gradient(135deg, #f3e8ff, #e9d5ff);
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 40px;
        color: #4c1d95;
    }

    h2 {
        margin-bottom: 20px;
        color: #6b21a8;
        text-align: center;
    }

    .container {
        background: white;
        color: #333;
        padding: 25px;
        border-radius: 15px;
        box-shadow: 0 10px 25px rgba(107, 33, 168, 0.25);
        text-align: center;
        width: 90%;
        max-width: 800px;
    }

    .controls {
        margin-bottom: 15px;
    }

    select {
        padding: 10px;
        border-radius: 8px;
        border: 1px solid #c084fc;
        outline: none;
    }

    select:focus {
        border-color: #7c3aed;
        box-shadow: 0 0 5px #c084fc;
    }

    button {
        padding: 10px 15px;
        border: none;
        border-radius: 8px;
        background: #7c3aed;
        color: white;
        cursor: pointer;
        margin: 5px;
        transition: 0.3s;
    }

    button:hover {
        background: #6b21a8;
        transform: translateY(-1px);
    }

    video {
        border: 4px solid #7c3aed;
        border-radius: 12px;
        width: 700px;
        max-width: 100%;
        box-shadow: 0 5px 15px rgba(107, 33, 168, 0.2);
    }

    #preview {
        margin-top: 15px;
        border: 3px solid #7c3aed;
        border-radius: 10px;
        display: none;
        box-shadow: 0 5px 15px rgba(107, 33, 168, 0.2);
    }

    form {
        margin-top: 10px;
    }
</style>
</head>

<body>

<h2>Camera Capture Feature</h2>

<div class="container">

    <div class="controls">
        <select id="cameraSelect"></select>
        <button onclick="startCamera()">Start Camera</button>
    </div>

    <video id="video" autoplay playsinline></video>

    <br>

    <button onclick="capture()">Capture</button>

    <form method="POST" action="upload.php">
        <input type="hidden" name="image" id="imageData">
        <br>
        <button type="submit">Upload</button>
    </form>

    <img id="preview" width="300">

</div>

<script>
let currentStream = null;
const video = document.getElementById('video');
const cameraSelect = document.getElementById('cameraSelect');

// Load cameras
async function loadCameras() {
    try {
        const tempStream = await navigator.mediaDevices.getUserMedia({ video: true });
        tempStream.getTracks().forEach(track => track.stop());

        const devices = await navigator.mediaDevices.enumerateDevices();
        const videoDevices = devices.filter(device => device.kind === 'videoinput');

        cameraSelect.innerHTML = "";

        videoDevices.forEach((device, index) => {
            const option = document.createElement('option');
            option.value = device.deviceId;
            option.text = device.label || `Camera ${index + 1}`;
            cameraSelect.appendChild(option);
        });

    } catch (error) {
        alert("Camera access denied!");
    }
}

// Start camera
async function startCamera() {
    try {
        if (currentStream) {
            currentStream.getTracks().forEach(track => track.stop());
        }

        const deviceId = cameraSelect.value;

        const stream = await navigator.mediaDevices.getUserMedia({
            video: deviceId ? { deviceId: { exact: deviceId } } : true
        });

        currentStream = stream;
        video.srcObject = stream;

    } catch (error) {
        alert("Error starting camera!");
    }
}

// Capture image
function capture() {
    if (!currentStream) {
        alert("Start camera first!");
        return;
    }

    const canvas = document.createElement('canvas');
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;

    const ctx = canvas.getContext('2d');
    ctx.drawImage(video, 0, 0);

    const dataURL = canvas.toDataURL('image/png');

    document.getElementById('imageData').value = dataURL;

    // Preview
    const preview = document.getElementById('preview');
    preview.src = dataURL;
    preview.style.display = "block";

    alert("Image Captured!");
}

window.onload = loadCameras;
</script>

</body>
</html>