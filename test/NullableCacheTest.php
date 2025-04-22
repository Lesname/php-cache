<?php
declare(strict_types=1);

namespace LesCacheTest;

use LesCache\NullableCache;
use PHPUnit\Framework\TestCase;

/**
 * @covers \LesCache\NullableCache
 */
final class NullableCacheTest extends TestCase
{
    public function testGet(): void
    {
        $cache = new NullableCache();

        self::assertSame('foo', $cache->get('fiz', 'foo'));
    }

    public function testSet(): void
    {
        $cache = new NullableCache();

        self::assertTrue($cache->set('fiz', 'biz'));
    }

    public function testDelete(): void
    {
        $cache = new NullableCache();

        self::assertTrue($cache->delete('fiz'));
    }

    public function testClear(): void
    {
        $cache = new NullableCache();

        self::assertTrue($cache->clear());
    }

    public function testHas(): void
    {
        $cache = new NullableCache();

        self::assertFalse($cache->has('fiz'));
    }
}
