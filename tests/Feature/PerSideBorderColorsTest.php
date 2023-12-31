<?php

use TailwindClassMerge\TailwindClassMerge;

it('merges classes with per-side border colors correctly', function (string $input, string $output) {
    expect(TailwindClassMerge::instance()->merge($input))
        ->toBe($output);
})->with([
    ['border-t-some-blue border-t-other-blue', 'border-t-other-blue'],
    ['border-t-some-blue border-some-blue', 'border-some-blue'],
]);
