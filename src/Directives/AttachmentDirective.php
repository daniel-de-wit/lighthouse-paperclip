<?php

declare(strict_types=1);

namespace DanielDeWit\LighthousePaperclip\Directives;

use Closure;
use Czim\Paperclip\Contracts\AttachmentInterface;
use Nuwave\Lighthouse\Execution\ResolveInfo;
use Nuwave\Lighthouse\Schema\Directives\BaseDirective;
use Nuwave\Lighthouse\Schema\Values\FieldValue;
use Nuwave\Lighthouse\Support\Contracts\FieldMiddleware;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class AttachmentDirective extends BaseDirective implements FieldMiddleware
{
    public static function definition(): string
    {
        return /** @lang GraphQL */ <<<'SDL'
"""
Returns the url of an Attachment with optional variant
"""
directive @attachment on FIELD_DEFINITION
SDL;
    }

    public function handleField(FieldValue $fieldValue): void
    {
        $fieldValue->wrapResolver(fn (callable $resolver): Closure => function (mixed $root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo) use ($resolver) {
            /** @var AttachmentInterface $result $result */
            $result = $resolver($root, $args, $context, $resolveInfo);

            return $result->url($args['variant'] ?? null);
        });
    }
}
