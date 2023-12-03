<?php
// config/firebase/firebase.php
return [
    'credentials' => [
        'file' => storage_path(env('FIREBASE_JSON_KEY_PATH',null)),
    ],
    'project_id'=>env('FIREBASE_PROJECT_ID',null),
    'database_url'=>env('FIREBASE_DATABASE_URL',null)

];
