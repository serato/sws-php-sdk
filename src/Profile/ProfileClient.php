<?php

declare(strict_types=1);

namespace Serato\SwsSdk\Profile;

use Serato\SwsSdk\Sdk;
use Serato\SwsSdk\Client;

/**
 * Client used to interact with SWS Profile service.
 *
 * @method \Serato\SwsSdk\Result getMe()
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
    public function getCommandMap(): array
    {
        return [
            # GET /me
            'GetMe'    => '\\Serato\\SwsSdk\\Profile\\Command\\GetMe',
            # GET /users/{user_id}
            'GetUser'    => '\\Serato\\SwsSdk\\Profile\\Command\\UserGet',
            # PUT /users/{user_id}
            'UpdateUser' => '\\Serato\\SwsSdk\\Profile\\Command\\UserUpdate',
            # GET /users/{user_id}/betaprograms
            'GetUserBetaProgram' => '\\Serato\\SwsSdk\\Profile\\Command\\UserGetBetaProgram',
            # POST /users/{user_id}/betaprograms
            'AddUserBetaProgram' => '\\Serato\\SwsSdk\\Profile\\Command\\UserAddBetaProgram',
            # POST /users/{user_id}/betaprograms/validateall
            'ValidateAllUserBetaPrograms' => '\\Serato\\SwsSdk\\Profile\\Command\\UserValidateAllBetaPrograms',
            # POST /partnerpromotions/code
            'ParterPromotionAddUser' => '\\Serato\\SwsSdk\\Profile\\Command\\PartnerPromotionAddUser'
        ];
    }
}
