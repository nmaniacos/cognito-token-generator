<?php

namespace CTG\Services;

use CTG\Exceptions\CannotAccessAwsException;
use Aws\CognitoIdentityProvider\CognitoIdentityProviderClient;
use Aws\CognitoIdentityProvider\Exception\CognitoIdentityProviderException;

/**
 * Class Cognito
 *
 * @package CTG\Services
 */
class Cognito
{
    /**
     * The cognito client
     *
     * @var CognitoIdentityProviderClient
     */
    private $client;

    /**
     * @var string The cognito pool id
     */
    private $poolId;

    /**
     * @var string The cognito client id
     */
    private $clientId;

    /**
     * @var Cognito
     */
    private static $instance = null;

    /**
     * Cognito constructor.
     */
    public function __construct()
    {
        $this->client = $this->prepareCognitoClient();
        $this->poolId = getenv('COGNITO_POOL_ID');
        $this->clientId = getenv('COGNITO_APP_CLIENT_ID');
    }

    /**
     * @return CognitoIdentityProviderClient
     */
    private function prepareCognitoClient()
    {
        return new CognitoIdentityProviderClient([
            'region' => 'eu-central-1',
            'version' => '2016-04-18',
        ]);
    }

    /**
     * @param string $email
     * @param string $password
     * @return string
     * @throws CannotAccessAwsException
     */
    public function createToken($email, $password)
    {
        try {

            $result = $this->client->adminInitiateAuth([
                'AuthFlow' => 'ADMIN_NO_SRP_AUTH',
                'UserPoolId' => (string) $this->poolId,
                'ClientId' => (string) $this->clientId,
                'AuthParameters' => [
                    'USERNAME' => $email,
                    'PASSWORD' => $password
                ]
            ]);

            if (isset($result['AuthenticationResult'])) {
                return $result['AuthenticationResult']['IdToken'];
            }

            throw new CannotAccessAwsException('Cannot login, no AuthenticationResult');

        } catch (CognitoIdentityProviderException $e) {
            throw new CannotAccessAwsException($e->getAwsErrorMessage());
        }
    }

    /**
     * @return Cognito
     */
    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new Cognito();
        }

        return self::$instance;
    }
}