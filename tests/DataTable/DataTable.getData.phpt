<?php

use Tester\Assert;
use DataTable\DataTable;

require __DIR__ . '/bootstrap.php';

$mockista = new \Mockista\Registry();

$expectedData = array('lorem', 'ipsum', 'dolor', 'sit', 'amet');

$dataset = $mockista->create('DataTable\DataSets\IDataSet');
$dataset->expects('getCount')->once()->andReturn(DataTable::DEFAULT_ITEMS_PER_PAGE * 5);
$dataset->expects('setLimit')->once()->with(DataTable::DEFAULT_ITEMS_PER_PAGE, 0);
$dataset->expects('getData')->once()->andReturn($expectedData);

$table = new DataTable($dataset);
$data = $table->getData();

Assert::same($expectedData, $data);

$mockista->assertExpectations();
