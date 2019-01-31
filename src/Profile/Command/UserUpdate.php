<?php
declare(strict_types=1);

namespace Serato\SwsSdk\Profile\Command;

use Serato\SwsSdk\CommandBasicAuth;

/**
 * Updates a User in the SWS Profile service.
 *
 * Valid keys for the `$args` array provided to the constructor are:
 *
 * - `user_id`: (integer) Required. User ID.
 * - `global_contact_status`: (integer) Global contact status.
 * Must be one of 0 (Implicit Opt-out), 1 (Implicit Opt-in), 2 (Explicit Opt-out), 3 (Explicit Opt-in).
 * - `first_name`: (string) First Name.
 * - `last_name`: (string) Last Name.
 * - `locale`: (string) ISO 15897 locale string.
 * - `address_1`: (string) Address line 1.
 * - `address_2`: (string) Address line 2.
 * - `city`: (string) City.
 * - `region`: (string) Region, state or province.
 * - `postcode`: (string) Postcode
 * - `country`: (string) Country
 *
 * This command can be excuted on a `Serato\SwsSdk\Profile\ProfileClient` instance
 * using the `ProfileClient::updateUser` magic method.
 */
class UserUpdate extends CommandBasicAuth
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
        return 'PUT';
    }

    /**
     * {@inheritdoc}
     */
    public function getUriPath(): string
    {
        return '/api/v1/users/' . $this->commandArgs['user_id'];
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
            'user_id'               => ['type' => self::ARG_TYPE_INTEGER, 'required' => true],
            'global_contact_status' => ['type' => self::ARG_TYPE_INTEGER],
            'first_name'            => ['type' => self::ARG_TYPE_STRING],
            'last_name'             => ['type' => self::ARG_TYPE_STRING],
            'locale'                => ['type' => self::ARG_TYPE_STRING],
            'address_1'             => ['type' => self::ARG_TYPE_STRING],
            'address_2'             => ['type' => self::ARG_TYPE_STRING],
            'city'                  => ['type' => self::ARG_TYPE_STRING],
            'region'                => ['type' => self::ARG_TYPE_STRING],
            'postcode'              => ['type' => self::ARG_TYPE_STRING],
            'country'               => ['type' => self::ARG_TYPE_STRING],
        ];
    }
}
