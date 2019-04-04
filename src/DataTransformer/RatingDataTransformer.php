<?php

namespace App\DataTransformer;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use ApiPlatform\Core\JsonLd\Serializer\ItemNormalizer;
use App\Query\Rating;
use \RuntimeException;

final class RatingDataTransformer implements DataTransformerInterface
{
    /**
     * {@inheritdoc}
     */
    public function transform($data, string $to, array $context = [])
    {
        if (null === $data->entity = $context[ItemNormalizer::OBJECT_TO_POPULATE] ?? null) {
            throw new RuntimeException("Entity is not defined");
        }

        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        if (!\is_array($data)) {
          return false;
        }

        return Rating::class === ($context['input']['class'] ?? null);
    }
}
