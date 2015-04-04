<?php
namespace Vanqard\PassMan\Test;

use Vanqard\PassMan\PasswordManager;

class PasswordManagerTest extends \PHPUnit_Framework_TestCase
{
    public $sut;
    
    public $testPassword = "rasmus";
    
    public $testHashTen = "";
    
    public $testHashTwelve = "";
    
    public function setUp()
    {
        // establish control values
        $this->testHashTen = password_hash($this->testPassword, PASSWORD_BCRYPT, array("cost" => 10));
        $this->testHashTwelve = password_hash($this->testPassword, PASSWORD_BCRYPT, array("cost" => 12));
    }
    
    public function initialiseSUT(array $options = array("cost" => 10))
    {
        $this->sut = PasswordManager::factory(PASSWORD_DEFAULT, $options);
    }
    
    public function testFactoryValidCallYieldsInstance()
    {
        $this->assertInstanceOf('\Vanqard\PassMan\PasswordManager', PasswordManager::factory(PASSWORD_DEFAULT));
    }
    
    /**
     * @expectedException \Vanqard\PassMan\Exception\PasswordManagerException
     */
    public function testFactoryInvalidCallThrowsException()
    {
        $this->assertInstanceOf('\Vanqard\PassMan\PasswordManager', PasswordManager::factory('PASSWORD_ROT13'));
    }
    
    public function testInstanceYieldsValidHash()
    {
        $this->initialiseSUT();
        
        $hash = $this->sut->passwordHash($this->testPassword);
        
        $this->assertTrue(password_verify($this->testPassword, $hash));
    }
    
    public function testInstanceCanVerifyValidHash()
    {
        $this->initialiseSut();
        $this->assertTrue($this->sut->passwordVerify($this->testPassword, $this->testHashTen));
    }
    
    public function testInstanceRejectsInvalidHash()
    {
        $this->initialiseSut();
        $this->assertFalse($this->sut->passwordVerify($this->testPassword, 'Hello World'));
    }
    
    public function testInstanceCanConfirmNeedsRehash()
    {
        $this->initialiseSUT(["cost" => 12]);
        $this->assertTrue($this->sut->passwordNeedsRehash($this->testHashTen));
    }
    
    public function testInstanceConfirmsNoRehash()
    {
        $this->initialiseSUT(["cost" => 10]);
        $this->assertFalse($this->sut->passwordNeedsRehash($this->testHashTen));
    }
    
    public function testInstanceGetInfoReturnsArray()
    {
        $this->initialiseSUT();
        $hashInfo = $this->sut->passwordGetInfo($this->testHashTen);
        
        $this->assertTrue(is_array($hashInfo));
        $this->assertArrayHasKey('algo', $hashInfo);
        $this->assertArrayHasKey('algoName', $hashInfo);
    }
    
    public function testIsStubable()
    {
        $stubbedHashValue = 'stubbedhashvalue';
        
        $passwordManagerStub = $this->getMockBuilder('\Vanqard\PassMan\PasswordManager')
                                    ->disableOriginalConstructor()
                                    ->getMock();
        
        $passwordManagerStub->method('passwordHash')
                            ->willReturn($stubbedHashValue);
             
        $this->assertEquals($stubbedHashValue, $passwordManagerStub->passwordHash($stubbedHashValue));
    }
}