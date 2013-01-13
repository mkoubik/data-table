<?php

use Tester\Assert;
use DataTable\DataSets\ArrayDataSet;

require __DIR__ . '/../bootstrap.php';

new ArrayDataSet(array(1, 2, 3));

new ArrayDataSet(new \ArrayIterator(array('a', 'b', 'c')));

new ArrayDataSet(new \Nette\ArrayHash(array('a', 'b', 'c')));

Assert::throws(function() {
	new ArrayDataSet('lorem ipsum');
}, 'InvalidArgumentException');
