<?php

namespace Vinorcola\DistantVersionedAssetsBundle\Config;

class Target
{
    /**
     * @var string
     */
    private $key;

    /**
     * @var string
     */
    private $baseUrl;

    /**
     * @var string
     */
    private $manifestUrl;

    /**
     * @var int
     */
    private $manifestTtl;

    /**
     * Target constructor.
     *
     * @param string $key
     * @param string $baseUrl
     * @param string $manifestPath
     * @param int    $manifestTtl
     */
    public function __construct(string $key, string $baseUrl, string $manifestPath, int $manifestTtl)
    {
        $this->key = $key;
        $this->baseUrl = rtrim($baseUrl, '/');
        $this->manifestUrl = $this->baseUrl . '/' . ltrim($manifestPath, '/');
        $this->manifestTtl = $manifestTtl;
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @return string
     */
    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }

    /**
     * @return string
     */
    public function getManifestUrl(): string
    {
        return $this->manifestUrl;
    }

    /**
     * @return int
     */
    public function getManifestTtl(): int
    {
        return $this->manifestTtl;
    }
}
