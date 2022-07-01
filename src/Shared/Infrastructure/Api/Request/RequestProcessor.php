<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Api\Request;

use ApiPlatform\Core\Exception\InvalidArgumentException;
use ApiPlatform\Core\Validator\ValidatorInterface;
use App\ThridParty\ApiPlatform\Format\RequestExtractFormat;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class RequestProcessor
{
    public function __construct(
        protected readonly ValidatorInterface $validator,
        protected readonly MessageBusInterface $queryBus,
        protected readonly SerializerInterface $serializer,
        protected readonly RequestStack $requestStack,
        protected readonly RequestExtractFormat $requestExtractFormat
    ) {
    }

    /**
     * @param mixed[]|null $validatorOpts
     */
    public function process(
        string $classType,
        ?string $busDispatchWithClass = null,
        ?array $validatorOpts = ['groups' => ['Default']],
    ): mixed {
        try {
            $request = $this->requestStack->getCurrentRequest();

            if ('form' === $request->getContentType()) {
                $dataRaw = $request->request->all();
                $dataRaw = json_encode($dataRaw);
                $format = 'json';
            } else {
                $dataRaw = $request->getContent();
                $format = $this->requestExtractFormat->getCurrentFormat();
            }

            $data = $this->serializer->deserialize($dataRaw,
                $classType,
                $format,
                [AbstractObjectNormalizer::DISABLE_TYPE_ENFORCEMENT => true]
            );
        } catch (\Throwable $e) {
            throw new InvalidArgumentException('Syntax error trying to extract data from request', 0, $e);
        }

        $this->validator->validate($data, $validatorOpts);

        if ($busDispatchWithClass) {
            return $this->queryBus->dispatch(
                new $busDispatchWithClass($data)
            )->last(HandledStamp::class)->getResult();
        }

        return $data;
    }
}
