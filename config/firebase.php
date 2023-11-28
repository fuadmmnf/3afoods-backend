<?php
// config/firebase/firebase.php
return [
    'credentials' => [
        'file' => env('FIREBASE_JSON_KEY_PATH',null),
    ],
    'database_url'=>env('FIREBASE_DATABASE_URL',null)

];
