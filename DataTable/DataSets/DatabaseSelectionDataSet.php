<?php

namespace DataTable\DataSets;

class DatabaseSelectionDataSet extends \Nette\Object implements IDataSet
{
    private $selection;

    public function __construct(\Nette\Database\Table\Selection $selection)
    {
        $this->selection = $selection;
    }

    public function setLimit($limit, $offset = null)
    {
        $this->selection = $this->selection->limit($limit, $offset);
    }

    public function setOrderBy($column, $asc = true)
    {
        $this->selection = $this->selection->order($column . ($asc ? '' : ' DESC'));
    }

    public function getCount()
    {
        return $this->selection->count('*');
    }

    public function getData()
    {
        return $this->selection;
    }
}
