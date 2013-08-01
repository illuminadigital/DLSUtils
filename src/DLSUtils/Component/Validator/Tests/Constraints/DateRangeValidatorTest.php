<?php

namespace DLSUtils\Component\Validator\Tests\Constraints;

use DLSUtils\Component\Validator\Constraints\Range;
use DLSUtils\Component\Validator\Constraints\RangeValidator;

class RangeValidatorTest extends \PHPUnit_Framework_TestCase
{
    protected $context;
    protected $validator;

    protected function setUp()
    {
        $this->context = $this->getMock('Symfony\Component\Validator\ExecutionContext', array(), array(), '', false);
        $this->validator = new DateRangeValidator();
        $this->validator->initialize($this->context);
    }

    public function testNullIsValid()
    {
        $this->context->expects($this->never())
            ->method('addViolation');

        $this->validator->validate(null, new Range(array('min' => 10, 'max' => 20)));
    }

    public function getEmptyDate()
    {
        return array(
            null
        );
    }

    public function getFutureDate()
    {
        return array(
            new \DateTime('+1m'),
            new \DateTime('+2d'),
            new \DateTime('+1d')
        );
    }

    public function getPastDate()
    {
        return array(
            new \DateTime('-1m'),
            new \DateTime('-2d'),
            new \DateTime('-1d'),
        );
    }

    public function getCurrentDate(){
        return array(
            new \DateTime('now')
        );
    }

    public function get10DayInterval(){
        return array(
            new \DateTime('-10d'),
            new \DateTime('-5d'),
            new \DateTime('now'),
            new \DateTime('+5d'),
            new \DateTime('+10d')
        );

    }

    /**
     * @dataProvider get10DayInterval
     */
    public function testValidValuesMin($value)
    {
        $this->context->expects($this->never())
            ->method('addViolation');

        $constraint = new DateRange(array('min' => new \DateTime('-10d')));
        $this->validator->validate($value, $constraint);
    }

    /**
     * @dataProvider get10DayInterval
     */
    public function testValidValuesMax($value)
    {
        $this->context->expects($this->never())
            ->method('addViolation');

        $constraint = new Range(array('max' => new \DateTime('+10d')));
        $this->validator->validate($value, $constraint);
    }

    /**
     * @dataProvider getTenToTwenty
     */
    public function testValidValuesMinMax($value)
    {
        $this->context->expects($this->never())
            ->method('addViolation');

        $constraint = new Range(array('min' => new \DateTime('-10d'), 'max' => new \DateTime('+10d')));
        $this->validator->validate($value, $constraint);
    }

    /**
     * @dataProvider getPastDate()
     */
    public function testInvalidValuesMin($value)
    {
        $constraint = new Range(array(
            'min' => new \DateTime('now'),
            'minMessage' => 'myMessage',
        ));

        $this->context->expects($this->once())
            ->method('addViolation')
            ->with('myMessage', $this->identicalTo(array(
                '{{ value }}' => $value,
                '{{ limit }}' => \DateTime('now'),
        )));

        $this->validator->validate($value, $constraint);
    }

    //TODO: Requires full testing.
}
