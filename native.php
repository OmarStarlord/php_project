<?php
// Path: scraper/native.php
require 'vendor/autoload.php';

use Goutte\Client;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle form submission
    $url = $_POST['url'];

    $client = new Client();
    $crawler = $client->request('GET', $url);

    // Extract and echo the course name
    $courseName = $crawler->filter('h2.course-name')->text();
    
    // Extract and echo the instructor's name
    $instructorName = $crawler->filter('strong')->eq(0)->text();
    
    // Extract and convert the completion date to the desired format (YYYY-MM-DD)
    $completionDateText = $crawler->filter('strong')->eq(1)->text();
    $completionDate = DateTime::createFromFormat('F j, Y', $completionDateText);
    
    if ($completionDate instanceof DateTime) {
        $formattedDate = $completionDate->format('Y-m-d');
    } else {
        $formattedDate = 'N/A';
    }
} else {
    // Set default values
    $url = '';
    $courseName = '';
    $instructorName = '';
    $formattedDate = '';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Web Scraper</title>
</head>
<body>
    <h1>Web Scraper</h1>
    
    <form method="post" action="">
        <label for="url">Enter the URL:</label>
        <input type="text" id="url" name="url" value="<?php echo htmlspecialchars($url); ?>" required>
        <button type="submit">Extract</button>
    </form>

    <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
        <h2>Results:</h2>
        <p>Course Name: <?php echo htmlspecialchars($courseName); ?></p>
        <p>Instructor: <?php echo htmlspecialchars($instructorName); ?></p>
        <p>Completion Date: <?php echo htmlspecialchars($formattedDate); ?></p>
    <?php endif; ?>
</body>
</html>
