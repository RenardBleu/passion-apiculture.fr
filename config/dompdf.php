<?php

return [
    'options' => [
        'isRemoteEnabled' => true,
        'isHtml5ParserEnabled' => true,
        'isFontSubsettingEnabled' => true,
        'debugPng' => true,
        'debugKeepTemp' => true,
        'debugCss' => true,
        'defaultFont' => 'DejaVu Sans',
        'defaultMediaType' => 'screen',
        'defaultPaperSize' => 'a4',
        'defaultPaperOrientation' => 'portrait',
        'dpi' => 96,
        'fontHeightRatio' => 1.1,
        'isPhpEnabled' => false,
        'isJavascriptEnabled' => true,
        'pdfBackend' => 'CPDF',
        'tempDir' => sys_get_temp_dir(),
        'fontDir' => __DIR__ . '/../vendor/dompdf/dompdf/lib/fonts',
        'fontCache' => __DIR__ . '/../vendor/dompdf/dompdf/lib/fonts',
    ]
]; 