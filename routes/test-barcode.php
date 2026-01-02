<?php

use Illuminate\Support\Facades\Route;
use Picqer\Barcode\BarcodeGeneratorPNG;

Route::get('/test-barcode-direct', function () {
    try {
        // Test 1: Check GD
        $gdLoaded = extension_loaded('gd');
        $imagecreate = function_exists('imagecreate');

        // Test 2: Try to generate barcode
        $generator = new BarcodeGeneratorPNG();
        $barcode = $generator->getBarcode('TEST-123', $generator::TYPE_CODE_128);
        $base64 = base64_encode($barcode);

        return response()->json([
            'success' => true,
            'gd_loaded' => $gdLoaded,
            'imagecreate_exists' => $imagecreate,
            'barcode_generated' => !empty($base64),
            'barcode_size' => strlen($base64),
            'html' => '<img src="data:image/png;base64,' . $base64 . '" alt="Barcode">'
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
            'gd_loaded' => extension_loaded('gd'),
            'imagecreate_exists' => function_exists('imagecreate')
        ]);
    }
});
