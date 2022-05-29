<?php

declare(strict_types=1);

namespace App\Role\Transport\Form\DataTransformer;

use App\Role\Application\Resource\RoleResource;
use App\Role\Domain\Entity\Role;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Throwable;

use function is_string;
use function sprintf;

/**
 * Class RoleTransformer
 *
 * @package App\Role
 */
class RoleTransformer implements DataTransformerInterface
{
    public function __construct(
        private readonly RoleResource $resource,
    ) {
    }

    /**
     * {@inheritdoc}
     *
     * Transforms an object (Role) to a string (Role id).
     *
     * @psalm-param Role|mixed $value
     */
    public function transform($value): string
    {
        return $value instanceof Role ? $value->getId() : '';
    }

    /**
     * {@inheritdoc}
     *
     * Transforms a string (Role id) to an object (Role).
     *
     * @phpstan-param mixed $value
     *
     * @throws Throwable
     */
    public function reverseTransform($value): ?Role
    {
        return is_string($value)
            ? $this->resource->findOne($value, false) ?? throw new TransformationFailedException(
                sprintf(
                    'Role with name "%s" does not exist!',
                    $value
                ),
            )
            : null;
    }
}
