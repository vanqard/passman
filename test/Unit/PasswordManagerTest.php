<?php

declare(strict_types=1);

namespace Vanqard\PassMan\Test;

use PHPUnit\Framework\TestCase;
use Vanqard\PassMan\PasswordManager;
use Vanqard\PassMan\Strategy\Algorithm;

class PasswordManagerTest extends TestCase
{
    public PasswordManager $sut;

    public string $testPassword = "rasmus";

    public string $testHashTen = "";

    public string $testHashTwelve = "";

    protected function setUp(): void
    {
        // establish control values
        $this->testHashTen = password_hash($this->testPassword, PASSWORD_BCRYPT, ["cost" => 10]);
        $this->testHashTwelve = password_hash($this->testPassword, PASSWORD_BCRYPT, ["cost" => 12]);
    }

    public function initialiseSUT(array $options = ["cost" => 10]): void
    {
        $this->sut = PasswordManager::factory(Algorithm::Bcrypt, $options);
    }

    public function testFactoryValidCallYieldsInstance(): void
    {
        $this->assertInstanceOf(PasswordManager::class, PasswordManager::factory());
    }

    public function testInstanceYieldsValidHash(): void
    {
        $this->initialiseSUT();

        $hash = $this->sut->passwordHash($this->testPassword);

        $this->assertTrue(password_verify($this->testPassword, $hash));
    }

    public function testInstanceCanVerifyValidHash(): void
    {
        $this->initialiseSut();
        $this->assertTrue($this->sut->passwordVerify($this->testPassword, $this->testHashTen));
    }

    public function testInstanceRejectsInvalidHash(): void
    {
        $this->initialiseSut();
        $this->assertFalse($this->sut->passwordVerify($this->testPassword, 'Hello World'));
    }

    public function testInstanceCanConfirmNeedsRehash(): void
    {
        $this->initialiseSUT(["cost" => 12]);
        $this->assertTrue($this->sut->passwordNeedsRehash($this->testHashTen));
    }

    public function testInstanceConfirmsNoRehash(): void
    {
        $this->initialiseSUT(["cost" => 10]);
        $this->assertFalse($this->sut->passwordNeedsRehash($this->testHashTen));
    }

    public function testInstanceGetInfoReturnsArray(): void
    {
        $this->initialiseSUT();
        $hashInfo = $this->sut->passwordGetInfo($this->testHashTen);

        $this->assertTrue(is_array($hashInfo));
        $this->assertArrayHasKey('algo', $hashInfo);
        $this->assertArrayHasKey('algoName', $hashInfo);
    }

    public function testIsStubable(): void
    {
        $stubbedHashValue = 'stubbedhashvalue';

        $passwordManagerStub = $this->getMockBuilder(PasswordManager::class)
                                    ->disableOriginalConstructor()
                                    ->getMock();

        $passwordManagerStub->method('passwordHash')
                            ->willReturn($stubbedHashValue);

        $this->assertEquals($stubbedHashValue, $passwordManagerStub->passwordHash($stubbedHashValue));
    }
}
