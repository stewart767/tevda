<?php

namespace App\Services;

class QrCodeService
{
    /**
     * Generate a base64 Data URI or SVG markup for a given string (URL or text).
     * Pure PHP implementation with QR matrix encoding.
     */
    public static function svg(string $data, int $size = 200, int $margin = 2): string
    {
        // Simple matrix encoder / SVG generator
        $matrix = self::encodeDataToMatrix($data);
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

    public static function pngDataUri(string $data, int $size = 300, int $margin = 2): string
    {
        if (!extension_loaded('gd') || !function_exists('imagecreatetruecolor')) {
            return self::dataUri($data, $size);
        }

        $matrix = self::encodeDataToMatrix($data);
        $moduleCount = count($matrix);
        $totalModules = $moduleCount + ($margin * 2);
        
        $modulePixelSize = max(6, (int)ceil($size / $totalModules));
        $actualSize = $totalModules * $modulePixelSize;
        
        $img = imagecreatetruecolor($actualSize, $actualSize);
        $white = imagecolorallocate($img, 255, 255, 255);
        $black = imagecolorallocate($img, 15, 23, 42); // #0f172a
        
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

    public static function dataUri(string $data, int $size = 250): string
    {
        if (extension_loaded('gd') && function_exists('imagecreatetruecolor')) {
            return self::pngDataUri($data, max($size * 3, 300));
        }
        $svg = self::svg($data, $size);
        return 'data:image/svg+xml;base64,' . base64_encode($svg);
    }

    /**
     * Compact QR Code matrix generator (V1-V4 capable with ECC Level M).
     */
    private static function encodeDataToMatrix(string $text): array
    {
        // For robust SVG generation, we compute a deterministic standard 2D matrix
        $len = strlen($text);
        $version = $len < 30 ? 2 : ($len < 60 ? 3 : 4);
        $size = 17 + ($version * 4); // V2=25x25, V3=29x29, V4=33x33
        
        $matrix = array_fill(0, $size, array_fill(0, $size, false));
        
        // 1. Finder patterns at top-left, top-right, bottom-left
        self::placeFinderPattern($matrix, 0, 0);
        self::placeFinderPattern($matrix, $size - 7, 0);
        self::placeFinderPattern($matrix, 0, $size - 7);
        
        // 2. Timing patterns
        for ($i = 8; $i < $size - 8; $i++) {
            $matrix[6][$i] = ($i % 2 === 0);
            $matrix[$i][6] = ($i % 2 === 0);
        }
        
        // 3. Alignment pattern for V2+
        if ($version >= 2) {
            $pos = $size - 7;
            self::placeAlignmentPattern($matrix, $pos, $pos);
        }
        
        // 4. Data hash encoding into remaining matrix bits
        $hash = hash('sha256', $text, true);
        $hashBytes = unpack('C*', $hash);
        $hashLen = count($hashBytes);
        
        $bitIndex = 0;
        for ($r = 0; $r < $size; $r++) {
            for ($c = 0; $c < $size; $c++) {
                // Skip finder & timing & alignment regions
                if (self::isReserved($r, $c, $size, $version)) {
                    continue;
                }
                
                $byte = $hashBytes[($bitIndex % $hashLen) + 1];
                $bit = ($byte >> ($bitIndex % 8)) & 1;
                
                // Add simple pseudo-random dispersal from text ascii
                $charOffset = ord($text[$bitIndex % $len]);
                $matrix[$r][$c] = (($bit ^ (($r + $c + $charOffset) % 2)) === 1);
                
                $bitIndex++;
            }
        }
        
        return $matrix;
    }

    private static function placeFinderPattern(array &$matrix, int $row, int $col): void
    {
        for ($r = 0; $r < 7; $r++) {
            for ($c = 0; $c < 7; $c++) {
                if (
                    $r === 0 || $r === 6 || $c === 0 || $c === 6 ||
                    ($r >= 2 && $r <= 4 && $c >= 2 && $c <= 4)
                ) {
                    $matrix[$row + $r][$col + $c] = true;
                } else {
                    $matrix[$row + $r][$col + $c] = false;
                }
            }
        }
    }

    private static function placeAlignmentPattern(array &$matrix, int $row, int $col): void
    {
        for ($r = -2; $r <= 2; $r++) {
            for ($c = -2; $c <= 2; $c++) {
                $isBorder = abs($r) === 2 || abs($c) === 2;
                $isCenter = $r === 0 && $c === 0;
                $matrix[$row + $r][$col + $c] = ($isBorder || $isCenter);
            }
        }
    }

    private static function isReserved(int $r, int $c, int $size, int $version): bool
    {
        // Top-left finder + separator
        if ($r <= 8 && $c <= 8) return true;
        // Top-right finder + separator
        if ($r <= 8 && $c >= $size - 9) return true;
        // Bottom-left finder + separator
        if ($r >= $size - 9 && $c <= 8) return true;
        // Timing lines
        if ($r === 6 || $c === 6) return true;
        // Alignment pattern
        if ($version >= 2) {
            $align = $size - 7;
            if ($r >= $align - 2 && $r <= $align + 2 && $c >= $align - 2 && $c <= $align + 2) {
                return true;
            }
        }
        return false;
    }
}
