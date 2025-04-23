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
    #[Override]
    public function get(string $key, mixed $default = null): mixed
    {
        return $default;
    }

    #[Override]
    public function set(string $key, mixed $value, null|int|DateInterval $ttl = null): bool
    {
        return true;
    }

    #[Override]
    public function delete(string $key): bool
    {
        return true;
    }

    #[Override]
    public function clear(): bool
    {
        return true;
    }

    #[Override]
    public function has(string $key): bool
    {
        return false;
    }
}
