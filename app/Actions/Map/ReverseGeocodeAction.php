<?php

namespace App\Actions\Map;

class ReverseGeocodeAction
{
    public function handle(float $lat, float $lng): ?array
    {
        if (! $this->validCoordinates($lat, $lng)) {
            return null;
        }

        $cacheKey = 'framework_rg_'.md5(number_format($lat, 4, '.', '').'|'.number_format($lng, 4, '.', ''));
        $cached = get_transient($cacheKey);

        if (is_array($cached)) {
            return $cached;
        }

        $url = add_query_arg([
            'format' => 'jsonv2',
            'addressdetails' => 1,
            'lat' => number_format($lat, 6, '.', ''),
            'lon' => number_format($lng, 6, '.', ''),
            'accept-language' => 'es',
        ], 'https://nominatim.openstreetmap.org/reverse');

        $response = wp_remote_get($url, [
            'timeout' => 10,
            'user-agent' => 'framework-acorn/1.0 ('.home_url('/').')',
            'headers' => ['Accept' => 'application/json'],
        ]);

        if (is_wp_error($response)) {
            return null;
        }

        $code = (int) wp_remote_retrieve_response_code($response);

        if ($code < 200 || $code >= 300) {
            return null;
        }

        $body = json_decode((string) wp_remote_retrieve_body($response), true);
        $payload = $this->normalizePayload($body, $lat, $lng);

        set_transient($cacheKey, $payload, 5 * MINUTE_IN_SECONDS);

        return $payload;
    }

    public function asRestCallback(\WP_REST_Request $request): \WP_REST_Response
    {
        $lat = (float) $request->get_param('lat');
        $lng = (float) $request->get_param('lng');

        if (! $this->validCoordinates($lat, $lng)) {
            return new \WP_REST_Response(['message' => 'Invalid coordinates.'], 422);
        }

        $payload = $this->handle($lat, $lng);

        if ($payload === null) {
            return new \WP_REST_Response(['message' => 'Reverse geocode request failed.'], 502);
        }

        return new \WP_REST_Response($payload, 200);
    }

    private function validCoordinates(float $lat, float $lng): bool
    {
        return is_finite($lat) && is_finite($lng) && $lat >= -90 && $lat <= 90 && $lng >= -180 && $lng <= 180;
    }

    private function normalizePayload(array $body, float $lat, float $lng): array
    {
        $address = is_array($body['address'] ?? null) ? $body['address'] : [];
        $displayName = (string) ($body['display_name'] ?? '');

        $estado = $this->addressValue($address, ['state']);
        $ciudad = $this->addressValue($address, ['city', 'town', 'village', 'hamlet', 'city_district']);
        $municipio = $this->addressValue($address, ['municipality', 'county']);

        $parroquia = '';
        if (preg_match('/Parroquia\s+([^,]+)/i', $displayName, $matches)) {
            $parroquia = trim($matches[1]);
        }

        if (empty($parroquia)) {
            $parroquia = $this->addressValue($address, ['city_district', 'suburb', 'quarter', 'neighbourhood']);
        }

        $codigoPostal = $this->addressValue($address, ['postcode']);

        return [
            'estado' => $estado,
            'ciudad' => $ciudad,
            'municipio' => $municipio,
            'parroquia' => $parroquia,
            'codigo_postal' => $codigoPostal,
            'latitud' => round($lat, 6),
            'longitud' => round($lng, 6),
            'direccion_completa' => $displayName,
            'fuera_de_venezuela' => ! app(CheckVenezuelaBoundsAction::class)($lat, $lng),
        ];
    }

    private function addressValue(array $address, array $keys): string
    {
        foreach ($keys as $key) {
            $value = trim((string) ($address[$key] ?? ''));
            if ($value !== '') {
                return $value;
            }
        }

        return '';
    }
}
