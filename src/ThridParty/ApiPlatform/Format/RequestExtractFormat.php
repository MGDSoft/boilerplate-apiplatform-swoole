<?php

declare(strict_types=1);

namespace App\ThridParty\ApiPlatform\Format;

use ApiPlatform\Core\Api\FormatMatcher;
use ApiPlatform\Core\Metadata\Resource\Factory\ResourceMetadataFactoryInterface;
use ApiPlatform\Core\Serializer\SerializerContextBuilderInterface;
use ApiPlatform\Core\Util\RequestAttributesExtractor;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Exception\UnsupportedMediaTypeHttpException;

/**
 * Class copied from ApiPlatform\Core\EventListener\DeserializeListener.
 */
class RequestExtractFormat
{
    public function __construct(
        protected readonly RequestStack $requestStack,
        protected ResourceMetadataFactoryInterface $resourceMetadataFactory,
        protected SerializerContextBuilderInterface $serializerContextBuilder
    ) {
    }

    public function getCurrentFormat(): string
    {
        $request = $this->requestStack->getCurrentRequest();

        $attributes = RequestAttributesExtractor::extractAttributes($request);

        $formats = $this
            ->resourceMetadataFactory
            ->create($attributes['resource_class'])
            ->getOperationAttribute($attributes, 'input_formats', [], true);

        return $this->getFormat($request, $formats);
    }

    /**
     * @param string[][] $formats
     */
    private function getFormat(Request $request, array $formats): string
    {
        /**
         * @var string|null
         */
        $contentType = $request->headers->get('CONTENT_TYPE');
        if (null === $contentType) {
            throw new UnsupportedMediaTypeHttpException('The "Content-Type" header must exist.');
        }

        $formatMatcher = new FormatMatcher($formats);
        $format = $formatMatcher->getFormat($contentType);
        if (null === $format) {
            $supportedMimeTypes = [];
            foreach ($formats as $mimeTypes) {
                foreach ($mimeTypes as $mimeType) {
                    $supportedMimeTypes[] = $mimeType;
                }
            }

            throw new UnsupportedMediaTypeHttpException(sprintf('The content-type "%s" is not supported. Supported MIME types are "%s".', $contentType, implode('", "', $supportedMimeTypes)));
        }

        return $format;
    }
}
