<?php declare(strict_types = 1);
require __DIR__ . '/bootstrap.php';

use Tester\Assert;
use function Interop\EqualableUtils\equals;

// primitives: integers
Assert::true(equals(0, 0));
Assert::true(equals(1, 1));
Assert::false(equals(1, 0));

// primitives: mixed (strict comparison)
Assert::false(equals("", 0));
Assert::false(equals(0, '0'));
Assert::false(equals('0', 0));

// primitives: strings
Assert::true(equals('', ''));
Assert::true(equals('hi', 'hi'));
Assert::false(equals('hi', 'hello'));

// nulls
Assert::true(equals(null, null));
Assert::false(equals(new stdClass(), null));
Assert::false(equals(null, new stdClass()));

// Datetime
Assert::true(equals(new DateTime('2017-03-24 11:11:11'), new DateTime('2017-03-24 11:11:11')));
Assert::false(equals(new DateTime('2017-03-24 11:11:11'), new DateTimeImmutable('2017-03-24 11:11:11')));

class HashableYes implements Ds\Hashable {
	public function equals($other): bool {return TRUE;}
	public function hash(): string {throw new LogicException('Not implemented');}
}
class HashableNo implements Ds\Hashable {
	public function equals($other): bool {return FALSE;}
	public function hash(): string {throw new LogicException('Not implemented');} // always return something different
}

// different type
Assert::true(equals(new HashableYes(), new HashableYes()));
Assert::false(equals(new HashableYes(), new class extends HashableYes {}));
Assert::false(equals(new HashableNo(), new HashableNo()));

// heuristics: is()
class Equalable_heuristic_is_YES {
	public function is() {return true;}
}
class Equalable_heuristic_is_NO {
	public function is() {return false;}
}
Assert::true(equals(new Equalable_heuristic_is_YES(), new Equalable_heuristic_is_YES()));
Assert::false(equals(new Equalable_heuristic_is_NO(), new Equalable_heuristic_is_NO()));

// heuristics: isEqualTo()
class Equalable_heuristic_isEqualTo_YES {
	public function isEqualTo() {return true;}
}
class Equalable_heuristic_isEqualTo_NO {
	public function isEqualTo() {return false;}
}
Assert::true(equals(new Equalable_heuristic_isEqualTo_YES(), new Equalable_heuristic_isEqualTo_YES()));
Assert::false(equals(new Equalable_heuristic_isEqualTo_NO(), new Equalable_heuristic_isEqualTo_NO()));

// heuristics: equals()
class Equalable_heuristic_equals_YES {
	public function equals() {return true;}
}
class Equalable_heuristic_equals_NO {
	public function equals() {return false;}
}
Assert::true(equals(new Equalable_heuristic_equals_YES(), new Equalable_heuristic_equals_YES()));
Assert::false(equals(new Equalable_heuristic_equals_NO(), new Equalable_heuristic_equals_NO()));


// Arrays

Assert::true(equals([TRUE], [TRUE]));
Assert::false(equals([TRUE], [1]));
Assert::true(equals([1], [1]));
Assert::true(equals([new DateTime('2017-03-24 11:11:11')], [new DateTime('2017-03-24 11:11:11')]));
Assert::false(equals([new DateTime('2017-03-24 11:11:11')], [new DateTimeImmutable('2017-03-24 11:11:11')]));

// Arrays order of items
Assert::false(equals(
	[TRUE, FALSE],
	[FALSE, TRUE]
));

Assert::true(equals(
	[new DateTime('2017-03-24 11:11:11'), new DateTime('2017-03-24 22:22:22')],
	[new DateTime('2017-03-24 11:11:11'), new DateTime('2017-03-24 22:22:22')]
));

Assert::false(equals(
	[new DateTime('2017-03-24 22:22:22'), new DateTime('2017-03-24 11:11:11')],
	[new DateTime('2017-03-24 11:11:11'), new DateTime('2017-03-24 22:22:22')]
));

Assert::false(equals(
	[new DateTimeImmutable('2017-03-24 11:11:11'), new DateTime('2017-03-24 22:22:22')],
	[new DateTime('2017-03-24 11:11:11'), new DateTimeImmutable('2017-03-24 22:22:22')]
));
