<?php

use Tester\Assert;
use DataTable\DataSets\ArrayDataSet;

require __DIR__ . '/../bootstrap.php';

$dataset = new ArrayDataSet(array(1, 2, 3, 4, 5));

$data = array();
foreach($dataset->getData() as $item) {
	$data[] = $item;
}
Assert::same(array(1, 2, 3, 4, 5), $data);

$dataset->setLimit(3, 1);
$data = array();
foreach($dataset->getData() as $item) {
	$data[] = $item;
}
Assert::same(array(2, 3, 4), $data);
