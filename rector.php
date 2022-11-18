<?php

declare(strict_types=1);

use Rector\CodingStyle\Rector\ArrowFunction\StaticArrowFunctionRector;
use Rector\CodingStyle\Rector\ClassConst\VarConstantCommentRector;
use Rector\CodingStyle\Rector\ClassMethod\UnSpreadOperatorRector;
use Rector\CodingStyle\Rector\Closure\StaticClosureRector;
use Rector\CodingStyle\Rector\Encapsed\EncapsedStringsToSprintfRector;
use Rector\CodingStyle\Rector\If_\NullableCompareToNullRector;
use Rector\CodingStyle\Rector\PostInc\PostIncDecToPreIncDecRector;
use Rector\Config\RectorConfig;
use Rector\Php56\Rector\FunctionLike\AddDefaultValueForUndefinedVariableRector;
use Rector\Php70\Rector\StaticCall\StaticCallOnNonStaticToInstanceCallRector;
use Rector\Php73\Rector\FuncCall\JsonThrowOnErrorRector;
use Rector\Php74\Rector\Closure\ClosureToArrowFunctionRector;
use Rector\Php74\Rector\LNumber\AddLiteralSeparatorToNumberRector;
use Rector\Php81\Rector\Array_\FirstClassCallableRector;
use Rector\PHPUnit\Rector\Class_\AddSeeTestAnnotationRector;
use Rector\PHPUnit\Set\PHPUnitLevelSetList;
use Rector\PHPUnit\Set\PHPUnitSetList;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;
use Rector\TypeDeclaration\Rector\ClassMethod\AddArrayParamDocTypeRector;
use Rector\TypeDeclaration\Rector\ClassMethod\AddArrayReturnDocTypeRector;
use Rector\TypeDeclaration\Rector\ClassMethod\ArrayShapeFromConstantArrayReturnRector;
use Rector\TypeDeclaration\Rector\ClassMethod\ReturnNeverTypeRector;
use Rector\TypeDeclaration\Rector\FunctionLike\ReturnTypeDeclarationRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->paths([
        __DIR__.'/src',
        __DIR__.'/tests',
    ]);

    // define sets of rules
    $rectorConfig->sets([
        // PHP
        LevelSetList::UP_TO_PHP_81,

        // PHPUnit
        PHPUnitLevelSetList::UP_TO_PHPUNIT_100,
        PHPUnitSetList::PHPUNIT_CODE_QUALITY,
        PHPUnitSetList::PHPUNIT_EXCEPTION,
        PHPUnitSetList::REMOVE_MOCKS,
        PHPUnitSetList::PHPUNIT_SPECIFIC_METHOD,
        PHPUnitSetList::PHPUNIT_YIELD_DATA_PROVIDER,

        SetList::DEAD_CODE,
        SetList::CODING_STYLE,
        SetList::EARLY_RETURN,
        SetList::ACTION_INJECTION_TO_CONSTRUCTOR_INJECTION,
        SetList::TYPE_DECLARATION,
        SetList::TYPE_DECLARATION_STRICT,
    ]);

    $rectorConfig->skip([
        ClosureToArrowFunctionRector::class,
        AddDefaultValueForUndefinedVariableRector::class,
        JsonThrowOnErrorRector::class,
        NullableCompareToNullRector::class,
        VarConstantCommentRector::class,
        EncapsedStringsToSprintfRector::class,
        UnSpreadOperatorRector::class,
        AddArrayParamDocTypeRector::class,
        AddArrayReturnDocTypeRector::class,
        ReturnTypeDeclarationRector::class,
        PostIncDecToPreIncDecRector::class,
        AddLiteralSeparatorToNumberRector::class,
        StaticArrowFunctionRector::class,
        StaticClosureRector::class,
        StaticCallOnNonStaticToInstanceCallRector::class,
        FirstClassCallableRector::class,
        ArrayShapeFromConstantArrayReturnRector::class,
        ReturnNeverTypeRector::class,
        AddSeeTestAnnotationRector::class,
    ]);
};
