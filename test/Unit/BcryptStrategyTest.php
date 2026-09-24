<?php

declare(strict_types=1);

namespace Vanqard\PassMan\Test;

use PHPUnit\Framework\TestCase;
use Vanqard\PassMan\Exception\AlgorithmException;
use Vanqard\PassMan\Strategy\Bcrypt;

class BcryptStrategyTest extends TestCase
{
    public string $testPassword = "rasmus";

    public string $testHashTen = "";

    public string $testHashTwelve = "";

    public Bcrypt $bcryptStrategyTen;

    public Bcrypt $bcryptStrategyTwelve;

    protected function setUp(): void
    {
        // establish control values
        $this->testHashTen = password_hash($this->testPassword, PASSWORD_BCRYPT, ["cost" => 10]);
        $this->testHashTwelve = password_hash($this->testPassword, PASSWORD_BCRYPT, ["cost" => 12]);

        // Estable SUTs
        $this->bcryptStrategyTen = new Bcrypt(["cost" => 10]);
        $this->bcryptStrategyTwelve = new Bcrypt(["cost" => 12]);
    }

    public function testControlValuesValid(): void
    {
        $this->assertTrue(password_verify($this->testPassword, $this->testHashTen));
        $this->assertTrue(password_verify($this->testPassword, $this->testHashTwelve));
    }

    public function testStrategyImplementsInterface(): void
    {
        $this->assertInstanceOf('\Vanqard\PassMan\Strategy\HashingStrategy', $this->bcryptStrategyTen);
    }

    public function testStrategyReturnsCostValue(): void
    {
        $this->assertEquals(10, $this->bcryptStrategyTen->getOption('cost'));
        $this->assertEquals(12, $this->bcryptStrategyTwelve->getOption('cost'));
    }

    public function testDefaultStrategyInitialisesWithDefaults(): void
    {
        $newStrategy = new Bcrypt();
        $this->assertEquals(10, $newStrategy->getOption('cost'));
    }

    public function testStrategyGeneratesValidHash(): void
    {
        $hash = $this->bcryptStrategyTen->passwordHash($this->testPassword);
        $this->assertTrue(password_verify($this->testPassword, $hash));
    }

    public function testStrategySetOptionsIsFluent(): void
    {
        $this->assertInstanceOf(get_class($this->bcryptStrategyTen), $this->bcryptStrategyTen->setOptions(["cost" => 10]));
    }

    public function testStrategyAcceptsValidCostOption(): void
    {
        $this->bcryptStrategyTen->setOptions(["cost" => 11]);
        $this->assertEquals(11, $this->bcryptStrategyTen->getOption('cost'));
    }

    public function testStrategyRejectsInvalidCostOption(): void
    {
        $this->expectException(AlgorithmException::class);
        $this->expectExceptionCode(AlgorithmException::VPM_ALGORITHM_COST_OUT_OF_RANGE);

        $this->bcryptStrategyTen->setOptions(["cost" => 99999]);
    }

    public function testStrategySetsDefaultCostWhenNotSupplied(): void
    {
        $withCost = ["cost" => 16];
        $withoutCost = ["salt" => "ignored"];

        // First confirm valid is set to no default value
        $this->bcryptStrategyTen->setOptions($withCost);
        $this->assertEquals(16, $this->bcryptStrategyTen->getOption('cost'));

        // Second confirm default is set to default (10) when not supplied
        $this->bcryptStrategyTen->setOptions($withoutCost);
        $this->assertEquals(10, $this->bcryptStrategyTen->getOption('cost'));
    }

    public function testStrategyCannotYieldSaltOption(): void
    {
        $this->expectException(AlgorithmException::class);
        $this->expectExceptionCode(AlgorithmException::VPM_ALGORITHM_INVALID_OPTION);

        $this->bcryptStrategyTen->setOptions(["salt" => "mysecretsalt"]);
        $this->bcryptStrategyTen->getOption('salt');
    }

    public function testStrategyConfirmsNeedsRehash(): void
    {
        $this->assertFalse($this->bcryptStrategyTen->passwordNeedsRehash($this->testHashTen));
        $this->assertTrue($this->bcryptStrategyTwelve->passwordNeedsRehash($this->testHashTen));
    }
}
