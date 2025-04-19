<?php

/**
 * #==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#
 * @package     ZCode
 * @author      Miguel92
 * @copyright   2024 - 2025
 * @version     3.1.18
 * @link        https://zcodev.alwaysdata.net/ (DEMO)
 * @link        https://github.com/ScriptParaPHPost/zcode (Repositorio Github)
 * @link        https://sourceforge.net/projects/zcodephp/ (Repositorio Sourceforge)
 * #==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#
**/

namespace app\services;

use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Contracts\Cache\ItemInterface;

class CacheService
{
    private FilesystemAdapter $cache;
    private int $ttl;

    public function __construct(string $namespace = 'zcode', int $ttl = 300, string $path = TS_STORAGE . 'queries')
    {
        $this->ttl = $ttl;
        $this->cache = new FilesystemAdapter($namespace, $ttl, $path);
    }

    /**
     * Genera o recupera del caché una consulta de posts.
     */
    public function getCached(string $baseKey, array $params, callable $callback, ?callable $changeDetector = null)
    {
        // Armar clave de caché segura y única
        $keyParts = array_merge([$baseKey], $params);
        if ($changeDetector) {
            $state = call_user_func($changeDetector);
            $keyParts[] = $state;
        }

        $cacheKey = md5(implode('_', $keyParts));

        return $this->cache->get($cacheKey, function (ItemInterface $item) use ($callback) {
            $item->expiresAfter($this->ttl);
            return call_user_func($callback);
        });
    }

    public function clear(string $baseKey, array $params = [], ?callable $changeDetector = null): void
    {
        $keyParts = array_merge([$baseKey], $params);
        if ($changeDetector) {
            $state = call_user_func($changeDetector);
            $keyParts[] = $state;
        }

        $cacheKey = md5(implode('_', $keyParts));
        $this->cache->deleteItem($cacheKey);
    }
}
