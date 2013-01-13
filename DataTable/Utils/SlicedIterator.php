<?php

namespace DataTable\Utils;

class SlicedIterator implements \Iterator
{
    private $iterator;

    private $position = 0;

    private $initialized = false;

    private $limit;
    private $offset;
    
    public function __construct(\Iterator $iterator, $limit = null, $offset = null)
    {
        $this->iterator = $iterator;
        $this->limit = $limit;
        $this->offset = $offset;
    }

    public function current()
    {
        $this->initialize();
        if ($this->valid()) {
            return $this->iterator->current();
        }
    }

    public function key()
    {
        $this->initialize();
        if ($this->valid()) {
            return $this->iterator->key();
        }
    }

    public function next()
    {
        $this->initialize();
        $this->iterator->next();
        $this->position++;
    }

    public function rewind()
    {
        $this->position = 0;
        $this->iterator->rewind();
        if ($this->offset !== null) {
            for ($i = 0; $i < $this->offset; $i++) {
                $this->iterator->next();
            }
        }
    }

    public function valid()
    {
        $this->initialize();
        return (($this->limit === null || $this->position < $this->limit) && $this->iterator->valid());
    }

    private function initialize()
    {
        if ($this->initialized) {
            return;
        }
        $this->rewind();
        $this->initialized = true;
    }
}
