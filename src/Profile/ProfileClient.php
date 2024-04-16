<?php

declare(strict_types=1);

namespace Serato\SwsSdk\Profile;

use Serato\SwsSdk\Sdk;
use Serato\SwsSdk\Client;

/**
 * Client used to interact with SWS Profile service.
 *
 * @method \Serato\SwsSdk\Result getUser(array $args)
 * @method \Serato\SwsSdk\Result updateUser(array $args)
 * @method \Serato\SwsSdk\Result getUserBetaProgram(array $args)
 * @method \Serato\SwsSdk\Result addUserBetaProgram(array $args)
 * @method \Serato\SwsSdk\Result validateAllUserBetaPrograms(array $args)
 * @method \Serato\SwsSdk\Result parterPromotionAddUser(array $args)
 */
class ProfileClient extends Client
{
    /**
     * Get the base URI for the Client
     *
     * @return string
     */
    #[\Override]
    public function getBaseUri(): string
    {
        return $this->config[Sdk::BASE_URI][Sdk::BASE_URI_PROFILE];
    }

    /**
     * Get an array of all valid commands for the Client.
     * The key of the array is command's name and the value is the Command
     * class name
     *
     * @return array<String, String>
     */
    #[\Override]
    public function getCommandMap(): array
    {
        return [
            # GET /users/{user_id}
            'GetUser'    => \Serato\SwsSdk\Profile\Command\UserGet::class,
            # PUT /users/{user_id}
            'UpdateUser' => \Serato\SwsSdk\Profile\Command\UserUpdate::class,
            # GET /users/{user_id}/betaprograms
            'GetUserBetaProgram' => \Serato\SwsSdk\Profile\Command\UserGetBetaProgram::class,
            # POST /users/{user_id}/betaprograms
            'AddUserBetaProgram' => \Serato\SwsSdk\Profile\Command\UserAddBetaProgram::class,
            # POST /users/{user_id}/betaprograms/validateall
            'ValidateAllUserBetaPrograms' => \Serato\SwsSdk\Profile\Command\UserValidateAllBetaPrograms::class,
            # POST /partnerpromotions/code
            'ParterPromotionAddUser' => \Serato\SwsSdk\Profile\Command\PartnerPromotionAddUser::class
        ];
    }
}
