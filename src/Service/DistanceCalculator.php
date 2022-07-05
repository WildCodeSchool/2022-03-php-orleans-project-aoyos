<?php

namespace App\Service;

use App\Model\Localizable;

class DistanceCalculator
{
    public const EARTH_RADIUS_KM = 6371.07103;

    public function getDistance(Localizable $start, Localizable $end): float
    {
        $radiusLatitudeStart = $start->getLatitude() * (pi() / 180);
        $radiusLatitudeEnd = $end->getLatitude() * (pi() / 180);
        $latitudeDifference = $radiusLatitudeEnd - $radiusLatitudeStart;
        $longitudeDifference = ($end->getLongitude() - $start->getLongitude()) * (pi() / 180);
        $distance = 2 * self::EARTH_RADIUS_KM * sin(sqrt(sin($latitudeDifference / 2) * sin($latitudeDifference / 2)
        + cos($radiusLatitudeStart) * cos($radiusLatitudeEnd)
        * sin($longitudeDifference / 2) * sin($longitudeDifference / 2)));
        return round($distance, 0);
    }
}
