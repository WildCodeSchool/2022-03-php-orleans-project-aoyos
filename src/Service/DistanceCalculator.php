<?php

namespace App\Service;

use App\Model\Localizable;
use Exception;
use LogicException;
use Symfony\Component\HttpClient\Exception\TransportException;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class DistanceCalculator
{
    public const URL = 'https://api-adresse.data.gouv.fr/search/';

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
}
