<?php
// phpcs:ignoreFile
namespace App\Config;

enum ReservationStatus : string
{
    case Waiting = 'En attente';
    case Validated = 'Validée';
    case Rejected = 'Refusée';

    public function getColor(): string
    {
        return match($this) {
            static::Waiting => 'warning',
            static::Validated => 'success',
            static::Rejected => 'danger',
        };
    }
}