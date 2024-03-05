<?php

namespace App\Auth;

use App\Entity\User;
use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Token\AccessToken;
use Psr\Http\Message\ResponseInterface;

final class AuthProvider extends AbstractProvider
{
    public function getBaseAuthorizationUrl(): string
    {
        return 'http://auth2.localhost/authorize';
    }

    public function getBaseAccessTokenUrl(array $params): string
    {
        return 'http://auth2/token';
    }

    public function getResourceOwnerDetailsUrl(AccessToken $token): string
    {
        return 'http://auth2/api/user';
    }

    protected function getDefaultScopes(): array
    {
        return ['uuid', 'email'];
    }

    protected function checkResponse(ResponseInterface $response, $data)
    {
    }

    protected function createResourceOwner(array $response, AccessToken $token)
    {
        return new User();
    }
}
