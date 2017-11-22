<?php

namespace Interop\EqualableUtils;

use Ds\Hashable;

final class Utils {

	private function __construct() // static class
	{
	}


	/**
	 * Compares two values by their meaning
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
	public static function equals($one, $other): bool
	{
		$_equals = function ($left, $right): bool {
			if(!is_object($left)) {
				return false;
			}

			if(get_class($left) !== get_class($right)) {
				return false;
			}

			if ($left instanceof Hashable) {
				return $left->equals($right);
			}

			if($left instanceof \Comparable) { // @see https://wiki.php.net/rfc/comparable
				return $left->compareTo($right) === 0;
			}

			if ($left instanceof \DateTimeInterface) {
				/** @noinspection TypeUnsafeComparisonInspection */
				return $left == $right; // @see http://php.net/manual/en/datetime.diff.php
			}

			if (is_object($left)) {
				foreach (['equals', 'is', 'isEqualTo'] as $methodName) {
					if (method_exists($left, $methodName)) {
						return $left->$methodName($right);
					}
				}
			}

			return FALSE;
		};

		$_equalsArray = function ($left, $right) use ($_equals): bool {
			if (!is_array($left) || !is_array($right)) {
				return FALSE;
			}

			if (array_keys($left) !== array_keys($right)) {
				return FALSE;
			}

			foreach($left as $key => $val) {
				if (!self::equals($left[$key], $right[$key])) {
					return FALSE;
				}
			}

			return TRUE;
		};

		return $one === $other || $_equals($one, $other) || $_equals($other, $one) || $_equalsArray($one, $other);
	}
}
