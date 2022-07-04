<?php

namespace tests;

use App\Entity\Artist;
use App\Entity\Reservation;
use App\Service\DistanceCalculator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;

class DistanceTest extends TestCase
{
    public function testDistance(): void
    {
        $artist = new Artist();
        $artist->setLongitude(1.910865);
        $artist->setLatitude(47.873527);
        $reservation = new Reservation();
        $reservation->setLongitude(2.346209);
        $reservation->setLatitude(48.860147);
        $client = new MockHttpClient();
        $distanceCalculator = new DistanceCalculator($client);

        $this->assertEquals(114, $distanceCalculator->getDistance($artist, $reservation));
    }
}
