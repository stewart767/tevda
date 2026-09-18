<?php

namespace App\Services;

class QrCodeService
{
    /**
     * Ensure autoloader for BaconQrCode if vendor files exist locally.
     */
    protected static function ensureAutoloaderRegistered(): void
    {
        if (class_exists(\BaconQrCode\Encoder\Encoder::class, false)) {
            return;
        }

        if (function_exists('base_path')) {
            $baconPath = base_path('vendor/bacon/bacon-qr-code/src/Encoder/Encoder.php');
            if (file_exists($baconPath)) {
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
    }

    /**
     * Generate standard QR Code matrix (boolean 2D array).
     * Guaranteed to work in ALL environments without requiring external vendor packages.
     */
    public static function getMatrix(string $data): array
    {
        self::ensureAutoloaderRegistered();

        // 1. If BaconQrCode is available, use it
        if (class_exists(\BaconQrCode\Encoder\Encoder::class)) {
            try {
                $qr = \BaconQrCode\Encoder\Encoder::encode($data, \BaconQrCode\Common\ErrorCorrectionLevel::M());
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
            } catch (\Throwable $e) {
                // Fallback to built-in pure generator
            }
        }

        // 2. Pure PHP self-contained standard ISO/IEC 18004 QR Matrix Generator
        return self::generatePureMatrix($data);
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

    /*
    |--------------------------------------------------------------------------
    | PURE PHP ISO/IEC 18004 STANDARD QR CODE GENERATOR ENGINE
    |--------------------------------------------------------------------------
    | 100% self-contained, zero external dependencies required.
    */
    private static array $exp = [];
    private static array $log = [];
    private static bool $gfInit = false;

    private static function initGf(): void
    {
        if (self::$gfInit) return;
        $x = 1;
        for ($i = 0; $i < 255; $i++) {
            self::$exp[$i] = $x;
            self::$log[$x] = $i;
            $x <<= 1;
            if ($x & 0x100) {
                $x ^= 0x11d;
            }
        }
        for ($i = 255; $i < 512; $i++) {
            self::$exp[$i] = self::$exp[$i - 255];
        }
        self::$gfInit = true;
    }

    private static function gfMul(int $x, int $y): int
    {
        if ($x === 0 || $y === 0) return 0;
        return self::$exp[self::$log[$x] + self::$log[$y]];
    }

    private static function rsPoly(int $ecLen): array
    {
        self::initGf();
        $poly = [1];
        for ($i = 0; $i < $ecLen; $i++) {
            $factor = [1, self::$exp[$i]];
            $newPoly = array_fill(0, count($poly) + count($factor) - 1, 0);
            for ($j = 0; $j < count($poly); $j++) {
                for ($k = 0; $k < count($factor); $k++) {
                    $newPoly[$j + $k] ^= self::gfMul($poly[$j], $factor[$k]);
                }
            }
            $poly = $newPoly;
        }
        return $poly;
    }

    private static function rsEncode(array $data, int $ecLen): array
    {
        self::initGf();
        $gen = self::rsPoly($ecLen);
        $res = array_fill(0, $ecLen, 0);
        
        foreach ($data as $byte) {
            $factor = $byte ^ ($res[0] ?? 0);
            array_shift($res);
            $res[] = 0;
            if ($factor !== 0) {
                for ($i = 0; $i < $ecLen; $i++) {
                    $res[$i] ^= self::gfMul($gen[$i + 1], $factor);
                }
            }
        }
        return $res;
    }

    private static array $versionSpecsM = [
        1 => [16, 10, 1, 16, 0, 0],
        2 => [28, 16, 1, 28, 0, 0],
        3 => [44, 26, 1, 44, 0, 0],
        4 => [64, 18, 2, 32, 0, 0],
        5 => [86, 24, 2, 43, 0, 0],
        6 => [108, 16, 4, 27, 0, 0],
        7 => [124, 18, 4, 31, 0, 0],
        8 => [154, 22, 2, 38, 2, 39],
        9 => [182, 22, 3, 36, 2, 37],
        10 => [216, 26, 4, 43, 1, 44],
    ];

    private static array $alignmentPositions = [
        1 => [],
        2 => [6, 18],
        3 => [6, 22],
        4 => [6, 26],
        5 => [6, 30],
        6 => [6, 34],
        7 => [6, 22, 38],
        8 => [6, 24, 42],
        9 => [6, 26, 46],
        10 => [6, 28, 50],
    ];

    private static array $formatInfoM = [
        0 => 0x5412,
        1 => 0x5125,
        2 => 0x5e7c,
        3 => 0x5b4b,
        4 => 0x45f9,
        5 => 0x40ce,
        6 => 0x4f97,
        7 => 0x4aa0,
    ];

    private static function generatePureMatrix(string $text): array
    {
        $len = strlen($text);
        
        $version = 1;
        while ($version <= 10) {
            $capacity = self::$versionSpecsM[$version][0];
            if ($len + 3 <= $capacity) {
                break;
            }
            $version++;
        }
        if ($version > 10) {
            $version = 10;
        }

        $spec = self::$versionSpecsM[$version];
        $totalDataBytes = $spec[0];
        $ecBytesPerBlock = $spec[1];
        $numBlocksG1 = $spec[2];
        $dataBytesG1 = $spec[3];
        $numBlocksG2 = $spec[4];
        $dataBytesG2 = $spec[5];

        // 1. Bit Buffer (Byte mode 0100)
        $bits = '0100';
        $charCountBits = ($version <= 9) ? 8 : 16;
        $bits .= str_pad(decbin($len), $charCountBits, '0', STR_PAD_LEFT);
        for ($i = 0; $i < $len; $i++) {
            $bits .= str_pad(decbin(ord($text[$i])), 8, '0', STR_PAD_LEFT);
        }

        $maxBits = $totalDataBytes * 8;
        $terminatorBits = min(4, $maxBits - strlen($bits));
        if ($terminatorBits > 0) {
            $bits .= str_repeat('0', $terminatorBits);
        }

        if (strlen($bits) % 8 !== 0) {
            $bits .= str_repeat('0', 8 - (strlen($bits) % 8));
        }

        $bytes = [];
        for ($i = 0; $i < strlen($bits); $i += 8) {
            $bytes[] = bindec(substr($bits, $i, 8));
        }

        $pad = [0xEC, 0x11];
        $padIdx = 0;
        while (count($bytes) < $totalDataBytes) {
            $bytes[] = $pad[$padIdx % 2];
            $padIdx++;
        }

        // 2. Error Correction
        $blocks = [];
        $ecBlocks = [];
        $byteOffset = 0;

        for ($b = 0; $b < $numBlocksG1; $b++) {
            $blockData = array_slice($bytes, $byteOffset, $dataBytesG1);
            $byteOffset += $dataBytesG1;
            $blocks[] = $blockData;
            $ecBlocks[] = self::rsEncode($blockData, $ecBytesPerBlock);
        }

        for ($b = 0; $b < $numBlocksG2; $b++) {
            $blockData = array_slice($bytes, $byteOffset, $dataBytesG2);
            $byteOffset += $dataBytesG2;
            $blocks[] = $blockData;
            $ecBlocks[] = self::rsEncode($blockData, $ecBytesPerBlock);
        }

        // 3. Interleaving
        $finalSequence = [];
        $maxDataLen = max($dataBytesG1, $dataBytesG2);
        for ($i = 0; $i < $maxDataLen; $i++) {
            foreach ($blocks as $b) {
                if (isset($b[$i])) {
                    $finalSequence[] = $b[$i];
                }
            }
        }

        for ($i = 0; $i < $ecBytesPerBlock; $i++) {
            foreach ($ecBlocks as $ecb) {
                if (isset($ecb[$i])) {
                    $finalSequence[] = $ecb[$i];
                }
            }
        }

        $finalBits = '';
        foreach ($finalSequence as $b) {
            $finalBits .= str_pad(decbin($b), 8, '0', STR_PAD_LEFT);
        }

        $remainderBits = [1 => 0, 2 => 7, 3 => 7, 4 => 7, 5 => 7, 6 => 7, 7 => 0, 8 => 0, 9 => 0, 10 => 0];
        $finalBits .= str_repeat('0', $remainderBits[$version] ?? 0);

        // 4. Matrix Layout
        $size = 17 + ($version * 4);
        $matrix = array_fill(0, $size, array_fill(0, $size, null));
        $reserved = array_fill(0, $size, array_fill(0, $size, false));

        self::placePureFinder($matrix, $reserved, 0, 0);
        self::placePureFinder($matrix, $reserved, $size - 7, 0);
        self::placePureFinder($matrix, $reserved, 0, $size - 7);

        for ($i = 8; $i < $size - 8; $i++) {
            $matrix[6][$i] = ($i % 2 === 0) ? 1 : 0;
            $reserved[6][$i] = true;
            $matrix[$i][6] = ($i % 2 === 0) ? 1 : 0;
            $reserved[$i][6] = true;
        }

        $matrix[(4 * $version) + 9][8] = 1;
        $reserved[(4 * $version) + 9][8] = true;

        $alignPos = self::$alignmentPositions[$version];
        if (!empty($alignPos)) {
            foreach ($alignPos as $r) {
                foreach ($alignPos as $c) {
                    if ($matrix[$r][$c] !== null || $reserved[$r][$c]) {
                        continue;
                    }
                    self::placePureAlignment($matrix, $reserved, $r, $c);
                }
            }
        }

        for ($i = 0; $i < 9; $i++) {
            $reserved[8][$i] = true;
            $reserved[$i][8] = true;
        }
        for ($i = $size - 8; $i < $size; $i++) {
            $reserved[8][$i] = true;
            $reserved[$i][8] = true;
        }

        if ($version >= 7) {
            for ($i = 0; $i < 6; $i++) {
                for ($j = 0; $j < 3; $j++) {
                    $reserved[$size - 11 + $j][$i] = true;
                    $reserved[$i][$size - 11 + $j] = true;
                }
            }
        }

        $bitIdx = 0;
        $bitLen = strlen($finalBits);
        $up = true;

        for ($right = $size - 1; $right > 0; $right -= 2) {
            if ($right === 6) $right--;
            $rows = $up ? range($size - 1, 0, -1) : range(0, $size - 1);
            foreach ($rows as $r) {
                foreach ([$right, $right - 1] as $c) {
                    if (!$reserved[$r][$c]) {
                        $matrix[$r][$c] = ($bitIdx < $bitLen) ? (int)$finalBits[$bitIdx] : 0;
                        $bitIdx++;
                    }
                }
            }
            $up = !$up;
        }

        $bestScore = PHP_INT_MAX;
        $bestMatrix = null;

        for ($mask = 0; $mask < 8; $mask++) {
            $testMatrix = $matrix;
            for ($r = 0; $r < $size; $r++) {
                for ($c = 0; $c < $size; $c++) {
                    if (!$reserved[$r][$c]) {
                        if (self::pureMaskCondition($mask, $r, $c)) {
                            $testMatrix[$r][$c] ^= 1;
                        }
                    }
                }
            }
            self::applyPureFormatInfo($testMatrix, $mask, $size);
            
            $score = self::calculatePurePenalty($testMatrix, $size);
            if ($score < $bestScore) {
                $bestScore = $score;
                $bestMatrix = $testMatrix;
            }
        }

        $result = [];
        for ($r = 0; $r < $size; $r++) {
            $row = [];
            for ($c = 0; $c < $size; $c++) {
                $row[] = ($bestMatrix[$r][$c] === 1);
            }
            $result[] = $row;
        }

        return $result;
    }

    private static function pureMaskCondition(int $mask, int $r, int $c): bool
    {
        return match ($mask) {
            0 => (($r + $c) % 2 === 0),
            1 => ($r % 2 === 0),
            2 => ($c % 3 === 0),
            3 => (($r + $c) % 3 === 0),
            4 => (((int)floor($r / 2) + (int)floor($c / 3)) % 2 === 0),
            5 => (($r * $c) % 2 + ($r * $c) % 3 === 0),
            6 => ((($r * $c) % 2 + ($r * $c) % 3) % 2 === 0),
            7 => ((($r + $c) % 2 + ($r * $c) % 3) % 2 === 0),
            default => false,
        };
    }

    private static function placePureFinder(array &$matrix, array &$reserved, int $row, int $col): void
    {
        for ($r = -1; $r <= 7; $r++) {
            for ($c = -1; $c <= 7; $c++) {
                $tr = $row + $r;
                $tc = $col + $c;
                if ($tr < 0 || $tr >= count($matrix) || $tc < 0 || $tc >= count($matrix)) continue;
                $reserved[$tr][$tc] = true;
                if ($r >= 0 && $r <= 6 && $c >= 0 && $c <= 6) {
                    if ($r === 0 || $r === 6 || $c === 0 || $c === 6 || ($r >= 2 && $r <= 4 && $c >= 2 && $c <= 4)) {
                        $matrix[$tr][$tc] = 1;
                    } else {
                        $matrix[$tr][$tc] = 0;
                    }
                } else {
                    $matrix[$tr][$tc] = 0;
                }
            }
        }
    }

    private static function placePureAlignment(array &$matrix, array &$reserved, int $row, int $col): void
    {
        for ($r = -2; $r <= 2; $r++) {
            for ($c = -2; $c <= 2; $c++) {
                $tr = $row + $r;
                $tc = $col + $c;
                $reserved[$tr][$tc] = true;
                if (abs($r) === 2 || abs($c) === 2 || ($r === 0 && $c === 0)) {
                    $matrix[$tr][$tc] = 1;
                } else {
                    $matrix[$tr][$tc] = 0;
                }
            }
        }
    }

    private static function applyPureFormatInfo(array &$matrix, int $mask, int $size): void
    {
        $bits = self::$formatInfoM[$mask];
        $bitStr = str_pad(decbin($bits), 15, '0', STR_PAD_LEFT);

        $coordsTopLeft = [
            [8, 0], [8, 1], [8, 2], [8, 3], [8, 4], [8, 5], [8, 7], [8, 8],
            [7, 8], [5, 8], [4, 8], [3, 8], [2, 8], [1, 8], [0, 8]
        ];
        for ($i = 0; $i < 15; $i++) {
            $val = (int)$bitStr[$i];
            [$r, $c] = $coordsTopLeft[$i];
            $matrix[$r][$c] = $val;
        }

        for ($i = 0; $i < 7; $i++) {
            $matrix[$size - 1 - $i][8] = (int)$bitStr[$i];
        }
        for ($i = 0; $i < 8; $i++) {
            $matrix[8][$size - 8 + $i] = (int)$bitStr[7 + $i];
        }
    }

    private static function calculatePurePenalty(array $matrix, int $size): int
    {
        $penalty = 0;

        for ($r = 0; $r < $size; $r++) {
            $count = 1;
            for ($c = 1; $c < $size; $c++) {
                if ($matrix[$r][$c] === $matrix[$r][$c - 1]) {
                    $count++;
                } else {
                    if ($count >= 5) $penalty += 3 + ($count - 5);
                    $count = 1;
                }
            }
            if ($count >= 5) $penalty += 3 + ($count - 5);
        }

        for ($c = 0; $c < $size; $c++) {
            $count = 1;
            for ($r = 1; $r < $size; $r++) {
                if ($matrix[$r][$c] === $matrix[$r - 1][$c]) {
                    $count++;
                } else {
                    if ($count >= 5) $penalty += 3 + ($count - 5);
                    $count = 1;
                }
            }
            if ($count >= 5) $penalty += 3 + ($count - 5);
        }

        for ($r = 0; $r < $size - 1; $r++) {
            for ($c = 0; $c < $size - 1; $c++) {
                $val = $matrix[$r][$c];
                if ($matrix[$r + 1][$c] === $val && $matrix[$r][$c + 1] === $val && $matrix[$r + 1][$c + 1] === $val) {
                    $penalty += 3;
                }
            }
        }

        return $penalty;
    }
}
