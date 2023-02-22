<?php

use PHP_CodeSniffer\Standards\Generic\Sniffs\CodeAnalysis\AssignmentInConditionSniff;
use PhpCsFixer\Fixer\Basic\BracesFixer;
use PhpCsFixer\Fixer\Basic\CurlyBracesPositionFixer;
use PhpCsFixer\Fixer\ClassNotation\ClassDefinitionFixer;
use PhpCsFixer\Fixer\ClassNotation\SelfAccessorFixer;
use PhpCsFixer\Fixer\FunctionNotation\NativeFunctionInvocationFixer;
use PhpCsFixer\Fixer\Import\NoUnusedImportsFixer;
use PhpCsFixer\Fixer\Operator\ConcatSpaceFixer;
use PhpCsFixer\Fixer\Operator\IncrementStyleFixer;
use PhpCsFixer\Fixer\Operator\StandardizeIncrementFixer;
use PhpCsFixer\Fixer\PhpUnit\PhpUnitMethodCasingFixer;
use PhpCsFixer\Fixer\Semicolon\MultilineWhitespaceBeforeSemicolonsFixer;
use PhpCsFixer\Fixer\Whitespace\MethodChainingIndentationFixer;
use Symplify\CodingStandard\Fixer\Spacing\MethodChainingNewlineFixer;
use Symplify\EasyCodingStandard\Config\ECSConfig;
use Symplify\EasyCodingStandard\ValueObject\Set\SetList;

return static function (ECSConfig $ecsConfig): void {
    $ecsConfig->parallel();
    $ecsConfig->paths([
        __DIR__.'/app',
        __DIR__.'/database',
        __DIR__.'/tests',
    ]);

    $ecsConfig->sets([
        SetList::ARRAY,
        SetList::CLEAN_CODE,
        SetList::COMMENTS,
        SetList::COMMON,
        SetList::CONTROL_STRUCTURES,
        SetList::DOCBLOCK,
        SetList::NAMESPACES,
        SetList::PHPUNIT,
        SetList::PSR_12,
        SetList::SPACES,
        SetList::STRICT,
        SetList::SYMPLIFY,
    ]);

    $ecsConfig->skip([
        PhpUnitMethodCasingFixer::class,
        MethodChainingNewlineFixer::class,
        MethodChainingIndentationFixer::class,
        IncrementStyleFixer::class,
        StandardizeIncrementFixer::class,
        AssignmentInConditionSniff::class,
        NativeFunctionInvocationFixer::class,
        ClassDefinitionFixer::class,
        ConcatSpaceFixer::class,
        BracesFixer::class,
        CurlyBracesPositionFixer::class,
        SelfAccessorFixer::class,
    ]);

    $ecsConfig->rule(NoUnusedImportsFixer::class);
    $ecsConfig->ruleWithConfiguration(MultilineWhitespaceBeforeSemicolonsFixer::class, [
        'strategy' => 'no_multi_line',
    ]);
};
