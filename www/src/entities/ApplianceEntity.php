<?php

namespace Linkedout\App\entities;

class ApplianceEntity
{
    public mixed $internshipId;
    public mixed $personId;
    public mixed $ratingId;
    public ?\DateTime $wishDate = null;
    public ?\DateTime $applianceDate = null;
    public ?\DateTime $responseDate = null;
    public bool $validation = false;

    public function __construct(?array $rawData = null)
    {
        if ($rawData == null)
            return;

        $this->internshipId = $rawData['internshipId'];
        $this->personId = $rawData['personId'];
        $this->ratingId = $rawData['ratingId'];
        if ($rawData['wishDate'] !== null)
            $this->wishDate = new \DateTime($rawData['wishDate']);
        if ($rawData['applianceDate'] !== null)
            $this->applianceDate = new \DateTime($rawData['applianceDate']);
        if ($rawData['responseDate'] !== null)
            $this->responseDate = new \DateTime($rawData['responseDate']);
        $this->validation = (bool)$rawData['validation'];
    }
}
