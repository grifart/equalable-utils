<?php declare(strict_types=1);

namespace Interop\EqualableUtils;

/**
 * Compares two values on semantic level.
 *
 * When objects implements {@see Hashable} interface, it is used. Otherwise, common methods:
 * `equals()`, `is()`, `isEqualTo()` are used . If this heuristics fails, the `===` comparision is used as
 * the last option.
 *
 * There are some exceptions for PHP built-in value objects (DateTime, ...)
 *
 * @param mixed $one
 * @param mixed $other
 * @return bool
 */
function equals($one, $other): bool
{
	return Utils::equals($one, $other);
}
