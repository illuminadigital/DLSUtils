<?php

namespace DLSUtils\Component\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\MissingOptionsException;

/**
 * @Annotation
 *
 * @author Josep Bonet <josep.bonet@digitallifesciences.co.uk>
 *
 */

/*
 * Based on the source of Michaël Perrin in http://blog.michaelperrin.fr/2013/03/19/range-date-validator-for-symfony2/.
 */

class DateRange extends Constraint
{
    public $minMessage = 'This date should be greater than {{ limit }}.';
    public $maxMessage = 'This date should be less than {{ limit }}.';
    public $invalidMessage = 'This value should be a valid date.';
    public $min;
    public $max;

    public function __construct($options = null)
    {
        parent::__construct($options);

        if (null === $this->min && null === $this->max) {
            throw new MissingOptionsException('Either option "min" or "max" must be given for constraint ' . __CLASS__, array('min', 'max'));
        }

//        if (null !== $this->min) {
//            $this->min = new \DateTime($this->min);
//        }
//
//        if (null !== $this->max) {
//            $this->max = new \DateTime($this->max);
//        }
    }
}