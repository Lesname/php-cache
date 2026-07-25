<?php

declare(strict_types=1);

namespace LesCache\Redis;

use RuntimeException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Redis;
use RedisException;

final class RedisCacheFactory
{
    /**
     * @psalm-suppress InvalidArgument invalid stub
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws RedisException
     */
    public function __invoke(ContainerInterface $container): RedisCache
    {
        $config = $container->get('config');
        assert(is_array($config), 'Config needs to be an array');

        $settings = $config[RedisCache::class];
        assert(is_array($settings), 'Settings needs to be an array');

        $keyPrefix = $settings['keyPrefix'];
        assert(is_string($keyPrefix), 'Expected string for key prefix setting');

        $redis = new Redis();
        $redis->connect(
            $this->getHost($settings),
            $this->getPort($settings),
            context: $this->getContext($settings)
        );

        $redis->setOption(Redis::OPT_PREFIX, $keyPrefix);

        return new RedisCache($redis);
    }

    /**
     * @param array<mixed> $settings
     *
     * @psalm-pure
     */
    private function getHost(array $settings): string
    {
        if (!isset($settings['host'])) {
            throw new RuntimeException('Host not set');
        }

        if (!is_string($settings['host'])) {
            throw new RuntimeException('Host must be string');
        }

        return $settings['host'];
    }

    /**
     * @param array<mixed> $settings
     *
     * @psalm-pure
     */
    private function getPort(array $settings): int
    {
        if (isset($settings['port'])) {
            if (!is_int($settings['port'])) {
                throw new RuntimeException('Port must be int');
            }

            return $settings['port'];
        }

        return 6379;
    }

    /**
     * @param array<mixed> $settings
     *
     * @return array{auth: array{0: string | null, 1: string}}|null
     *
     * @psalm-pure
     */
    private function getContext(array $settings): ?array
    {
        $context = [];

        if (isset($settings['password']) && is_string($settings['password'])) {
            $context['auth'] = isset($settings['username']) && is_string($settings['username'])
                ? [$settings['username'], $settings['password']]
                : [null, $settings['password']];
        }

        return $context !== []
            ? $context
            : null;
    }
}
