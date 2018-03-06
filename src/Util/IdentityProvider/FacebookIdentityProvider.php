<?php

namespace App\Util\IdentityProvider;

use App\Util\Exception\AppException;

class FacebookIdentityProvider
{
    protected $db;
    protected $facebook;

    public function __construct($db, $facebook)
    {
        $this->db = $db;
        $this->facebook = $facebook;
    }

    public function makeIdentifiers($options)
    {
        $helper = $this->facebook->getRedirectLoginHelper();
        $accessToken = $helper->getAccessToken();
        if (!isset($accessToken)) {
            if ($helper->getError()) {
                $msg = 'Error: '.$helper->getError().'\n';
                $msg += 'Error Code: '.$helper->getErrorCode().'\n';
                $msg += 'Error Reason: '.$helper->getErrorReason().'\n';
                $msg += 'Error Description: '.$helper->getErrorDescription();
                throw new AppException($msg, 401);
            }
            throw new AppException('Bad request.', 400);
        }
        $response = $this->facebook->get('/me?fields=id,name,email', $accessToken);
        $userNode = $response->getGraphUser();
        return [
            'facebook' => $userNode['id'],
        ];
    }

    public function retrieveUser($data)
    {
        return $this->db->query('App:User')->where('facebook', $data['facebook'])->first();
    }

    public function makeRegistrationToken($data)
    {
        return null;
    }
}
