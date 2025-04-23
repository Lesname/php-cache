<?php
declare(strict_types=1);

namespace LesCache\Redis;

use Override;
use DateInterval;
use DateTimeImmutable;
use LesCache\AbstractCache;
use Redis;

final class RedisCache extends AbstractCache
{
    public function __construct(private readonly Redis $redis)
    {}

    #[Override]
    public function get(string $key, mixed $default = null): mixed
    {
        $value = $this->redis->get($key);

        if (!is_string($value)) {
            return $default;
        }

        return unserialize($value);
    }

    #[Override]
    public function set(string $key, mixed $value, DateInterval|int|null $ttl = null): bool
    {
        if (isset($ttl)) {
            if ($ttl instanceof DateInterval) {
                $now = new DateTimeImmutable();
                $expires = $now->add($ttl);

                $ttl = $expires->getTimestamp() - $now->getTimestamp();
            }

            return $this->redis->setEx($key, $ttl, serialize($value)) === true;
        }

        return $this->redis->set($key, serialize($value)) === true;
    }

    #[Override]
    public function delete(string $key): bool
    {
        $this->redis->del($key);

        return true;
    }

    #[Override]
    public function clear(): bool
    {
        return $this->redis->flushDB();
    }

    #[Override]
    public function has(string $key): bool
    {
        return $this->redis->exists($key) === 1;
    }
}
