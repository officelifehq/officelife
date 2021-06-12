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

        ServiceQueueTester::dispatch();

        $this->assertTrue(ServiceQueueTester::$executed);
        $this->assertFalse(ServiceQueueTester::$failed);
    }

    /** @test */
    public function it_run_a_service_sync(): void
    {
        ServiceQueueTester::dispatchSync();

        $this->assertTrue(ServiceQueueTester::$executed);
        $this->assertFalse(ServiceQueueTester::$failed);
    }

    /** @test */
    public function it_run_a_service_which_failed(): void
    {
        $this->expectException(\Exception::class);
        try {
            ServiceQueueTester::dispatchSync(['throw' => true]);
        } finally {
            $this->assertTrue(ServiceQueueTester::$executed);
            $this->assertTrue(ServiceQueueTester::$failed);
        }
    }

    /** @test */
    public function service_is_not_run_if_queue_set(): void
    {
        config(['queue.default' => 'database']);

        ServiceQueueTester::dispatch(['throw' => true]);

        $this->assertFalse(ServiceQueueTester::$executed);
        $this->assertFalse(ServiceQueueTester::$failed);
    }

    /** @test */
    public function it_fails_if_the_service_is_not_queuable(): void
    {
        $this->expectException(\Exception::class);
        new ServiceQueue(new AddUserToCompany);
    }
}
