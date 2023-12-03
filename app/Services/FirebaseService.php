<?php

namespace App\Services;


use Google\Cloud\Core\Exception\GoogleException;
use Kreait\Firebase\Factory;
use Google\Cloud\Firestore\FirestoreClient;

class FirebaseService
{
    protected $firebase;
    protected $firestore;

    /**
     * @throws GoogleException
     */
    public function __construct()
    {
        $this->firebase = (new Factory)
            ->withServiceAccount(config('firebase.credentials.file'))
            ->createFirestore();

        $this->firestore = new FirestoreClient([
            'projectId' => config('firebase.project_id')
        ]);

    }

    public function sendEmail(array $data)
    {
        $collection = $this->firestore->collection('emails');
        $newDocument = $collection->add($data);

        // You can get the generated key (document ID) if needed
        $key = $newDocument->id();

        return $key;
    }
}
