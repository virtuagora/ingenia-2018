<?php

namespace App\Service;

use App\Util\Exception\SystemException;
use Carbon\Carbon;

class IdentityService
{
    private $providers;

    public function __construct($providers)
    {
        $this->providers = $providers;
    }

    public function signIn($provider, $options)
    {
        if (!isset($this->providers[$provider])) {
            throw new SystemException('Identity provider not found');
        }
        $provider = $this->providers[$provider];
        $identifiers = $provider->makeIdentifiers($options);
        $user = $provider->retrieveUser($identifiers);
        if (is_null($user)) {
            $token = $provider->makeRegistrationToken($identifiers);
            if (is_null($token)) {
                return [
                    'status' => 'not-found',
                ];
            } else {
                return [
                    'status' => 'pending-user',
                    'token' => $token,
                ];
            }
        } else {
            if (isset($user->banExpiration)) {
                if (Carbon::now()->lt($user->banExpiration)) {
                    return [
                        'status' => 'banned',
                    ];
                } else {
                    $user->banExpiration = null;
                    $user->save();
                }
            }
        }
        return [
            'status' => 'success',
            'user' => $user,
        ];
    }
}
