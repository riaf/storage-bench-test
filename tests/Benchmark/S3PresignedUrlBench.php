<?php

namespace Acme\Benchmark;

use Aws\S3\S3Client;
use Acme\S3;

/**
 * @BeforeMethods({"setUp"})
 * @AfterMethods({"tearDown"})
 */
class S3PresignedUrlBench
{
    private $s3;
    private $s3Client;

    private $bucketName;
    private $objectKey;

    public function bench()
    {
        $this->s3->createPresignedUrl($this->s3Client, $this->bucketName, $this->objectKey);
    }

    public function setUp()
    {
        $this->s3Client = new S3Client([
            'version' => 'latest',
            'region' => $_ENV['AWS_REGION'],
            'credentials' => [
                'key' => $_ENV['AWS_ACCESS_KEY_ID'],
                'secret' => $_ENV['AWS_SECRET_ACCESS_KEY'],
            ],
            'endpoint' => $_ENV['AWS_ENDPOINT'],
        ]);

        $this->bucketName = $_ENV['AWS_S3_BUCKET'];
        $this->objectKey = uniqid('bench-presign-object');

        $this->s3Client->putObject([
            'Bucket' => $this->bucketName,
            'Key' => $this->objectKey,
            'Body' => random_bytes($_ENV['TARGET_FILESIZE'] || 1024 * 1024),
        ]);

        $this->s3 = new S3();
    }

    public function tearDown()
    {
        $this->s3Client->deleteObject([
            'Bucket' => $this->bucketName,
            'Key' => $this->objectKey,
        ]);
    }
}
