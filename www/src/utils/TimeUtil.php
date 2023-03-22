<?php

namespace Linkedout\App\utils;

use Carbon\Carbon;
use DateTime;
use Exception;

class TimeUtil
{
    static function calculateDuration(string $beginDate, string $endDate): string
    {
        try {
            $beginDate = new DateTime($beginDate);
            $endDate = new DateTime($endDate);
            $days = $beginDate->diff($endDate)->days;
            $months = round($days / 30);
            return $months . ' mois';
        } catch (Exception) {
            return 'Durée invalide';
        }
    }

    static function formatDate(string $date): string
    {
        try {
            $date = Carbon::parse($date);
            return $date->locale('fr_FR')->isoFormat('LL');
        } catch (Exception) {
            return 'Date invalide';
        }
    }
}
