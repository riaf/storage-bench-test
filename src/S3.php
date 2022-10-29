<?php

namespace Acme;

class S3
{
    public function createPresignedUrl(\Aws\S3\S3Client $s3Client, string $bucket, string $key)
    {
        $cmd = $s3Client->getCommand('GetObject', [
            'Bucket' => $bucket,
            'Key' => $key,
        ]);

        $request = $s3Client->createPresignedRequest($cmd, '+20 minutes');

        return (string) $request->getUri();
    }
}
