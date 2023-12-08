<?php

use TailwindClassMerge\Support\Config;
use TailwindClassMerge\TailwindClassMerge;

test('theme scale can be extended', function ($input, $output) {
    $instance = TailwindClassMerge::factory()
        ->withConfiguration([
            'theme' => [
                'spacing' => ['my-space'],
                'margin' => ['my-margin'],
            ],
        ])
        ->make();

    expect($instance->merge($input))->toBe($output);
})->with([
    ['p-3 p-my-space p-my-margin', 'p-my-space p-my-margin'],
    ['m-3 m-my-space m-my-margin', 'm-my-margin'],
]);

test('theme object can be extended', function ($input, $output) {
    $instance = TailwindClassMerge::factory()
        ->withConfiguration([
            'theme' => [
                'my-theme' => ['hallo', 'hello'],
            ],
            'classGroups' => [
                'px' => [['px' => [Config::fromTheme('my-theme')]]],
            ],
        ])
        ->make();

    expect($instance->merge($input))->toBe($output);
})->with([
    ['p-3 p-hello p-hallo', 'p-3 p-hello p-hallo'],
    ['px-3 px-hello px-hallo', 'px-hallo'],
]);
