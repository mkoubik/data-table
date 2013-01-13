<?php

namespace DataTable\DataSets;

interface IDataSet
{
    /**
     * Sets limit and offset
     * @param int|null $limit  limit
     * @param int|null $offset offset
     */
    public function setLimit($limit, $offset = null);

    /**
     * Implementation of this method is optional.
     * @throws \Nette\NotImplementedException
     */
    public function setOrderBy($column, $asc = true);

    /**
     * Total count of records (ignoring limit and offset).
     * @return int count
     */
    public function getCount();

    /**
     * @return \ITraversable
     */
    public function getData();
}
