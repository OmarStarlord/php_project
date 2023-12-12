<?php namespace Ottosmops\Pdftotext;

require_once __DIR__ . '/vendor/autoload.php';

use Symfony\Component\Process\Process;

use Ottosmops\Pdftotext\Exceptions\CouldNotExtractText;
use Ottosmops\Pdftotext\Exceptions\FileNotFound;
use Ottosmops\Pdftotext\Exceptions\BinaryNotFound;


class Extract
{
    protected $executable = '';

    protected $options = '';

    protected $source = '';

    /**
     * setup executable and options
     * @param string $executable path to executable (default is 'pdftotext')
     * @param string $options    options for pdftotext
     */
    public function __construct($executable = null, $options = null)
    {
        $executable = 'pdftotext';
$process = Process::fromShellCommandline("where $executable || which $executable");
$process->run();

if (!$process->isSuccessful()) {
    throw new BinaryNotFound($process);
}

$executable = trim($process->getOutput());

        $this->executable = trim($executable);
        $this->options = (isset($options) && $options != '') ? $options: '-eol unix -enc UTF-8 -raw';
    }


    /**
     * get text from pdf
     * @param  string $source
     * @param  string $options (optional)
     * @param  string $executable (optional)
     * @return string
     */
    public static function getText($source, $options = null, $executable = null)
    {
        return (new static($executable, $options))
                  ->pdf($source)
                  ->text();
    }

    /**
     * set options
     * @param  string $options
     * @return object
     */
    public function options($options = '')
    {
        $this->options = $options;

        return $this;
    }

    /**
     * set pdf files (source)
     * @param  string $source
     * @return object
     */
    public function pdf($source)
    {
        if (!file_exists($source)) {
            throw new FileNotFound("could not find pdf {$source}");
        }
        $this->source = $source;

        return $this;
    }

    /**
     * extract text
     * @return string
     */
    public function text()
    {
        $command = "{$this->executable} {$this->options} '{$this->source}' -";

        $process = Process::fromShellCommandline($command);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new CouldNotExtractText($process);
        }

        return trim($process->getOutput(), " \t\n\r\0\x0B\x0C");
    }
    public static function handleFileUpload()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['pdf_file'])) {
            try {
                $uploadedFile = $_FILES['pdf_file'];

                // Check for errors during file upload
                if ($uploadedFile['error'] !== UPLOAD_ERR_OK) {
                    throw new \Exception('Error during file upload');
                }

                // Move the uploaded file to a temporary location
                $tmpFilePath = __DIR__ . '/tmp/' . $uploadedFile['name'];
                move_uploaded_file($uploadedFile['tmp_name'], $tmpFilePath);

                // Extract text from the uploaded PDF
                $text = self::getText($tmpFilePath);

                // Display the extracted text
                echo nl2br(htmlspecialchars($text));

                // Optionally, you can delete the temporary file
                // unlink($tmpFilePath);
            } catch (\Exception $e) {
                echo 'Error: ' . $e->getMessage();
            }
        }
    }
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PDF to Text Converter</title>
</head>
<body>
    <form action="" method="post" enctype="multipart/form-data">
        <label for="pdf_file">Choose a PDF file:</label>
        <input type="file" name="pdf_file" accept=".pdf" required>
        <button type="submit">Upload and Convert</button>
    </form>

    <!-- Display the extracted text here -->
    <div>
        <h2>Extracted Text:</h2>
        <?php Extract::handleFileUpload(); ?>
    </div>
</body>
</html>