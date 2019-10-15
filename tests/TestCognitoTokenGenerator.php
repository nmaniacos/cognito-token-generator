<?php

use CTG\CognitoTokenGenerator;
use PHPUnit\Framework\TestCase;

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

    public function testCanCreateTokenForCorrectEnvironmentVariables(): void
    {
        $this->assertNotEmpty($this->cognitoTokenGeneratorMock->createToken());
    }
}

