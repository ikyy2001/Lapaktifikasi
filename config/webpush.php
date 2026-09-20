<?php

return [
    'vapid' => [
        'subject' => env('VAPID_SUBJECT', 'mailto:support@lapaktifikasi.com'),
        'public_key' => env('VAPID_PUBLIC_KEY', 'BP1LIGlDFyxXmLEUeqJ-H2Ajkf-Fhigdaja17gEc8RDThqxqxNxlZLJL3PBWcSV9x2BDb09YH79O9qZLdSkoQPc'),
        'private_key' => env('VAPID_PRIVATE_KEY', 'EYMOhQXTCTX7HSnXG5X87zxNRMehJAm2lX2IWNKuEFo'),
    ],
];
