<?php

namespace Serato\SwsSdk\Exception;

use Serato\SwsSdk\Result;
use GuzzleHttp\Exception\BadResponseException;
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
    /** @var Result  */
    private $result;

    public function __construct(BadResponseException $e)
    {
        $this->result = new Result($e->getResponse());

        $msg = "`" . $this->getHttpResponseName() . "` returned from `" .
                $e->getRequest()->getUri() . "`\n\n" .
                $this->getResultMessage();

        parent::__construct(trim($msg), $this->getResultCode());
    }

    abstract protected function getHttpResponseName();

    abstract protected function getResultMessage();

    abstract protected function getResultCode();

    /**
     * Return the Result object created from the error response.
     *
     * @return Result
     */
    public function getResult()
    {
        return $this->result;
    }
}
