<?php

return [
    'name' => 'Presensi FH',
    'manifest' => [
        'name' => 'Presensi FH',
        'short_name' => 'Presensi FH',
        'start_url' => '/',
        'background_color' => '#EC4433',
        'theme_color' => '#EC4433',
        'display' => 'standalone',
        'orientation'=> 'portrait',
        'status_bar'=> '#EC4433',
        'icons' => [
            '72x72' => [
                'path' => 'assets/img/icon/72x72.png',
                'purpose' => 'any'
            ],
            '96x96' => [
                'path' => 'assets/img/icon/96x96.png',
                'purpose' => 'any'
            ],
            '128x128' => [
                'path' => 'assets/img/icon/128x128.png',
                'purpose' => 'any'
            ],
            '144x144' => [
                'path' => 'assets/img/icon/144x144.png',
                'purpose' => 'any'
            ],
            '152x152' => [
                'path' => 'assets/img/icon/152x152.png',
                'purpose' => 'any'
            ],
            '192x192' => [
                'path' => 'assets/img/icon/192x192.png',
                'purpose' => 'any'
            ],
            '384x384' => [
                'path' => 'assets/img/icon/384x384.png',
                'purpose' => 'any'
            ],
            '512x512' => [
                'path' => 'assets/img/icon/512x512.png',
                'purpose' => 'any'
            ],
        ],
        // 'splash' => [
        //     '640x1136' => 'assets/img/icon/splash-640x1136.png',
        //     '750x1334' => 'assets/img/icon/splash-750x1334.png',
        //     '828x1792' => 'assets/img/icon/splash-828x1792.png',
        //     '1125x2436' => 'assets/img/icon/splash-1125x2436.png',
        //     '1242x2208' => 'assets/img/icon/splash-1242x2208.png',
        //     '1242x2688' => 'assets/img/icon/splash-1242x2688.png',
        //     '1536x2048' => 'assets/img/icon/splash-1536x2048.png',
        //     '1668x2224' => 'assets/img/icon/splash-1668x2224.png',
        //     '1668x2388' => 'assets/img/icon/splash-1668x2388.png',
        //     '2048x2732' => 'assets/img/icon/splash-2048x2732.png',
        // ],
        'custom' => [],
        'sw' => config('app.url').'/serviceworker.js'
    ]
];
