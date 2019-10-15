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
     * @return string
     * @throws CannotAccessAwsException
     * @throws Exceptions\CannotPrepareAwsCognitoClient
     */
    public function createToken(): string
    {
        $cognito = Cognito::getInstance();

        return $cognito->createToken();
    }
}