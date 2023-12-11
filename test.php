<?php

// Function to convert PDF to images using Ghostscript
function pdfToImages($pdfPath, $outputFolder) {
    $command = "gs -dNOPAUSE -sDEVICE=pngalpha -r300 -o $outputFolder/page_%03d.png $pdfPath";
    shell_exec($command);
}

// Function to extract text from an image using Tesseract OCR
function extractTextFromImage($imagePath) {
    $command = "tesseract $imagePath stdout";
    return shell_exec($command);
}

// Function to extract text from a PDF using Tesseract OCR
function extractTextFromPDF($pdfPath, $outputFolder) {
    pdfToImages($pdfPath, $outputFolder);
    
    $extractedText = "";

    // Loop through each generated image
    for ($pageNumber = 1; file_exists("$outputFolder/page_$pageNumber.png"); $pageNumber++) {
        $imagePath = "$outputFolder/page_$pageNumber.png";
        $text = extractTextFromImage($imagePath);

        // Append the extracted text to the result
        $extractedText .= "Page $pageNumber:\n$text\n\n";
    }

    return $extractedText;
}

// Check if a file was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["pdfFile"])) {
    $uploadFolder = "uploads";
    $outputFolder = "output";

    // Create upload and output folders if not exist
    if (!file_exists($uploadFolder)) {
        mkdir($uploadFolder, 0777, true);
    }

    if (!file_exists($outputFolder)) {
        mkdir($outputFolder, 0777, true);
    }

    $uploadedFile = $_FILES["pdfFile"];
    $uploadedFileName = $uploadedFile["name"];
    $uploadedFilePath = "$uploadFolder/$uploadedFileName";
    $outputFilePath = "$outputFolder/$uploadedFileName.txt";

    // Move the uploaded file to the upload folder
    move_uploaded_file($uploadedFile["tmp_name"], $uploadedFilePath);

    // Extract text from the uploaded PDF
    $extractedText = extractTextFromPDF($uploadedFilePath, $outputFolder);

    // Save extracted text to a file
    file_put_contents($outputFilePath, $extractedText);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PDF Text Extractor</title>
</head>
<body>
    <h1>PDF Text Extractor</h1>

    <form action="" method="post" enctype="multipart/form-data">
        <label for="pdfFile">Select a PDF file:</label>
        <input type="file" name="pdfFile" accept=".pdf" required>
        <button type="submit">Extract Text</button>
    </form>

    <?php
    // Display the extracted text if available
    if (isset($extractedText)) {
        echo "<h2>Extracted Text:</h2>";
        echo "<pre>$extractedText</pre>";
    }
    ?>

</body>
</html>
