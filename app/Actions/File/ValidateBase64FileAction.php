<?php

namespace App\Actions\File;

class ValidateBase64FileAction
{
    /**
     * Valida un string base64 y opcionalmente verifica su tipo MIME y tamaño.
     */
    public function __invoke(string $base64, array $allowedMimes = [], float $maxSizeMb = 0): bool
    {
        if (empty($base64)) {
            return false;
        }

        // 1. Verificar formato base64 básico (data:mime;base64,content)
        if (! preg_match('/^data:([^;]+);base64,(.+)$/', $base64, $matches)) {
            return false;
        }

        $mimeType = $matches[1];
        $content = $matches[2];

        // 2. Validar tipo MIME
        if (! empty($allowedMimes) && ! in_array($mimeType, $allowedMimes, true)) {
            return false;
        }

        // 3. Validar tamaño (estimado desde base64: cada 4 chars = 3 bytes)
        if ($maxSizeMb > 0) {
            $sizeInBytes = (strlen($content) * 3) / 4;
            $maxSizeInBytes = $maxSizeMb * 1024 * 1024;
            if ($sizeInBytes > $maxSizeInBytes) {
                return false;
            }
        }

        // 4. Intentar decodificar para asegurar integridad
        if (base64_decode($content, true) === false) {
            return false;
        }

        return true;
    }
}
