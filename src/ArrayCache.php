<?php

declare(strict_types=1);

namespace LesCache;

use Override;
use DateInterval;
use DateTime;

/**
 * Cache within an array
 */
final class ArrayCache extends AbstractCache
{
    /** @var array<string, array{expire: int | null, value: mixed}> */
    private array $cache = [];

    #[Override]
    public function get(string $key, mixed $default = null): mixed
    {
        return $this->has($key)
            ? $this->cache[$key]['value']
            : $default;
    }

    #[Override]
    public function set(string $key, mixed $value, \DateInterval|int|null $ttl = null): bool
    {
        if ($ttl instanceof DateInterval) {
            $expire = (new DateTime())->add($ttl)->getTimestamp();
        } elseif (is_int($ttl)) {
            $expire = time() + $ttl;
        } else {
            $expire = null;
        }

        $this->cache[$key] = [
            'expire' => $expire,
            'value' => $value,
        ];

        return true;
    }

    /**
     * @psalm-external-mutation-free
     */
    #[Override]
    public function delete(string $key): bool
    {
        unset($this->cache[$key]);

        return true;
    }

    /**
     * @psalm-external-mutation-free
     */
    #[Override]
    public function clear(): bool
    {
        $this->cache = [];

        return true;
    }

    #[Override]
    public function has(string $key): bool
    {
        if (!array_key_exists($key, $this->cache)) {
            return false;
        }

        if (is_int($this->cache[$key]['expire']) && $this->cache[$key]['expire'] <= time()) {
            $this->delete($key);

            return false;
        }

        return true;
    }
}
