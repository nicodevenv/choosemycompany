<?php

declare(strict_types=1);

include_once(__DIR__ . '/config.php');

final class JobsLister
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

    public function listJobs(): array
    {
        $data = $this->db->query('SELECT id, partner, reference, title, description, url, company_name, publication FROM job')->fetchAll(PDO::FETCH_ASSOC);

        return array_map(fn(array $item) => new Job(
            $item['partner'],
            $item['reference'],
            $item['title'],
            $item['description'],
            $item['url'],
            $item['company_name'],
            $item['publication'],
            $item['id']
        ), $data);
    }
}
