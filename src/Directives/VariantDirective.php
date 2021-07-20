<?php

declare(strict_types=1);

namespace DanielDeWit\LighthousePaperclip\Directives;

use Czim\Paperclip\Contracts\AttachmentInterface;
use Exception;
use GraphQL\Language\AST\FieldDefinitionNode;
use GraphQL\Language\AST\InputValueDefinitionNode;
use GraphQL\Language\AST\ObjectTypeDefinitionNode;
use GraphQL\Language\Parser;
use Illuminate\Support\Str;
use Nuwave\Lighthouse\Schema\AST\ASTHelper;
use Nuwave\Lighthouse\Schema\AST\DocumentAST;
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
    ): void
    {
        $modelName = ASTHelper::modelName($parentType);

        $modelFQN = $this->namespaceModelClass(
            $modelName,
        );

        /** @var AttachmentInterface $model */
        $model = new $modelFQN();
        $attribute = Str::snake($parentField->name->value);

        $availableVariants = $model->{$attribute}->variants();

        foreach ($availableVariants as $variant) {
            if (preg_match('/^[0-9]/', $variant)) {
                throw new \RuntimeException('Unable to create enum value for ' . $modelFQN . ' ' . $attribute . ' Attachment variant "' . $variant . '", due to starting with integer');
            }
        }

        $allowedVariants = $this->directiveArgValue('variants');

        $variants = $availableVariants;

        if ($allowedVariants) {
            // Check if the pre-defined variants actually exist.
            $faulty = array_diff($allowedVariants, $availableVariants);
            if (count($faulty)) {
                // Todo: Throw dedicated Exception./vendor/bin/php-cs-fixer fix --allow-risky=yes --show-progress=run-in --dry-run
                throw new Exception('Variant(s) "' . implode('", "', $faulty) . '" are not available for attachment "' . $attribute . '" on model "' . $modelFQN . '"');
            }

            $variants = $allowedVariants;
        }

        $resizeEnumName = ASTHelper::modelName($parentType) . Str::studly($parentField->name->value) . Str::studly($argDefinition->name->value);
        $argDefinition->type = Parser::namedType($resizeEnumName);

        $enumValues = collect($variants)->map(function (string $variant) {
            return strtoupper($variant) . ' @enum(value: "' . $variant . '")';
        });

        $enumDefinition = "\"Allowed resizes for the `{$argDefinition->name->value}` argument on the query `{$parentField->name->value}`.\"\n" . "enum $resizeEnumName {\n";
        foreach ($enumValues as $enumValue) {
            $enumDefinition .= "$enumValue\n";
        }
        $enumDefinition .= '}';

        $documentAST->setTypeDefinition(
            Parser::enumTypeDefinition($enumDefinition)
        );
    }
}
