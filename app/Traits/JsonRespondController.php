<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Validation\Validator;

trait JsonRespondController
{
    /**
     * @var int
     */
    protected $httpStatusCode = 200;

    /**
     * @var string
     */
    protected $errorMessage;

    /**
     * Get HTTP status code of the response.
     *
     * @return int
     */
    public function getHTTPStatusCode()
    {
        return $this->httpStatusCode;
    }

    /**
     * Set HTTP status code of the response.
     *
     * @param int $statusCode
     *
     * @return $this
     */
    public function setHTTPStatusCode($statusCode)
    {
        $this->httpStatusCode = $statusCode;

        return $this;
    }

    /**
     * Get error code of the response.
     *
     * @return string
     */
    public function getErrorMessage()
    {
        return $this->errorMessage;
    }

    /**
     * Set error code of the response.
     *
     * @param string $errorMessage
     *
     * @return $this
     */
    public function setErrorMessage($errorMessage)
    {
        $this->errorMessage = $errorMessage;

        return $this;
    }

    /**
     * Sends a JSON to the consumer.
     *
     * @param array $data
     *
     * @return JsonResponse
     */
    public function respond($data)
    {
        return response()->json($data, $this->getHTTPStatusCode());
    }

    /**
     * Sends a response not found (404) to the request.
     * Error Code = 31.
     *
     * @return JsonResponse
     */
    public function respondNotFound()
    {
        return $this->setHTTPStatusCode(404)
            ->setErrorMessage('Resource not found.')
            ->respondWithError();
    }

    /**
     * Sends an error when the validator failed.
     *
     * @param Validator $validator
     *
     * @return JsonResponse
     */
    public function respondValidatorFailed(Validator $validator)
    {
        return $this->setHTTPStatusCode(422)
            ->setErrorMessage('Validator failed.')
            ->respondWithError($validator->errors()->all());
    }

    /**
     * Sends an error when the query didn't have the right parameters for
     * creating an object.
     *
     * @param string $message
     *
     * @return JsonResponse
     */
    public function respondNotTheRightParameters($message = null)
    {
        return $this->setHTTPStatusCode(500)
            ->setErrorMessage('Internal server error.')
            ->respondWithError($message);
    }

    /**
     * Sends a response invalid query (http 500) to the request.
     *
     * @param string $message
     *
     * @return JsonResponse
     */
    public function respondInvalidQuery($message = null)
    {
        return $this->setHTTPStatusCode(500)
            ->setErrorMessage('Invalid query.')
            ->respondWithError($message);
    }

    /**
     * Sends a response unauthorized (401) to the request.
     *
     * @param string $message
     *
     * @return JsonResponse
     */
    public function respondUnauthorized($message = null)
    {
        return $this->setHTTPStatusCode(401)
            ->setErrorMessage('Action requires user authentication.')
            ->respondWithError($message);
    }

    /**
     * Sends a response with error.
     *
     * @param array|string|null $message
     *
     * @return JsonResponse
     */
    public function respondWithError($message = null)
    {
        return $this->respond([
            'error' => [
                'message' => $message,
                'error_message' => $this->getErrorMessage(),
            ],
        ]);
    }

    /**
     * Sends a response that the object has been deleted, and also indicates
     * the id of the object that has been deleted.
     *
     * @param int $id
     *
     * @return JsonResponse
     */
    public function respondObjectDeleted($id)
    {
        return $this->respond([
            'deleted' => true,
            'id' => $id,
        ]);
    }
}
