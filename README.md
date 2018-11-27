# Equalable utils

Are objects equal or not? PHP defines only instance equality using `===` operator. Then there is comparision by value `==` which compares by object values, but user-land code cannot modify how they are compared.

This is why universal [`equals()` method exists in Java](https://docs.oracle.com/javase/7/docs/api/java/lang/Object.html#equals(java.lang.Object)). PHP is missing anything like that.

This is why I have implemented global `equals()` function provides logic equality of objects. Which objects are equal and which not can be modified by several things - see bellow.

## Installation

```bash
composer require grifart/equalable-utils
```

## Implementing logical equality

There are several ways of implementhing logic equality.

### `\Ds\Hashable` (from PHP DS extension)

[Hashable](http://php.net/manual/en/class.ds-hashable.php) is PHP clone of Java `equals()` and `hashCode()` method. If you like to use implementing objects in collection this would make the most sense.

### `\Comparable` interface proposal

This library supports [`\Comparable`](https://github.com/grifart/comparable-polyfill) interface and tries to compare objects.

### Just implement equals($other), is($other) or isEqualTo($other) methods

This methods must behave the same as [Java `equals()` method](https://docs.oracle.com/javase/7/docs/api/java/lang/Object.html#equals(java.lang.Object)).

