<?php

declare(strict_types=1);

namespace Atlas\Connectors\Nexus\Tests\Unit\Exceptions;

use Atlas\Connectors\Nexus\Exceptions\AuthenticationException;
use Atlas\Connectors\Nexus\Exceptions\ApiException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(AuthenticationException::class)]
class AuthenticationExceptionTest extends TestCase
{
    public function testAuthenticationExceptionIsAnApiException(): void
    {
        $exception = new AuthenticationException('Unauthorized', 401);

        $this->assertInstanceOf(ApiException::class, $exception);
        $this->assertEquals('Unauthorized', $exception->getMessage());
        $this->assertEquals(401, $exception->getCode());
    }
}
