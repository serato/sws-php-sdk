<?php

declare(strict_types=1);

namespace Serato\SwsSdk\Exception;

use Serato\SwsSdk\Result;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Psr7\Response;
use RuntimeException;

/**
 * An exception raised when a request to an SWS web service results in a
 * non-20x HTTP response.
 *
 * This exception type is raised in response to trapping a `GuzzleHttp\Exception\BadResponseException`
 * exception.
 */
abstract class ResponseException extends RuntimeException
{
    /** @var Result<String, mixed>  */
    private $result;

    public function __construct(BadResponseException $e)
    {
        $response = $e->getResponse();

        # Should never happen
        if ($response === null) {
            $response = new Response();
        }

        $this->result = new Result($response);

        $msg = "`" . $this->getHttpResponseName() . "` returned from `" .
                $e->getRequest()->getUri() . "`\n\n" .
                $this->getResultMessage();

        parent::__construct(trim($msg), $this->getResultCode());
    }

    abstract protected function getHttpResponseName(): string;

    abstract protected function getResultMessage(): string;

    abstract protected function getResultCode(): int;

    /**
     * Return the Result object created from the error response.
     *
     * @return Result<String, mixed>
     */
    public function getResult(): Result
    {
        return $this->result;
    }
}
