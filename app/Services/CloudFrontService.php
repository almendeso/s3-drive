<?php

namespace App\Services;

use Aws\CloudFront\CloudFrontClient;

class CloudFrontService
{
    protected $client;
    protected $distribution;

    public function __construct()
    {
        $this->distribution = config('services.cloudfront.distribution');

        $this->client = new CloudFrontClient([
            'version' => 'latest',
            'region'  => 'us-east-1',
            'credentials' => [
                'key'    => config('filesystems.disks.s3.key'),
                'secret' => config('filesystems.disks.s3.secret')
            ]
        ]);
    }

    public function invalidate($paths)
    {
        if (!is_array($paths)) {
            $paths = [$paths];
        }

        return $this->client->createInvalidation([
            'DistributionId' => $this->distribution,
            'InvalidationBatch' => [
                'CallerReference' => uniqid(),
                'Paths' => [
                    'Quantity' => count($paths),
                    'Items' => $paths
                ]
            ]
        ]);
    }
}