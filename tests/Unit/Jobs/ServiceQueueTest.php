<?php

namespace Tests\Unit\Jobs;

use Tests\TestCase;
use App\Jobs\ServiceQueue;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\Company\Adminland\Company\AddUserToCompany;

class ServiceQueueTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_run_a_service_ok(): void
    {
        config(['queue.default' => 'sync']);

        ServiceQueueTester::dispatchSync();

        $this->assertTrue(ServiceQueueTester::$executed);
        $this->assertFalse(ServiceQueueTester::$failed);
    }

    /** @test */
    public function it_run_a_service_which_failed(): void
    {
        config(['queue.default' => 'sync']);

        ServiceQueueTester::dispatchSync(['throw' => true]);

        $this->assertTrue(ServiceQueueTester::$executed);
        $this->assertTrue(ServiceQueueTester::$failed);
    }

    /** @test */
    public function it_fails_if_the_service_is_not_queuable(): void
    {
        $this->expectException(\Exception::class);
        new ServiceQueue(new AddUserToCompany);
    }
}
