<?php

namespace DataTable\DataSets;

class ArrayDataSet implements IDataSet
{
    private $data;

    private $limit;
    private $offset;

    public function __construct($data)
    {
        if (is_array($data)) {
            $data = \Nette\ArrayHash::from($data);
        }
        if (!$data instanceof \Traversable || !$data instanceof \Countable) {
            throw new \Nette\InvalidArgumentException('Parameter $data must be an array or implement \\Traversable and \\Countable');
        }
        $this->data = $data;
    }

    public function setLimit($limit, $offset = null)
    {
        $this->limit = $limit;
        $this->offset = $offset;
    }

    public function getCount()
    {
        return count($this->data);
    }

    public function getData()
    {
   	    $iterator = ($this->data instanceof \IteratorAggregate) ? $this->data->getIterator() : $this->data;
   	    if (is_array($iterator)) {
            return array_slice($iterator, $this->offset, $this->limit);
   	    } else {
            return new \DataTable\Utils\SlicedIterator($iterator, $this->limit, $this->offset);
   	    }
    }

    public function setOrderBy($column, $asc = true)
    {
        throw new \Nette\NotImplementedException('You cannot sort ' . __CLASS__ . ' data set.');
    }
}
