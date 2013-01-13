<?php

use Tester\Assert;
use DataTable\DataSets\ArrayDataSet;

require __DIR__ . '/../bootstrap.php';

$dataset = new ArrayDataSet(array(1, 2, 3));

Assert::throws(function() use ($dataset) {
	$dataset->setOrderBy('column');
}, 'Nette\\NotImplementedException');
