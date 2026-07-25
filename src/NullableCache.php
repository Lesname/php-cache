<?php

declare(strict_types=1);

namespace LesCache;

use Override;
use DateInterval;

/**
 * Cache that does nothing
 */
final class NullableCache extends AbstractCache
{
    /**
     * @psalm-pure
     */
    #[Override]
    public function get(string $key, mixed $default = null): mixed
    {
        return $default;
    }

    /**
     * @psalm-pure
     */
    #[Override]
    public function set(string $key, mixed $value, null|int|DateInterval $ttl = null): bool
    {
        return true;
    }

    /**
     * @psalm-pure
     */
    #[Override]
    public function delete(string $key): bool
    {
        return true;
    }

    /**
     * @psalm-pure
     */
    #[Override]
    public function clear(): bool
    {
        return true;
    }

    /**
     * @psalm-pure
     */
    #[Override]
    public function has(string $key): bool
    {
        return false;
    }
}
