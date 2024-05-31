<?php

declare(strict_types=1);

class Job
{
    public function __construct(
        public string $partner,
        public string $reference,
        public string $title,
        public string $description,
        public string $url,
        public string $company,
        public string $publication,
        public ?int $id = null,
    ) {
    }
}
