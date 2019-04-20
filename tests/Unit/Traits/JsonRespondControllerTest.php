<?php

namespace Tests\Unit\Traits;

use Tests\TestCase;
use Illuminate\Http\JsonResponse;
use App\Traits\JsonRespondController;
use Illuminate\Support\Facades\Validator;

class JsonRespondControllerTest extends TestCase
{
    /** @test */
    public function it_gets_the_http_status_code()
    {
        $trait = $this->getMockForTrait(JsonRespondController::class);

        $this->assertEquals(
            200,
            $trait->getHTTPStatusCode()
        );
    }

    /** @test */
    public function it_sets_the_http_status_code()
    {
        $trait = $this->getMockForTrait(JsonRespondController::class);

        $object = $trait->setHTTPStatusCode(300);

        $this->assertEquals(
            300,
            $trait->getHTTPStatusCode()
        );
    }

    /** @test */
    public function it_gets_the_error_message()
    {
        $trait = $this->getMockForTrait(JsonRespondController::class);

        $this->assertNull($trait->getErrorMessage());
    }

    /** @test */
    public function it_sets_the_error_message()
    {
        $trait = $this->getMockForTrait(JsonRespondController::class);

        $object = $trait->setErrorMessage('error');

        $this->assertEquals(
            'error',
            $trait->getErrorMessage()
        );
    }

    /** @test */
    public function it_responds()
    {
        $trait = $this->getMockForTrait(JsonRespondController::class);
        $array = ['test'];

        $response = $trait->respond($array);

        $this->assertInstanceOf(
            JsonResponse::class,
            $response
        );

        $this->assertEquals(
            200,
            $response->status()
        );
    }

    /** @test */
    public function it_responds_not_found()
    {
        $trait = $this->getMockForTrait(JsonRespondController::class);
        $array = ['test'];

        $response = $trait->respondNotFound($array);

        $this->assertEquals(
            404,
            $response->status()
        );

        $this->assertEquals(
            '{"error":{"message":null,"error_message":"Resource not found."}}',
            $response->content()
        );
    }

    /** @test */
    public function it_responds_validator_failed()
    {
        $trait = $this->getMockForTrait(JsonRespondController::class);

        $validator = Validator::make(
            ['name' => null],
            ['name' => 'required']
        );

        $response = $trait->respondValidatorFailed($validator);

        $this->assertEquals(
            422,
            $response->status()
        );
    }

    /** @test */
    public function it_responds_not_right_parameters()
    {
        $trait = $this->getMockForTrait(JsonRespondController::class);
        $array = ['test'];

        $response = $trait->respondNotTheRightParameters($array);

        $this->assertEquals(
            500,
            $response->status()
        );

        $this->assertEquals(
            '{"error":{"message":["test"],"error_message":"Internal server error."}}',
            $response->content()
        );
    }

    /** @test */
    public function it_responds_invalid_query()
    {
        $trait = $this->getMockForTrait(JsonRespondController::class);
        $array = ['test'];

        $response = $trait->respondInvalidQuery($array);

        $this->assertEquals(
            500,
            $response->status()
        );

        $this->assertEquals(
            '{"error":{"message":["test"],"error_message":"Invalid query."}}',
            $response->content()
        );
    }

    /** @test */
    public function it_responds_unauthorized()
    {
        $trait = $this->getMockForTrait(JsonRespondController::class);
        $array = ['test'];

        $response = $trait->respondUnauthorized($array);

        $this->assertEquals(
            401,
            $response->status()
        );

        $this->assertEquals(
            '{"error":{"message":["test"],"error_message":"Action requires user authentication."}}',
            $response->content()
        );
    }

    /** @test */
    public function it_responds_with_error()
    {
        $trait = $this->getMockForTrait(JsonRespondController::class);

        $response = $trait->respondWithError('error');

        $this->assertEquals(
            '{"error":{"message":"error","error_message":null}}',
            $response->content()
        );
    }

    /** @test */
    public function it_responds_deleted_object()
    {
        $trait = $this->getMockForTrait(JsonRespondController::class);

        $response = $trait->respondObjectDeleted(2);

        $this->assertEquals(
            '{"deleted":true,"id":2}',
            $response->content()
        );
    }
}
