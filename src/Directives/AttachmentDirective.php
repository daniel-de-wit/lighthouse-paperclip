<?php

declare(strict_types=1);

namespace DanielDeWit\LighthousePaperclip\Directives;

use Closure;
use Czim\Paperclip\Contracts\AttachmentInterface;
use GraphQL\Type\Definition\ResolveInfo;
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

    public function handleField(FieldValue $fieldValue, Closure $next): FieldValue
    {
        // Retrieve the existing resolver function
        /** @var Closure $previousResolver */
        $previousResolver = $fieldValue->getResolver();

        // Wrap around the resolver
        $wrappedResolver = function ($root, array $args, GraphQLContext $context, ResolveInfo $info) use ($previousResolver): string {
            // Call the resolver, passing along the resolver arguments
            /** @var AttachmentInterface $result */
            $result = $previousResolver($root, $args, $context, $info);

            return $result->url($args['variant'] ?? null);
        };

        // Place the wrapped resolver back upon the FieldValue
        // It is not resolved right now - we just prepare it
        $fieldValue->setResolver($wrappedResolver);

        // Keep the middleware chain going
        return $next($fieldValue);
    }
}
