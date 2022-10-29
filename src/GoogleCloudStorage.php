<?php

namespace Acme;

use Google\Cloud\Storage\StorageObject;

class GoogleCloudStorage
{
    public function createPresignedUrl(StorageObject $object)
    {
        return $object->signedUrl(new \DateTime('+20 minutes'));
    }
}
