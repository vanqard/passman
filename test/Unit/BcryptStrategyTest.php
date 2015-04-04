<?php
namespace Vanqard\PassMan\Test;

use Vanqard\PassMan\Strategy\Bcrypt;

class BcryptStrategyTest extends \PHPUnit_Framework_TestCase
{
    
    public $testPassword = "rasmus";
    
    public $testHashTen = "";
    
    public $testHashTwelve = "";
    
    public $bcryptStrategyTen;
    
    public $bcryptStrategyTwelve;
    
    
    public function setUp()
    {
        // establish control values
        $this->testHashTen = password_hash($this->testPassword, PASSWORD_BCRYPT, array("cost" => 10));
        $this->testHashTwelve = password_hash($this->testPassword, PASSWORD_BCRYPT, array("cost" => 12));
        
        // Estable SUTs
        $this->bcryptStrategyTen = new Bcrypt(array("cost" => 10));
        $this->bcryptStrategyTwelve = new Bcrypt(array("cost" => 12));
    }
    
    public function testControlValuesValid()
    {
        $this->assertTrue(password_verify($this->testPassword, $this->testHashTen));
        $this->assertTrue(password_verify($this->testPassword, $this->testHashTwelve));
    }
    
    public function testStrategyImplementsInterface()
    {
        
        $this->assertInstanceOf('\Vanqard\PassMan\Strategy\HashingStrategy', $this->bcryptStrategyTen);
    }
    
    public function testStrategyReturnsCostValue()
    {
        $this->assertEquals(10, $this->bcryptStrategyTen->getOption('cost'));
        $this->assertEquals(12, $this->bcryptStrategyTwelve->getOption('cost'));
    }
    
    public function testDefaultStrategyInitialisesWithDefaults()
    {
        $newStrategy = new Bcrypt();
        $this->assertEquals(10, $newStrategy->getOption('cost'));
    }
    
    public function testStrategyGeneratesValidHash()
    {
        $hash = $this->bcryptStrategyTen->passwordHash($this->testPassword);
        $this->assertTrue(password_verify($this->testPassword, $hash));
    }
    
    public function testStrategySetOptionsIsFluent()
    {
        $this->assertInstanceOf(get_class($this->bcryptStrategyTen), $this->bcryptStrategyTen->setOptions(array("cost" => 10)));
    }
    
    public function testStrategyAcceptsValidCostOption()
    {
        $this->bcryptStrategyTen->setOptions(array("cost" => 11));
        $this->assertEquals(11, $this->bcryptStrategyTen->getOption('cost'));
    }

    /**
     * @expectedException \Vanqard\PassMan\Exception\AlgorithmException
     * @expectedExceptionCode 1024
     */
    public function testStrategyRejectsInvalidCostOption()
    {
        $this->bcryptStrategyTen->setOptions(array("cost" => 99999));
    }
    
    public function testStrategySetsDefaultCostWhenNotSupplied()
    {
        $withCost = array("cost" => 16);
        $withoutCost = array("salt" => "ignored");
        
        // First confirm valid is set to no default value
        $this->bcryptStrategyTen->setOptions($withCost);
        $this->assertEquals(16, $this->bcryptStrategyTen->getOption('cost'));
        
        // Second confirm default is set to default (10) when not supplied
        $this->bcryptStrategyTen->setOptions($withoutCost);
        $this->assertEquals(10, $this->bcryptStrategyTen->getOption('cost'));
    }
    
    /**
     * @expectedException \Vanqard\PassMan\Exception\AlgorithmException
     * @expectedExceptionCode 2048
     */
    public function testStrategyCannotYieldSaltOption()
    {
        $this->bcryptStrategyTen->setOptions(array("salt" => "mysecretsalt"));
        $this->bcryptStrategyTen->getOption('salt');
    }
    
    public function testStrategyConfirmsNeedsRehash()
    {
        $this->assertFalse($this->bcryptStrategyTen->passwordNeedsRehash($this->testHashTen));
        $this->assertTrue($this->bcryptStrategyTwelve->passwordNeedsRehash($this->testHashTen));
    }
}