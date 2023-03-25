<?php

namespace Linkedout\App\controllers\api;

use Linkedout\App\models;

class PromotionController extends ApiController
{
    protected mixed $campusId;

    public function setRouteParams($campusId): void
    {
        $this->campusId = $campusId;
    }

    protected function fetch(): array
    {
        $promotionModel = new models\PromotionModel($this->database);

        if (empty($_GET['tutor']))
            $promotions = $promotionModel->getPromotionForCampusId($this->campusId);
        else
            $promotions = $promotionModel->getAvailablePromotionsForTutor($this->campusId, (int)$_GET['tutor']);

        return [
            'promotions' => $promotions ?? []
        ];
    }
}
