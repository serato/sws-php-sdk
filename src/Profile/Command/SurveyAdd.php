<?php
declare(strict_types=1);

namespace Serato\SwsSdk\Profile\Command;

use Serato\SwsSdk\CommandBasicAuth;

/**
 * Add a User in the Beta program.
 *
 * Valid keys for the `$args` array provided to the constructor are:
 *
 * - `user_id`: (integer) Optional. User ID.
 * - `survey_name`: (string) Required. Name of survey being submitted.
 * - `general_feedback`: (string) Required. Survey feedback.

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
        $args = $this->commandArgs;
        unset($args['user_id']);
        return $this->arrayToFormUrlEncodedBody($args);
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
        $this->setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    }

    /**
     * {@inheritdoc}
     */
    protected function getArgsDefinition(): array
    {
        return [
            'user_id'               => ['type' => self::ARG_TYPE_INTEGER, 'required' => false],
            'survey_name'       => ['type' => self::ARG_TYPE_STRING, 'required' => true],
            'general_feedback'       => ['type' => self::ARG_TYPE_STRING, 'required' => true]
        ];
    }
}
