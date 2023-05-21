<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class IntegerWithTwoDecimalsConstraint extends Constraint
{
    public $message = 'The value "{{ value }}" is not an integer with at most 2 decimals.';
}
