<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class DomainEmail extends Constraint
{
    public $message = 'Nécessite un email de la société Deloitte.';
}
