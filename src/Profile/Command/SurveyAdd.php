<?php
declare(strict_types=1);

namespace Serato\SwsSdk\Profile\Command;

use Serato\SwsSdk\CommandBasicAuth;

/**
 * Add a survey.
 *
 * Valid keys for the `$args` array provided to the constructor are:
 *
 * - `survey`: (string) .

 * This command can be excuted on a `Serato\SwsSdk\Profile\ProfileClient` instance
 * using the `ProfileClient::addSurvey` magic method.
 */
class SurveyAdd extends CommandBasicAuth
{
    /**
     * {@inheritdoc}
     */
    public function getBody()
    {
        return json_encode($this->commandArgs);
    }

    /**
     * {@inheritdoc}
     */
    public function getHttpMethod(): string
    {
        return 'POST';
    }

    /**
     * {@inheritdoc}
     */
    public function getUriPath(): string
    {
        return '/api/v1/survey';
    }

    /**
     * {@inheritdoc}
     */
    protected function setCommandRequestHeaders(): void
    {
        $this->setRequestHeader('Content-Type', 'application/json');
    }

    /**
     * {@inheritdoc}
     */
    protected function getArgsDefinition(): array
    {
        return [
            'survey'               => ['type' => self::ARG_TYPE_STRING, 'required' => true],
        ];
    }
}
