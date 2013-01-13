<?php

use Tester\Assert;
use DataTable\Utils\SlicedIterator;

require __DIR__ . '/../bootstrap.php';

$data = array('one', 'two', 'three', 'four', 'five', 'six');

$iterator = new SlicedIterator(new \ArrayIterator($data), 3);

Assert::same('one', $iterator->current());
Assert::same(0, $iterator->key());
Assert::true($iterator->valid());

$iterator->next();
Assert::same('two', $iterator->current());
Assert::same(1, $iterator->key());
Assert::true($iterator->valid());

$iterator->next();
Assert::same('three', $iterator->current());
Assert::same(2, $iterator->key());
Assert::true($iterator->valid());

$iterator->next();
Assert::same(null, $iterator->current());
Assert::same(null, $iterator->key());
Assert::false($iterator->valid());

$iterator->rewind();
Assert::same('one', $iterator->current());
Assert::same(0, $iterator->key());
Assert::true($iterator->valid());
