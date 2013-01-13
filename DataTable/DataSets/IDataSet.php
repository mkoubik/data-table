<?php

namespace DataTable\DataSets;

interface IDataSet
{
    public function setLimit($limit, $offset = null);

    public function setOrderBy($column, $asc = true);

    public function getCount();

    public function getData();
}
