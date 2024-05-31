<?php

declare(strict_types=1);

include_once(__DIR__ . '/config.php');

class JobsImporter
{
    private PDO $db;

    public function __construct()
    {
        try {
            $this->db = new PDO('mysql:host=' . SQL_HOST . ';dbname=' . SQL_DB, SQL_USER, SQL_PWD);
        } catch (Exception $e) {
            die('DB error: ' . $e->getMessage() . "\n");
        }
    }

    protected function openFile(string $filename)
    {
        $fileExtension = pathinfo($filename, PATHINFO_EXTENSION);
        switch ($fileExtension) {
            case 'xml':
                return simplexml_load_file($filename);
            case 'json':
                return json_decode(file_get_contents($filename));
        }

        throw new Exception('Unknown file extension');
    }

    /**
     * @param Job[] $jobs
     */
    protected function import(string $partnerName, array $jobs)
    {
        $this->db->exec('DELETE FROM job WHERE partner = "'.$partnerName.'"');

        $insertJobSql = array_map(fn(Job $job) => '(
            \''.addslashes($partnerName).'\',
            \''.addslashes($job->reference).'\',
            \''.addslashes($job->title).'\',
            \''.addslashes($job->description).'\',
            \''.addslashes($job->url).'\',
            \''.addslashes($job->company).'\',
            \''.addslashes($job->publication).'\'
        )', $jobs);

        $this->db->exec('INSERT INTO job (partner, reference, title, description, url, company_name, publication) VALUES '.implode(',', $insertJobSql));
    }
}
