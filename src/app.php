<?php

/************************************
Entry point of the project.
To be run from the command line.
************************************/

include_once(__DIR__.'/utils.php');

printMessage("Starting...");

$partners = [
    RegionJobImporter::class,
    JobTeaserImporter::class,
];

foreach ($partners as $partner) {
    $importer = new $partner();
    $count = $importer->importJobs();

    printMessage("> {partner} : {count} jobs imported.", ['{partner}' => $importer::PARTNER_NAME, '{count}' => $count]);
}

/* list jobs */
$jobsLister = new JobsLister();
$jobs = $jobsLister->listJobs();
printMessage("> all jobs ({count}):", ['{count}' => count($jobs)]);

foreach ($jobs as $job) {
    printMessage(" {id}: {reference} - {title} - {publication}", [
    	'{id}' => $job->id,
    	'{reference}' => $job->reference,
    	'{title}' => $job->title,
    	'{publication}' => $job->publication
    ]);
}
