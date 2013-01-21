<?php

namespace DataTable;

use \Nette\Application\UI\Control;

class DataTable extends Control
{
    const ASC = 'asc';

    const DESC = 'desc';

    const DEFAULT_ITEMS_PER_PAGE = 10;

    /** @persistent */
    public $orderColumn;

    /** @persistent */
    public $orderDir = self::ASC;

    /** @persistent */
    public $page = 1;
    
    /** @persistent */
    public $itemsPerPage = self::DEFAULT_ITEMS_PER_PAGE;

    public $templates = array();

    /** @var \App\Model\DataSets\IDataSet */
    private $dataset;
    
    /** @var \Nette\Utils\Paginator */
    private $paginator;

    private $itemsPerPageOptions = array(self::DEFAULT_ITEMS_PER_PAGE, 25, 50);
    
    public $snippets = array();

    private $defaultOrderColumn;

    private $defaultOrderDir = self::ASC;

    /**
     * @param \DataTable\DataSets\IDataSet|\Nette\Database\Table\Selection $dataset
     * @throws \Nette\InvalidArgumentException
     */
    public function __construct($dataset)
    {
        parent::__construct();
        if ($dataset instanceof \Nette\Database\Table\Selection) {
            $dataset = new DataSets\DatabaseSelectionDataSet($dataset);
        }
        if (!$dataset instanceof DataSets\IDataSet) {
            throw new \Nette\InvalidArgumentException('Parameter $dataset must be an instance of \DataTable\DataSets\IDataSet or \Nette\Database\Table\Selection');
        }
        $this->dataset = $dataset;
        $this->paginator = new \Nette\Utils\Paginator();
        $this->setupDefaultTemplates();
    }

    private function setupDefaultTemplates()
    {
        $this->templates['column'] = __DIR__ . '/templates/DataTable.column.latte';
        $this->templates['info'] = __DIR__ . '/templates/DataTable.info.latte';
        $this->templates['itemsPerPage'] = __DIR__ . '/templates/DataTable.itemsPerPage.latte';
        $this->templates['paginator'] = __DIR__ . '/templates/DataTable.paginator.latte';
    }
    
    public function handleOrderBy($column, $asc = true)
    {
        $this->orderColumn = $column;
        $this->orderDir = $asc ? self::ASC : self::DESC;
        $this->redirectThis();
    }
    
    public function handleCancelOrderBy()
    {
        $this->orderColumn = null;
        $this->redirectThis();
    }
        
    public function handleSetPage($page)
    {
        $this->page = $page;
        $this->redirectThis();
    }
        
    public function handleSetItemsPerPage($items)
    {
        $this->itemsPerPage = $items;
        $this->redirectThis();
    }

    public function setDefaultOrder($column, $dir = self::ASC)
    {
        $this->defaultOrderColumn = $column;
        $this->defaultOrderDir = $dir;
    }

    public function getData()
    {
        $this->paginator->setItemsPerPage($this->itemsPerPage);
        $this->paginator->setItemCount($this->dataset->getCount());
        $this->paginator->setPage($this->page);
        $this->page = $this->paginator->getPage();
        
        $this->dataset->setLimit($this->paginator->length, $this->paginator->offset);
        // TODO: IDataSource::setOrderBy() is optional
        if (empty($this->orderColumn) && !empty($this->defaultOrderColumn)) {
            $this->dataset->setOrderBy($this->defaultOrderColumn, $this->defaultOrderDir === self::ASC);
        } elseif (!empty($this->orderColumn)) {
            $this->dataset->setOrderBy($this->orderColumn, $this->orderDir === self::ASC);
        }
        return $this->dataset->getData();
    }

    public function getDataSet()
    {
        return $this->dataset;
    }

    public function setItemsPerPageOptions(array $options)
    {
        $this->itemsPerPageOptions = $options;
    }
    
    /**
     * Based on Visual Paginator by David Grudl.
     * @copyright  Copyright (c) 2009 David Grudl
     * @license    New BSD License
     * @link       http://extras.nettephp.com
     * @package    Nette Extras
     */
    public function renderPaginator()
    {
        if ($this->paginator->pageCount < 2) {
            $steps = array($this->page);
        } else {
            $arr = range(max($this->paginator->firstPage, $this->page - 3), min($this->paginator->lastPage, $this->page + 3));
            $count = 4;
            $quotient = ($this->paginator->pageCount - 1) / $count;
            for ($i = 0; $i <= $count; $i++) {
                $arr[] = round($quotient * $i) + $this->paginator->firstPage;
            }
            sort($arr);
            $steps = array_values(array_unique($arr));
        }
        $this->template->steps = $steps;
        $this->template->paginator = $this->paginator;
        
        $this->template->setFile($this->templates['paginator']);
        $this->template->render();
    }
    
    public function renderColumn($title, $name = null)
    {
        $this->template->name = $name;
        $this->template->title = $title;
        $this->template->orderColumn = $this->orderColumn;
        $this->template->orderDir = $this->orderDir;

        $this->template->setFile($this->templates['column']);
        $this->template->render();
    }

    public function renderItemsPerPage(array $options = null)
    {
        $this->template->itemsPerPage = $this->itemsPerPage;
        $this->template->options = $options ?: $this->itemsPerPageOptions;

        $this->template->setFile($this->templates['itemsPerPage']);
        $this->template->render();
    }

    public function renderInfo()
    {
        $this->template->paginator = $this->paginator;

        $this->template->setFile($this->templates['info']);
        $this->template->render();
    }
    
    protected function redirectThis()
    {
        foreach ($this->snippets as $snippet) {
            $this->parent->invalidateControl($snippet);
        }
        if (!$this->presenter->isAjax()) {
            $this->redirect('this');
        }
    }
}
