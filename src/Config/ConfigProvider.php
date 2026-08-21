<?php

declare(strict_types=1);

namespace LesCache\Config;

use LesCache\RequestCache;
use LesCache\NullableCache;
use LesCache\Redis\RedisCache;
use Psr\SimpleCache\CacheInterface;
use LesCache\Redis\RedisCacheFactory;

/**
 * @psalm-immutable
 */
final class ConfigProvider
{
    /**
     * @return array<string, mixed>
     *
     * @psalm-pure
     */
    public function __invoke(): array
    {
        return [
            'dependencies' => [
                'aliases' => [
                    CacheInterface::class => RedisCache::class,
                ],
                'invokables' => [
                    NullableCache::class => NullableCache::class,
                    RequestCache::class => RequestCache::class,
                ],
                'factories' => [
                    RedisCache::class => RedisCacheFactory::class,
                ],
            ],
        ];
    }
}
