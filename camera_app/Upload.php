<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['image'])) {

        $image = $_POST['image'];

        // Remove base64 header
        $image = str_replace('data:image/png;base64,', '', $image);
        $image = str_replace(' ', '+', $image);

        $imageData = base64_decode($image);

        // Create uploads folder
        $folder = "uploads/";

        if (!file_exists($folder)) {
            mkdir($folder, 0777, true);
        }

        // File name
        $fileName = "capture_" . date("Ymd_His") . ".png";
        $filePath = $folder . $fileName;

        if (file_put_contents($filePath, $imageData)) {

            echo "<h2>✅ Image Uploaded Successfully!</h2>";
            echo "<img src='$filePath' width='400'><br><br>";
            echo "<a href='index.php'>⬅ Back</a>";

        } else {
            echo "❌ Failed to save image.";
        }

    } else {
        echo "No image received.";
    }

} else {
    echo "Invalid request.";
}
?>