<?php

namespace App\Service;

use App\Entity\Coordinate;
use App\Model\Localizable;
use App\Repository\CoordinateRepository;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class DistanceCalculator
{
    public function __construct(
        private HttpClientInterface $client,
    ) {
    }

    public function setCoordinates(Localizable $localizable): void
    {
        $response = $this->client->request(
            'GET',
            'https://api-adresse.data.gouv.fr/search/?q=' . $localizable->getAddress()
        );
        $data = $response->toArray();
        $longitude = $data['features'][0]['geometry']['coordinates'][0];
        $latitude = $data['features'][0]['geometry']['coordinates'][1];
        $localizable->setLatitude($latitude)->setLongitude($longitude);
    }
}
