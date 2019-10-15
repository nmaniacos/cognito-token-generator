<?php

namespace CTG;

use CTG\Services\Cognito;
use CTG\Exceptions\CannotAccessAwsException;

/**
 * Class CognitoTokenGenerator
 *
 * @package CTG
 */
trait CognitoTokenGenerator
{
    /**
     * @var Cognito
     */
    protected $cognito;

    /**
     * CognitoTokenGenerator constructor.
     */
    public function __construct()
    {
        $this->cognito = Cognito::getInstance();
    }

    /**
     * @param string $email
     * @param string $password
     * @return string
     * @throws CannotAccessAwsException
     */
    public function generate(string $email, string $password): string
    {
        return $this->cognito->createToken($email, $password);
    }
}