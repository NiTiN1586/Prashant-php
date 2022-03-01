<?php

declare(strict_types=1);

namespace App\Tests\Unit\Utils;

use Jagaad\WitcherApi\Utils\StringUtils;
use PHPUnit\Framework\TestCase;

class StringUtilsTest extends TestCase
{
    /**
     * @dataProvider getHandleToName
     *
     * @param string $request
     * @param string $response
     */
    public function testConvertNameToHandle(string $request, string $response): void
    {
        self::assertSame($response, StringUtils::convertNameToHandle($request));
    }

    /**
     * @return array<int, mixed>
     */
    public function getHandleToName(): array
    {
        return [
            [
                'test name',
                'TEST_NAME',
            ],
            [
                'te$stname',
                'TE_STNAME',
            ],
        ];
    }
}
