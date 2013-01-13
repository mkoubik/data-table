<?php

use Tester\Assert;
use DataTable\DataTable;

require __DIR__ . '/bootstrap.php';

$mockista = new \Mockista\Registry();

class MockPresenter extends \Nette\Application\UI\Presenter {
	public function renderDefault()
	{
		$this->terminate();
	}
}

$config = new \Nette\Config\Configurator();
$config->setTempDirectory(TEMP_DIR);
$config->addConfig(__DIR__ . '/DataTable.renderColumn.config.neon', FALSE);
$context = $config->createContainer();

$presenter = new MockPresenter($context);

$dataset = $mockista->create('DataTable\DataSets\IDataSet');

$table = new DataTable($dataset);
$table->templates['column'] = __DIR__ . '/templates/DataTable.renderColumn.latte';

$presenter['table'] = $table;
$presenter->run(new \Nette\Application\Request('Mock', 'GET', array()));

$table->orderColumn = 'orderColumn';
ob_start();
$table->renderColumn('title', 'name');
$html = ob_get_contents();
ob_end_flush();
Assert::same(file_get_contents(__DIR__ . '/expected/DataTable.renderColumn.html'), $html);

$mockista->assertExpectations();
