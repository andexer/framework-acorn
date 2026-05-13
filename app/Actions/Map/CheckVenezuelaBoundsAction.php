<?php

namespace App\Actions\Map;

class CheckVenezuelaBoundsAction
{
    public function __invoke(float $lat, float $lng): bool
    {
        return $lat >= 0.4 && $lat <= 12.4 && $lng >= -73.6 && $lng <= -59.6;
    }
}
