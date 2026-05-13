<?php

namespace App\Actions\Map;

class RegisterMapRoutesAction
{
    public function __invoke(): void
    {
        register_rest_route(MapConfig::REST_NAMESPACE, MapConfig::REST_REVERSE_ROUTE, [
            'methods' => 'GET',
            'callback' => [app(ReverseGeocodeAction::class), 'asRestCallback'],
            'permission_callback' => '__return_true',
        ]);
    }
}
