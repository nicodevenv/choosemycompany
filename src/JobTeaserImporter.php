<?php
declare(strict_types=1);

final class JobTeaserImporter extends JobsImporter {
    const PARTNER_NAME = 'jobteaser';
    const FILENAME = RESSOURCES_DIR . 'jobteaser.json';

    public function importJobs() {
        $fileContent = $this->openFile(self::FILENAME);
        $urlPrefix = $fileContent->offerUrlPrefix;

        $jobs = [];
        foreach ($fileContent->offers as $item) {
            $jobs[] = new Job(
                self::PARTNER_NAME,
                (string) $item->reference,
                (string) $item->title,
                (string) $item->description,
                $urlPrefix.$item->urlPath,
                (string) $item->companyname,
                (new DateTime($item->publishedDate))->format('Y-m-d h:i:s')
            );
        }

        parent::import(self::PARTNER_NAME, $jobs);

        return count($jobs);
    }
}
