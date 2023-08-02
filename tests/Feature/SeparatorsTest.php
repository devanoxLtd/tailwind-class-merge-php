<?php

use TailwindClassMerge\TailwindClassMerge;

test('single character separator working correctly', function () {
    //    const twMerge = extendTailwindClassMerge({
    //        separator: '_',
    //    })

    expect(TailwindClassMerge::instance()->merge('block hidden'))->toBe('hidden');

    expect(TailwindClassMerge::instance()->merge('p-3 p-2'))->toBe('p-2');

    expect(TailwindClassMerge::instance()->merge('!right-0 !inset-0'))->toBe('!inset-0');

    expect(TailwindClassMerge::instance()->merge('hover_focus_!right-0 focus_hover_!inset-0'))->toBe('focus_hover_!inset-0');
    expect(TailwindClassMerge::instance()->merge('hover:focus:!right-0 focus:hover:!inset-0'))->toBe(
        'hover:focus:!right-0 focus:hover:!inset-0',
    );
})->todo();

test('multiple character separator working correctly', function () {
    //    const twMerge = extendTailwindClassMerge({
    //        separator: '__',
    //    })

    expect(TailwindClassMerge::instance()->merge('block hidden'))->toBe('hidden');

    expect(TailwindClassMerge::instance()->merge('p-3 p-2'))->toBe('p-2');

    expect(TailwindClassMerge::instance()->merge('!right-0 !inset-0'))->toBe('!inset-0');

    expect(TailwindClassMerge::instance()->merge('hover__focus__!right-0 focus__hover__!inset-0'))->toBe('focus__hover__!inset-0');
    expect(TailwindClassMerge::instance()->merge('hover:focus:!right-0 focus:hover:!inset-0'))->toBe(
        'hover:focus:!right-0 focus:hover:!inset-0',
    );
})->todo();