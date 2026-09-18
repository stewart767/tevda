<?php

namespace App\Services;

use BaconQrCode\Common\ErrorCorrectionLevel;
use BaconQrCode\Encoder\Encoder;

class QrCodeService
{
    /**
     * Ensure BaconQrCode autoloader is registered in all execution contexts.
     */
    protected static function ensureAutoloaderRegistered(): void
    {
        if (!class_exists(\BaconQrCode\Encoder\Encoder::class, false)) {
            spl_autoload_register(function ($class) {
                if (str_starts_with($class, 'BaconQrCode\\')) {
                    $path = base_path('vendor/bacon/bacon-qr-code/src/' . str_replace('\\', '/', substr($class, 12)) . '.php');
                    if (file_exists($path)) {
                        require_once $path;
                    }
                } elseif (str_starts_with($class, 'DASPRiD\\Enum\\')) {
                    $path = base_path('vendor/dasprid/enum/src/' . str_replace('\\', '/', substr($class, 13)) . '.php');
                    if (file_exists($path)) {
                        require_once $path;
                    }
                }
            });
        }
    }

    /**
     * Generate standard QR Code matrix from input text.
     */
    public static function getMatrix(string $data): array
    {
        self::ensureAutoloaderRegistered();
        $qr = Encoder::encode($data, ErrorCorrectionLevel::M());
        $byteMatrix = $qr->getMatrix();
        $w = $byteMatrix->getWidth();
        $h = $byteMatrix->getHeight();

        $matrix = [];
        for ($y = 0; $y < $h; $y++) {
            $row = [];
            for ($x = 0; $x < $w; $x++) {
                $row[] = ($byteMatrix->get($x, $y) === 1);
            }
            $matrix[] = $row;
        }

        return $matrix;
    }

    /**
     * Generate a standard vector SVG QR Code for a given string (URL or text).
     */
    public static function svg(string $data, int $size = 200, int $margin = 2): string
    {
        $matrix = self::getMatrix($data);
        $moduleCount = count($matrix);
        $totalSize = $moduleCount + ($margin * 2);
        
        $svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 ' . $totalSize . ' ' . $totalSize . '" width="' . $size . '" height="' . $size . '" shape-rendering="crispEdges">';
        $svg .= '<rect width="100%" height="100%" fill="#ffffff"/>';
        $svg .= '<path fill="#0f172a" d="';
        
        $pathD = '';
        for ($r = 0; $r < $moduleCount; $r++) {
            for ($c = 0; $c < $moduleCount; $c++) {
                if ($matrix[$r][$c]) {
                    $x = $c + $margin;
                    $y = $r + $margin;
                    $pathD .= "M{$x},{$y}h1v1h-1z ";
                }
            }
        }
        
        $svg .= $pathD . '"/>';
        $svg .= '</svg>';
        
        return $svg;
    }

    /**
     * Generate a high-resolution PNG Data URI from standard QR matrix using GD.
     */
    public static function pngDataUri(string $data, int $size = 300, int $margin = 2): string
    {
        if (!extension_loaded('gd') || !function_exists('imagecreatetruecolor')) {
            return self::svgDataUri($data, $size, $margin);
        }

        $matrix = self::getMatrix($data);
        $moduleCount = count($matrix);
        $totalModules = $moduleCount + ($margin * 2);
        
        $modulePixelSize = max(6, (int)ceil($size / $totalModules));
        $actualSize = $totalModules * $modulePixelSize;
        
        $img = imagecreatetruecolor($actualSize, $actualSize);
        $white = imagecolorallocate($img, 255, 255, 255);
        $black = imagecolorallocate($img, 15, 23, 42); // Deep slate for high contrast scannability
        
        imagefill($img, 0, 0, $white);
        
        for ($r = 0; $r < $moduleCount; $r++) {
            for ($c = 0; $c < $moduleCount; $c++) {
                if ($matrix[$r][$c]) {
                    $x1 = ($c + $margin) * $modulePixelSize;
                    $y1 = ($r + $margin) * $modulePixelSize;
                    $x2 = $x1 + $modulePixelSize - 1;
                    $y2 = $y1 + $modulePixelSize - 1;
                    imagefilledrectangle($img, $x1, $y1, $x2, $y2, $black);
                }
            }
        }
        
        ob_start();
        imagepng($img);
        $imageData = ob_get_clean();
        imagedestroy($img);
        
        return 'data:image/png;base64,' . base64_encode($imageData);
    }

    /**
     * Generate SVG as base64 Data URI.
     */
    public static function svgDataUri(string $data, int $size = 250, int $margin = 2): string
    {
        $svg = self::svg($data, $size, $margin);
        return 'data:image/svg+xml;base64,' . base64_encode($svg);
    }

    /**
     * Return base64 Data URI (PNG if GD is loaded, otherwise SVG).
     */
    public static function dataUri(string $data, int $size = 250): string
    {
        if (extension_loaded('gd') && function_exists('imagecreatetruecolor')) {
            return self::pngDataUri($data, max($size * 2, 300), 2);
        }
        return self::svgDataUri($data, $size, 2);
    }
}
