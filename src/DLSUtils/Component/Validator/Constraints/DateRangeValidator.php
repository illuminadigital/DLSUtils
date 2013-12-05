<?php

namespace DLSUtils\Component\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/*
 * Based on the source of Michaël Perrin in http://blog.michaelperrin.fr/2013/03/19/range-date-validator-for-symfony2/.
 */

class DateRangeValidator extends ConstraintValidator
{
    /**
     * {@inheritDoc}
     */
    public function validate($value, Constraint $constraint)
    {
        if (null === $value) {
            return;
        }

        if (!($value instanceof \DateTime)) {
            $this->context->addViolation($constraint->invalidMessage, array(
                '{{ value }}' => $value,
            ));

            return;
        }

        if (null !== $constraint->max && $value > new \DateTime($constraint->max)) {
            $this->context->addViolation($constraint->maxMessage, array(
                '{{ value }}' => $value,
                '{{ limit }}' => $this->formatDate(new \DateTime($constraint->max)),
            ));
        }

        if (null !== $constraint->min && $value < new \DateTime($constraint->min)) {
            $this->context->addViolation($constraint->minMessage, array(
                '{{ value }}' => $value,
                '{{ limit }}' => $this->formatDate(new \DateTime($constraint->min)),
            ));
        }
    }

    protected function formatDate($date)
    {
        $formatter = new \IntlDateFormatter(
            null,
            \IntlDateFormatter::SHORT,
            \IntlDateFormatter::NONE,
            date_default_timezone_get(),
            \IntlDateFormatter::GREGORIAN,
            'dd/MM/yyyy'
        );

        return $this->processDate($formatter, $date);
    }

    /**
     * @param  \IntlDateFormatter $formatter
     * @param  \Datetime          $date
     * @return string
     */
    protected function processDate(\IntlDateFormatter $formatter, \Datetime $date)
    {
        return $formatter->format((int) $date->format('U'));
    }
}