<?php

use CTG\CognitoTokenGenerator;
use PHPUnit\Framework\TestCase;
use CTG\Exceptions\CannotAccessAwsException;

/**
 * Class TestCognitoTokenGenerator
 */
final class TestCognitoTokenGenerator extends TestCase
{
    /**
     * @var object
     */
    protected $cognitoTokenGeneratorMock;

    /**
     * TestCognitoTokenGenerator constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->cognitoTokenGeneratorMock = new class {
            use CognitoTokenGenerator;
        };
    }

    public function testExceptionForInvalidEmailAndPassword(): void
    {
        $this->expectException(CannotAccessAwsException::class);
        $this->cognitoTokenGeneratorMock->generate('random-email', 'random-password');
    }

    public function testTokenForCorrectEmailAndPassword(): void
    {
        $this->assertNotEmpty(
            $this->cognitoTokenGeneratorMock->generate(
                getenv('AUTH_USER_EMAIL'),
                getenv('AUTH_USER_PASSWORD')
            )
        );
    }
}

