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
 * - `country_code`: (string) Country Code (Alpha-2)
 * - `twitch_access_token`: (string) A Twitch Extension access token
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
        return '/api/v1/users/' . self::toString($this->commandArgs['user_id']);
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
            'global_contact_status' => ['type' => self::ARG_TYPE_INTEGER, 'required' => false],
            'first_name'            => ['type' => self::ARG_TYPE_STRING, 'required' => false],
            'last_name'             => ['type' => self::ARG_TYPE_STRING, 'required' => false],
            'locale'                => ['type' => self::ARG_TYPE_STRING, 'required' => false],
            'address_1'             => ['type' => self::ARG_TYPE_STRING, 'required' => false],
            'address_2'             => ['type' => self::ARG_TYPE_STRING, 'required' => false],
            'city'                  => ['type' => self::ARG_TYPE_STRING, 'required' => false],
            'region'                => ['type' => self::ARG_TYPE_STRING, 'required' => false],
            'postcode'              => ['type' => self::ARG_TYPE_STRING, 'required' => false],
            'country_code'          => ['type' => self::ARG_TYPE_STRING, 'required' => false],
            'twitch_access_token'   => ['type' => self::ARG_TYPE_STRING, 'required' => false],
        ];
    }
}
