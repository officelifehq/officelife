<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\BaseService;

class BaseServiceTest extends TestCase
{
    /** @test */
    public function it_returns_an_empty_rule_array()
    {
        $stub = $this->getMockForAbstractClass(BaseService::class);

        $this->assertInternalType(
            'array',
            $stub->rules()
        );
    }
}
