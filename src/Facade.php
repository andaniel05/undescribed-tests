<?php

namespace Andaniel05\UndescribedTests;

use Closure;
use Exception;
use Pest\PendingCalls\TestCall;
use Pest\Support\HigherOrderTapProxy;
use ReflectionFunction;

abstract class Facade
{
    public static function test(
        string|Closure|null $description = null,
        ?Closure $closure = null,
        ?string $undescribedGroupName = 'undescribed',
    ): HigherOrderTapProxy|TestCall
    {
        $blankDescription = null;

        if ($description instanceof Closure) {
            $blankDescription = true;
            $closure = $description;
        }

        if (is_string($description) && empty($description)) {
            $blankDescription = true;
        }

        if (! $closure instanceof Closure) {
            throw new Exception('Unhandled case');
        }

        if ($blankDescription) {
            $reflectionFunction = new ReflectionFunction($closure);

            $relativeFilename = str_replace(
                search: getcwd().DIRECTORY_SEPARATOR,
                replace: '',
                subject: (string) $reflectionFunction->getFileName(),
            );

            $description = $relativeFilename.':'.$reflectionFunction->getStartLine();
        }

        $test = test($description, $closure);

        if ($blankDescription && $undescribedGroupName) {
            $test->group($undescribedGroupName);
        }

        return $test;
    }
}