<?php

namespace App\Services;

use Kreait\Firebase\Factory;

class FirebaseService
{
    protected $firebase;

    public function __construct()
    {
        $this->firebase = (new Factory)
            ->withServiceAccount(config('firebase.credentials.file'))
            ->withDatabaseUri(config('firebase.database_url'))
            ->createDatabase();
    }

    public function sendEmail(array $data)
    {
        $reference =$this->firebase->getReference('emails')->push($data);

        // You can get the generated key if needed
        $key = $reference->getKey();

        return $key;
    }
}
