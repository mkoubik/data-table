<?php

use Tester\Assert;
use DataTable\DataSets\ArrayDataSet;

require __DIR__ . '/../bootstrap.php';

$dataset = new ArrayDataSet(array(1, 2, 3, 4, 5));
Assert::same(5, $dataset->getCount());

$dataset->setLimit(2, 1);
Assert::same(5, $dataset->getCount());
