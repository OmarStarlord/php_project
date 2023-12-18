<?php

require '../vendor/autoload.php';

use Goutte\Client;
class WebScraper {
    private $url;
    private $courseName;
    private $studentName;
    private $formattedDate;

    public function __construct($url) {
        $this->url = $url;
    }

    public function scrape() {
        $client = new Client();
        $crawler = $client->request('GET', $this->url);

    
        $this->courseName = $crawler->filter('h2.course-name')->text();

        
        $this->studentName = $crawler->filter('strong')->eq(0)->text();

        
        $completionDateText = $crawler->filter('strong')->eq(1)->text();
        $completionDate = DateTime::createFromFormat('F j, Y', $completionDateText);

        if ($completionDate instanceof DateTime) {
            $this->formattedDate = $completionDate->format('Y-m-d');
        } else {
            $this->formattedDate = 'N/A';
        }
    }

    public function getCourseName() {
        return $this->courseName;
    }

    public function getStudentName() {
        return $this->studentName;
    }

    public function getFormattedDate() {
        return $this->formattedDate;
    }
}


?>