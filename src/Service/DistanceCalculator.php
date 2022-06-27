<?php

namespace App\Service;

use App\Entity\Artist;
use App\Entity\Coordinate;
use App\Repository\CoordinateRepository;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class DistanceCalculator
{
    public function __construct(
        private CoordinateRepository $coordinateRepository,
        private HttpClientInterface $client
    ) {
    }

    public function getArtistCoordinates(Artist $artist): void
    {
        $coordinates = new Coordinate();
        $response = $this->client->request(
            'GET',
            'https://api-adresse.data.gouv.fr/search/?q=' . $artist->getAddress()
        );
        $data = $response->toArray();
        $coordX = $data['geometry']['coordinates'][0];
        $coordY = $data['geometry']['coordinates'][1];
        $coordinates->setX($coordX);
        $coordinates->setY($coordY);
        $this->coordinateRepository->add($coordinates, true);
    }
}
