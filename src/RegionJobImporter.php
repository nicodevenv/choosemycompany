<?php
declare(strict_types=1);

final class RegionJobImporter extends JobsImporter {
    const PARTNER_NAME = 'region_jobs';
    const FILENAME = RESSOURCES_DIR . 'regionsjob.xml';

    public function importJobs() {
        $fileContent = $this->openFile(self::FILENAME);

        $jobs = [];
        foreach ($fileContent->item as $item) {
            $jobs[] = new Job(
                self::PARTNER_NAME,
                (string) $item->ref,
                (string) $item->title,
                (string) $item->description,
                (string) $item->url,
                (string) $item->company,
                (string) $item->pubDate
            );
        }

        parent::import(self::PARTNER_NAME, $jobs);

        return count($jobs);
    }
}
