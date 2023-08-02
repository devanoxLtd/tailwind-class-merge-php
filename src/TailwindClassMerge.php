<?php

namespace TailwindClassMerge;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use TailwindClassMerge\Contracts\TailwindClassMergeContract;
use TailwindClassMerge\Support\Config;
use TailwindClassMerge\Support\TailwindClassParser;
use TailwindClassMerge\ValueObjects\ParsedClass;

class TailwindClassMerge implements TailwindClassMergeContract
{
    public static function instance(): self
    {
        return self::factory()
            ->make();
    }

    /**
     * Creates a new factory instance
     */
    public static function factory(): Factory
    {
        return new Factory;
    }

    /**
     * @param  array<string, mixed>  $configuration
     */
    public function __construct(
        private readonly array $configuration
    ) {
    }

    /**
     * @param  array<array-key, string|array<array-key, string>>  ...$args
     */
    public function merge(...$args): string
    {
        $input = Arr::toCssClasses($this->createArrayFromArgs($args));

        $conflictingClassGroups = [];

        $parser = new TailwindClassParser($this->configuration);

        return Str::of($input)
            ->trim()
            ->split('/\s+/')
            ->map(fn (string $class): ParsedClass => $parser->parse($class)) // @phpstan-ignore-line
            ->reverse()
            ->map(function (ParsedClass $class) use (&$conflictingClassGroups): ?string {
                $classId = $class->modifierId . $class->classGroupId;

                if (array_key_exists($classId, $conflictingClassGroups)) {
                    return null;
                }

                $conflictingClassGroups[$classId] = true;

                foreach (self::getConflictingClassGroupIds($class->classGroupId, $class->hasPostfixModifier) as $group) {
                    $conflictingClassGroups[$class->modifierId . $group] = true;
                }

                return $class->originalClassName;
            })
            ->reverse()
            ->filter()
            ->join(' ');
    }

    /**
     * @return array<array-key, string>
     */
    private static function getConflictingClassGroupIds(string $classGroupId, bool $hasPostfixModifier): array
    {
        $conflicts = Config::getMergedConfig()['conflictingClassGroups'][$classGroupId] ?? [];

        if ($hasPostfixModifier && isset(Config::getMergedConfig()['conflictingClassGroupModifiers'][$classGroupId])) {
            return [...$conflicts, ...Config::getMergedConfig()['conflictingClassGroupModifiers'][$classGroupId]];
        }

        return $conflicts;
    }

    /**
     * @param  array<mixed>  $args
     * @return array<mixed>
     */
    private function createArrayFromArgs(array $args = []): array
    {
        $result = [];
        foreach ($args as $key => $value) {
            if (is_array($value)) {
                $result = array_merge($result, $this->createArrayFromArgs($value));
            } elseif (is_numeric($key)) {
                $result[] = $value;
            } elseif (is_bool($value)) {
                $result[$key] = $value;
            } else {
                $result[] = $key;
            }
        }

        return $result;
    }
}