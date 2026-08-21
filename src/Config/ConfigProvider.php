<?php

declare(strict_types=1);

namespace LesCache\Config;

use LesCache\ArrayCache;
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
     *
     * @psalm-suppress DeprecatedClass
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
                    // @phpstan-ignore-next-line
                    RequestCache::class => RequestCache::class,
                    ArrayCache::class => ArrayCache::class,
                ],
                'factories' => [
                    RedisCache::class => RedisCacheFactory::class,
                ],
            ],
        ];
    }
}
