<?php

namespace App\Utils;

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Writer\PngWriter;

class QRCode
{
    public static function generate(string $data, string $path): void
    {
        $builder = new Builder(
            data: $data,
            writer: new PngWriter(),
            errorCorrectionLevel: ErrorCorrectionLevel::High,
        );

        $result = $builder->build();
        $result->saveToFile($path);
    }
}
