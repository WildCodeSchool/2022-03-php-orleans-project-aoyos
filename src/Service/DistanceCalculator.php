<?php

namespace App\Service;

use App\Model\Localizable;
use Exception;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class DistanceCalculator
{
    public const URL = 'https://api-adresse.data.gouv.fr/search/';
    public const EARTH_RADIUS_KM = 6371.07103;

    public function __construct(
        private HttpClientInterface $client,
    ) {
    }

    public function setCoordinates(Localizable $localizable): void
    {
        $response = $this->client->request(
            'GET',
            self::URL,
            [
                'query' => [
                    'q' => $localizable->getAddress(),
                ]
            ]
        );

        if ($response->getStatusCode() === 200) {
            $data = $response->toArray();
            if (isset($data['features'][0])) {
                $longitude = $data['features'][0]['geometry']['coordinates'][0];
                $latitude = $data['features'][0]['geometry']['coordinates'][1];
                $localizable->setLatitude($latitude)->setLongitude($longitude);
            } else {
                throw new Exception('Adresse non trouvée');
            }
        } else {
            throw new Exception('Une erreur est survenue lors de la récupération de l\'adresse');
        }
    }

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
