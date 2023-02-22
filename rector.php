<?php

declare(strict_types=1);

use Rector\Arguments\Rector\ClassMethod\ArgumentAdderRector;
use Rector\CodeQuality\Rector\ClassMethod\DateTimeToDateTimeInterfaceRector;
use Rector\CodingStyle\Rector\ArrowFunction\StaticArrowFunctionRector;
use Rector\CodingStyle\Rector\ClassMethod\UnSpreadOperatorRector;
use Rector\CodingStyle\Rector\Closure\StaticClosureRector;
use Rector\CodingStyle\Rector\Encapsed\EncapsedStringsToSprintfRector;
use Rector\CodingStyle\Rector\Plus\UseIncrementAssignRector;
use Rector\CodingStyle\Rector\PostInc\PostIncDecToPreIncDecRector;
use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\Node\RemoveNonExistingVarAnnotationRector;
use RectorLaravel\Rector\Class_\AnonymousMigrationsRector;
use RectorLaravel\Rector\Class_\UnifyModelDatesWithCastsRector;
use RectorLaravel\Rector\FuncCall\FactoryFuncCallToStaticCallRector;
use RectorLaravel\Rector\FuncCall\RemoveDumpDataDeadCodeRector;
use RectorLaravel\Rector\MethodCall\ChangeQueryWhereDateValueWithCarbonRector;
use RectorLaravel\Rector\MethodCall\FactoryApplyingStatesRector;
use RectorLaravel\Rector\MethodCall\RedirectBackToBackHelperRector;
use RectorLaravel\Rector\MethodCall\RedirectRouteToToRouteHelperRector;
use RectorLaravel\Rector\Namespace_\FactoryDefinitionRector;
use RectorLaravel\Rector\PropertyFetch\OptionalToNullsafeOperatorRector;
use RectorLaravel\Set\LaravelSetList;
use Rector\Naming\Rector\Assign\RenameVariableToMatchMethodCallReturnTypeRector;
use Rector\Naming\Rector\Class_\RenamePropertyToMatchTypeRector;
use Rector\Naming\Rector\ClassMethod\RenameParamToMatchTypeRector;
use Rector\Php81\Rector\FuncCall\NullToStrictStringFuncCallArgRector;
use Rector\Php82\Rector\Class_\ReadOnlyClassRector;
use Rector\Privatization\Rector\Class_\ChangeReadOnlyVariableWithDefaultValueToConstantRector;
use Rector\Privatization\Rector\Class_\FinalizeClassesWithoutChildrenRector;
use Rector\Privatization\Rector\Class_\RepeatedLiteralToClassConstantRector;
use Rector\PSR4\Rector\FileWithoutNamespace\NormalizeNamespaceByPSR4ComposerAutoloadRector;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;
use Rector\Transform\Rector\FuncCall\FuncCallToNewRector;
use Rector\TypeDeclaration\Rector\ClassMethod\ArrayShapeFromConstantArrayReturnRector;
use Rector\Visibility\Rector\ClassMethod\ChangeMethodVisibilityRector;

return static function (RectorConfig $rectorConfig): void {
    $timeout = getenv('RECTOR_TIMEOUT') ? intval(getenv('RECTOR_TIMEOUT')) : 600;
    $processes = getenv('RECTOR_PROCESSES') ? intval(getenv('RECTOR_PROCESSES')) : 600;
    $itemsPerBatch = getenv('RECTOR_ITEMS_PER_BATCH') ? intval(getenv('RECTOR_ITEMS_PER_BATCH')) : 10;

    if ($processes > 1) {
        $rectorConfig->parallel($timeout, $processes, $itemsPerBatch);
    } else {
        $rectorConfig->disableParallel();
    }

    $rectorConfig->paths([
        __DIR__.'/app',
        __DIR__.'/database',
        __DIR__.'/tests',
    ]);

    $rectorConfig->sets([
        SetList::ACTION_INJECTION_TO_CONSTRUCTOR_INJECTION,
        SetList::CODE_QUALITY,
        SetList::CODING_STYLE,
        SetList::DEAD_CODE,
        SetList::EARLY_RETURN,
        SetList::NAMING,
        SetList::PRIVATIZATION,
        SetList::PSR_4,
        SetList::TYPE_DECLARATION,
        LevelSetList::UP_TO_PHP_82,
        LaravelSetList::LARAVEL_90,
        LaravelSetList::LARAVEL_ARRAY_STR_FUNCTION_TO_STATIC_CALL,
        LaravelSetList::LARAVEL_CODE_QUALITY,
        LaravelSetList::LARAVEL_LEGACY_FACTORIES_TO_CLASSES,
        LaravelSetList::ARRAY_STR_FUNCTIONS_TO_STATIC_CALL,
    ]);

    $rectorConfig->importNames();

    $rectorConfig->rule(FactoryDefinitionRector::class);
    $rectorConfig->rule(FactoryFuncCallToStaticCallRector::class);
    $rectorConfig->rule(OptionalToNullsafeOperatorRector::class);
    $rectorConfig->rule(RedirectBackToBackHelperRector::class);
    $rectorConfig->rule(RedirectRouteToToRouteHelperRector::class);
    $rectorConfig->rule(RemoveDumpDataDeadCodeRector::class);
    $rectorConfig->rule(UnifyModelDatesWithCastsRector::class);
    $rectorConfig->rule(AnonymousMigrationsRector::class);
    $rectorConfig->rule(FactoryApplyingStatesRector::class);
    $rectorConfig->rule(ChangeQueryWhereDateValueWithCarbonRector::class);

    $rectorConfig->skip([
        NormalizeNamespaceByPSR4ComposerAutoloadRector::class,
        ArrayShapeFromConstantArrayReturnRector::class,
        RemoveNonExistingVarAnnotationRector::class,
        RepeatedLiteralToClassConstantRector::class,
        FinalizeClassesWithoutChildrenRector::class,
        RenamePropertyToMatchTypeRector::class,
        RenameParamToMatchTypeRector::class,
        NullToStrictStringFuncCallArgRector::class,
        ChangeReadOnlyVariableWithDefaultValueToConstantRector::class,
        DateTimeToDateTimeInterfaceRector::class,
        RenameVariableToMatchMethodCallReturnTypeRector::class,
        UseIncrementAssignRector::class,
        PostIncDecToPreIncDecRector::class,
        ReadOnlyClassRector::class,
        FuncCallToNewRector::class,
        StaticClosureRector::class,
        StaticArrowFunctionRector::class,
        EncapsedStringsToSprintfRector::class,
        UnSpreadOperatorRector::class,
        ChangeMethodVisibilityRector::class,
        ArgumentAdderRector::class,
    ]);
};
