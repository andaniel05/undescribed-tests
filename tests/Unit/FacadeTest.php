<?php

use Andaniel05\UndescribedTests\Facade;

Facade::test(function () {
    expect(true)->toBe(true);
});

Facade::test(function () {
    expect(true)->toBe(true);
});

Facade::test('', function () {
    expect(true)->toBe(true);
});

Facade::test('', function () {
    expect(true)->toBe(true);
});

Facade::test('described test', function () {
    expect(true)->toBe(true);
});