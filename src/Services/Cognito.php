<?php

namespace CTG\Services;

use CTG\Exceptions\CannotAccessAwsException;
use CTG\Exceptions\CannotPrepareAwsCognitoClient;
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
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $password;

    /**
     * @var Cognito
     */
    private static $instance = null;

    /**
     * Cognito constructor.
     *
     * @throws CannotPrepareAwsCognitoClient
     */
    public function __construct()
    {
        $this->poolId = getenv('COGNITO_POOL_ID');
        $this->clientId = getenv('COGNITO_APP_CLIENT_ID');
        $this->email = getenv('SYSTEM_USER_EMAIL');
        $this->password = getenv('SYSTEM_USER_PASSWORD');

        $this->client = $this->prepareCognitoClient();
    }

    /**
     * @return CognitoIdentityProviderClient
     * @throws CannotPrepareAwsCognitoClient
     */
    private function prepareCognitoClient()
    {
        if (empty($this->poolId) || empty($this->clientId) || empty(getenv('AWS_ACCESS_KEY_ID')) || empty(getenv('AWS_SECRET_ACCESS_KEY'))) {
            throw new CannotPrepareAwsCognitoClient('Some envariable variables are missing: AWS_ACCESS_KEY_ID, AWS_SECRET_ACCESS_KEY or Cognito Pool credentials');
        }

        return new CognitoIdentityProviderClient([
            'region' => 'eu-central-1',
            'version' => '2016-04-18',
        ]);
    }

    /**
     * @return string
     * @throws CannotAccessAwsException
     */
    public function createToken()
    {
        try {

            $result = $this->client->adminInitiateAuth([
                'AuthFlow' => 'ADMIN_NO_SRP_AUTH',
                'UserPoolId' => (string) $this->poolId,
                'ClientId' => (string) $this->clientId,
                'AuthParameters' => [
                    'USERNAME' => $this->email,
                    'PASSWORD' => $this->password
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
     * @throws CannotPrepareAwsCognitoClient
     */
    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new Cognito();
        }

        return self::$instance;
    }
}