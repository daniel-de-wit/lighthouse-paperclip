<?php

declare(strict_types=1);

namespace DanielDeWit\LighthousePaperclip\Directives;

use Czim\Paperclip\Contracts\AttachmentInterface;
use Exception;
use GraphQL\Language\AST\FieldDefinitionNode;
use GraphQL\Language\AST\InputValueDefinitionNode;
use GraphQL\Language\AST\ObjectTypeDefinitionNode;
use Illuminate\Support\Str;
use Nuwave\Lighthouse\Schema\AST\DocumentAST;
use Nuwave\Lighthouse\Schema\AST\PartialParser;
use Nuwave\Lighthouse\Schema\Directives\BaseDirective;
use Nuwave\Lighthouse\Support\Contracts\ArgManipulator;

class VariantDirective extends BaseDirective implements ArgManipulator
{
    public static function definition(): string
    {
        return /** @lang GraphQL */ <<<'SDL'
"""
Generates an variant enum based on the Attachment
"""
directive @variant(
    """
    Restrict the allowed variants to a well-defined list.
    """
    variants: [String!]
) on ARGUMENT_DEFINITION
SDL;
    }

    public function manipulateArgDefinition(
        DocumentAST &$documentAST,
        InputValueDefinitionNode &$argDefinition,
        FieldDefinitionNode &$parentField,
        ObjectTypeDefinitionNode &$parentType
    ): void {
        // Todo: Find a better way to determine the Model
        $modelClass = $parentType->directives[0]->arguments[0]->value->value;

        /** @var AttachmentInterface $model */
        $model = new $modelClass();
        $attribute = Str::snake($parentField->name->value);

        $availableVariants = $model->{$attribute}->variants();
        $allowedVariants = $this->directiveArgValue('variants');

        $variants = $availableVariants;

        if ($allowedVariants) {
            // Check if the pre-defined variants actually exist.
            $faulty = array_diff($allowedVariants, $availableVariants);
            if (count($faulty)) {
                // Todo: Throw dedicated Exception./vendor/bin/php-cs-fixer fix --allow-risky=yes --show-progress=run-in --dry-run
                throw new Exception('Variant(s) "' . implode('", "', $faulty) . '" are not available for attachment "' . $attribute . '" on model "' . $modelClass . '"');
            }

            $variants = $allowedVariants;
        }

        $resizeEnumName = Str::studly($parentField->name->value) . Str::studly($argDefinition->name->value);
        $argDefinition->type = PartialParser::namedType($resizeEnumName);


        $enumValues = collect($variants)->map(function (string $variant) {
            return strtoupper($variant) . ' @enum(value: "' . $variant . '")';
        });

        $enumDefinition = "\"Allowed resizes for the `{$argDefinition->name->value}` argument on the query `{$parentField->name->value}`.\"\n" . "enum $resizeEnumName {\n";
        foreach ($enumValues as $enumValue) {
            $enumDefinition .= "$enumValue\n";
        }
        $enumDefinition .= '}';

        $documentAST->setTypeDefinition(
            PartialParser::enumTypeDefinition($enumDefinition)
        );
    }
}
