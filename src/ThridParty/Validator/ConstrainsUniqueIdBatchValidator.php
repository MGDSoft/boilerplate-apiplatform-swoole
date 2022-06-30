<?php

declare(strict_types=1);

namespace App\ThridParty\Validator;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ConstrainsUniqueIdBatchValidator extends ConstraintValidator implements EventSubscriberInterface
{
    /** @var int[] */
    protected array $store=[];

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof ConstrainsUniqueIdBatch) {
            throw new UnexpectedTypeException($constraint, ConstrainsUniqueIdBatch::class);
        }

        if (!$value || !is_integer($value)) {
            return;
        }

        if (isset($this->store[$value])) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{id}}', (string) $value)
                ->setCode($constraint::ERROR_CODE)
                ->addViolation();

            return;
        }

        $this->store[$value]=$value;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => 'onKernelRequest',
        ];
    }

    /**
     * Event to prevent saved data for next request, if we use php serve.
     */
    public function onKernelRequest(): void
    {
        $this->store=[];
    }
}
