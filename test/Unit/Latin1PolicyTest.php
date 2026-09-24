<?php

declare(strict_types=1);

namespace Vanqard\PassMan\Test;

use PHPUnit\Framework\TestCase;
use Vanqard\PassMan\Policy\Latin1Policy;

class Latin1PolicyTest extends TestCase
{
    public Latin1Policy $sut;

    protected function setUp(): void
    {
        $this->sut = new Latin1Policy();
    }

    public function testQualityRatingIsWithinBounds(): void
    {
        foreach (['', 'a', 'password', 'Password1!', str_repeat('aA1!', 20)] as $password) {
            $this->sut->setRawPassword($password);
            $rating = $this->sut->getQualityRating();

            $this->assertGreaterThanOrEqual(1, $rating);
            $this->assertLessThanOrEqual(100, $rating);
        }
    }

    public function testEmptyPasswordYieldsMinimumRating(): void
    {
        $this->sut->setRawPassword('');
        $this->assertEquals(1, $this->sut->getQualityRating());
    }

    public function testLongerPasswordScoresAtLeastAsHighAsShorterPrefix(): void
    {
        $this->sut->setRawPassword('password');
        $shortRating = $this->sut->getQualityRating();

        $this->sut->setRawPassword('passwordpassword');
        $longRating = $this->sut->getQualityRating();

        $this->assertGreaterThan($shortRating, $longRating);
    }

    public function testGreaterCharacterVarietyScoresHigherThanSameLengthSingleClass(): void
    {
        $this->sut->setRawPassword('aaaaaaaa');
        $singleClassRating = $this->sut->getQualityRating();

        $this->sut->setRawPassword('aA1!aA1!');
        $mixedClassRating = $this->sut->getQualityRating();

        $this->assertGreaterThan($singleClassRating, $mixedClassRating);
    }

    public function testLongVariedPasswordScoresMaximum(): void
    {
        $this->sut->setRawPassword(str_repeat('aA1!', 20));
        $this->assertEquals(100, $this->sut->getQualityRating());
    }
}
