<?php

namespace App\Helpers;

use Exception;

class CastMaker
{
    public const PRIMITIVE_CASTS = [
        'int',
        'integer',
        'string',
        'object',
        'array',
        'float',
        'null',
    ];

    /**
     * castItToIf function
     *
     * @param mixed $data
     * @param string $castTo
     * @param string|callable $validator
     *
     * @return mixed
     */
    public static function castItToIf(
        mixed $data,
        string|callable $castTo,
        string|array|bool|null|callable $validator
    ): mixed {
        $isValid = function () use (
            $data,
            $validator
        ) {
            if (\is_callable($validator)) {
                return static::isValid($validator, $data);
            }

            $positivePrefixes = [
                'is:',
                'type_is:',
                'instance_of:', //TODO
            ];

            $invertionPrefixes = [
                '!:',
                'not:',
                'not_is:',
            ];

            $toInvertCheck = static::prefixSum($validator, $invertionPrefixes, 1); // if are negative check
            $checkPrefix = static::prefixSum($validator, $positivePrefixes, 1);

            // check if has prefix
            if ($toInvertCheck || $checkPrefix) {
                $checkValidator = static::getValidatorFromPrefixStr(
                    $validator,
                    $toInvertCheck ? $invertionPrefixes : $positivePrefixes
                );

                if (!\is_callable($checkValidator)) {
                    return \null;
                }

                $result = static::isValid($checkValidator, $data);

                return $toInvertCheck ? !$result : $result;
            }

            return false;
        };

        if (!$isValid()) {
            return null;
        }

        if (\is_callable($castTo)) {
            return \call_user_func($castTo, $data);
        }

        $type = \gettype($data);
        $toPrimitiveType = \is_string($castTo) && \in_array(\strtolower($castTo), static::PRIMITIVE_CASTS, true);

        if ($toPrimitiveType) {
            return static::castToPrimitive($data, $castTo);
        }

        if ($type === 'object') {
            if (!is_a($data, $validator)) {
                return null;
            }

            if (!class_exists($castTo)) {
                throw new Exception("The '{$castTo}' class do not exists", 100);
            }
        }

        if (class_exists($castTo)) {
            return new $castTo($data);
        }

        return $data ?? \null;
    }

    /**
     * getValidatorFromPrefixStr function
     *
     * @param string|null $validator
     * @param array $prefixes
     *
     * @return null|string
     */
    public static function getValidatorFromPrefixStr(
        string|null $validator,
        array $prefixes
    ): null|string {
        if (!trim((string) $validator) || !$prefixes) {
            return \null;
        }

        if (static::prefixSum($validator, $prefixes, 1) != 1) {
            return \null;
        }

        $validatorStr = trim(
            \str_replace(
                $prefixes,
                '',
                \strtolower($validator)
            )
        );

        $validatorCallable = $validatorStr && \in_array(
            $validatorStr,
            static::PRIMITIVE_CASTS,
            true
        ) ? "is_{$validatorStr}" : \null;

        if (\is_callable($validatorCallable)) {
            return $validatorCallable;
        }

        return \null;
    }

    /**
     * function castToPrimitive
     *
     * @param $data
     * @param string $castTo
     *
     * @return mixed
     */
    public static function castToPrimitive($data, string $castTo): mixed
    {
        if (!\in_array(
            $castTo,
            static::PRIMITIVE_CASTS,
            true
        )) {
            return null;
        }

        switch ($castTo) {
            case 'int':
                return (int) $data;
                break;

            case 'integer':
                return (int) $data;
                break;

            case 'string':
                return (string) $data;
                break;

            case 'object':
                return (object) $data;
                break;

            case 'array':
                return (array) $data;
                break;

            case 'float':
                return (float) $data;
                break;

            default:
                return $data ?? \null;
                break;
        }
    }

    /**
     * function castIt
     *
     * alias to static::castItToIf
     *
     * @param mixed $data
     * @param string $castTo
     * @param string|callable $validator
     *
     * @return mixed
     */
    public static function castIt(
        mixed $data,
        string|callable $castTo,
        string|array|bool|null|callable $validator = \null
    ): mixed {
        return static::castItToIf($data, $castTo, $validator ?? true);
    }

    /**
     * function isValid
     *
     * @param callable $validator
     * @param mixed $data
     *
     * @return bool
     */
    public static function isValid(
        callable $validator,
        mixed $data
    ): bool {
        return (bool) \call_user_func($validator, $data);
    }

    /**
     * function notIs
     *
     * @param callable $validator
     * @param mixed $data
     *
     * @return bool
     */
    public static function notIs(
        mixed $data,
        callable $validator
    ): bool {
        return (bool) \call_user_func($validator, $data);
    }

    /**
     * function prefixSum
     *
     * @param string|null $stringToCheck
     * @param array $prefixes
     *
     * @return int
     */
    public static function prefixSum(
        string|null $stringToCheck,
        array $prefixes,
        null|int $acceptedOccurrences = 1
    ): int {
        if (!trim((string) $stringToCheck)) {
            return \false;
        }
        $internalCounter = 0;

        foreach ($prefixes as $prefix) {
            $prefix = trim((string) $prefix);

            if (!$prefix) {
                continue;
            }

            if (
                \str_starts_with($stringToCheck, $prefix) &&
                (
                    $acceptedOccurrences > 0
                    ? \substr_count($stringToCheck, $prefix) === 1
                    : \true
                )
            ) {
                $internalCounter++;
            }
        }

        return $internalCounter;
    }
}
