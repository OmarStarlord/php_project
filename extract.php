<?php
include 'vendor/autoload.php';

function extractTextFromPDF($file) {
    $parser = new \Smalot\PdfParser\Parser();
    $pdf = $parser->parseFile($file);
    $text = $pdf->getText();

    return nl2br($text);
}

$statusMsg = '';

if (isset($_POST['submit'])) {
    if (!empty($_FILES["pdf_file"]["name"])) {
        $fileName = basename($_FILES["pdf_file"]["name"]);
        $fileType = pathinfo($fileName, PATHINFO_EXTENSION);
        $allowTypes = array('pdf');

        if (in_array($fileType, $allowTypes)) {
            $file = $_FILES["pdf_file"]["tmp_name"];
            $pdfText = extractTextFromPDF($file);

            // Save the extracted text to a file
            file_put_contents('result.txt', $pdfText);

        } else {
            $statusMsg = '<p>Sorry, only PDF files are allowed to upload.</p>';
        }
    } else {
        $statusMsg = '<p>Please select a PDF file to extract text.</p>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Extract Text from PDF using PHP</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        form {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <h1>Extract Text from PDF using PHP</h1>

    <form action="" method="post" enctype="multipart/form-data">
        <label>Upload PDF File:</label>
        <input type="file" name="pdf_file" required/>
        <input type="submit" name="submit" value="Upload"/>
    </form>

    <?php echo $statusMsg; ?>
    
    <?php if (!empty($pdfText)) : ?>
        <div>
            <h2>Extracted Text:</h2>
            <?php echo $pdfText; ?>
        </div>
    <?php endif; ?>
</body>
</html>
