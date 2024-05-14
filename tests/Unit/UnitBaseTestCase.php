<?php
declare(strict_types=1);

namespace Tests\Unit;

use Tests\TestCase;
use Tests\Unit\Resources\AuthUserMock;

class UnitBaseTestCase extends TestCase
{
    public static function getAuthUserMock(): AuthUserMock
    {
        return new AuthUserMock();
    }
}
