<?php

namespace Vinorcola\DistantVersionedAssetsBundle\Model;

use Psr\Cache\CacheItemPoolInterface;
use RuntimeException;
use Vinorcola\DistantVersionedAssetsBundle\Config\Config;
use Vinorcola\DistantVersionedAssetsBundle\Config\Target;
use Vinorcola\DistantVersionedAssetsBundle\Exception\InvalidManifestFileException;

class VersionModel
{
    private const CACHE_KEY_PREFIX = 'dvab.target.';

    /**
     * @var CacheItemPoolInterface
     */
    private $cache;

    /**
     * @var Config
     */
    private $config;

    /**
     * Extension constructor.
     *
     * @param CacheItemPoolInterface $cache
     * @param Config                 $config
     */
    public function __construct(CacheItemPoolInterface $cache, Config $config)
    {
        $this->cache = $cache;
        $this->config = $config;
    }

    /**
     * @param string      $file
     * @param string|null $target
     * @return string
     */
    public function getFileUrl(string $file, ?string $target = null): string
    {
        $target = $this->config->getTarget($target);
        $manifest = $this->getManifestFile($target);

        if (!key_exists($file, $manifest)) {
            return $target->getBaseUrl() . '/' . ltrim($file, '/');
        }

        return $target->getBaseUrl() . $manifest[$file];
    }

    /**
     * Get the target's manifest file content.
     *
     * @param Target $target
     * @return string[]
     */
    private function getManifestFile(Target $target): array
    {
        $cachedManifest = $this->cache->getItem(self::CACHE_KEY_PREFIX . $target->getKey());
        if (!$cachedManifest->isHit()) {
            $content = $this->fetchContent($target->getManifestUrl(), $target);

            $parsedContent = json_decode($content, true);
            if (!is_array($parsedContent)) {
                throw new InvalidManifestFileException(
                    'The manifest file content for target "' . $target->getKey() . '" is invalid. It should be a valid JSON object.'
                );
            }

            $cachedManifest->set($parsedContent);
            $cachedManifest->expiresAfter($target->getManifestTtl());
            $this->cache->save($cachedManifest);
        }

        return $cachedManifest->get();
    }

    /**
     * @param string $url
     * @param Target $target
     * @return string
     */
    public function fetchContent(string $url, Target $target): string
    {
        $connection = curl_init();
        curl_setopt($connection, CURLOPT_URL, $url);
        curl_setopt($connection, CURLOPT_RETURNTRANSFER, true);
        $content = curl_exec($connection);
        $httpStatus = curl_getinfo($connection, CURLINFO_HTTP_CODE);
        $error = curl_error($connection);
        curl_close($connection);

        if ($httpStatus !== 200) {
            throw new RuntimeException(
                'Could not fetch manifest file for target "' . $target->getKey() . '". ' . (
                    $httpStatus === 0 ?
                        $error :
                        'Get error ' . $httpStatus . '.'
                )
            );
        }

        return $content;
    }
}
