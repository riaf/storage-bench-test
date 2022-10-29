<?php

namespace Acme\Benchmark;

use Google\Cloud\Storage\StorageClient;
use Acme\GoogleCloudStorage;

/**
 * @BeforeMethods({"setUp"})
 * @AfterMethods({"tearDown"})
 */
class GoogleCloudStoragePresignedUrlBench
{
    private $gcs;
    private $storage;
    private $object;

    private $bucketName;
    private $objectKey;

    public function bench()
    {
        $this->gcs->createPresignedUrl($this->object);
    }

    public function setUp()
    {
        $this->storage = new StorageClient([
            'keyFilePath' => $_ENV['GOOGLE_APPLICATION_CREDENTIALS'],
        ]);

        $this->bucketName = $_ENV['GOOGLE_STORAGE_BUCKET'];
        $this->objectKey = uniqid('bench-presign-object');

        $bucket = $this->storage->bucket($this->bucketName);
        $bucket->upload(random_bytes($_ENV['TARGET_FILESIZE'] || 1024 * 1024), [
            'name' => $this->objectKey,
        ]);

        $this->gcs = new GoogleCloudStorage();
        $this->object = $this->storage->bucket($this->bucketName)->object($this->objectKey);
    }

    public function tearDown()
    {
        $bucket = $this->storage->bucket($this->bucketName);
        $bucket->object($this->objectKey)->delete();
    }
}
