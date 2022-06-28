<?php

namespace App\Model;

interface Localizable
{
    public function getAddress(): ?string;
    public function setLatitude(float $latitude): self;
    public function setLongitude(float $longitude): self;
}
