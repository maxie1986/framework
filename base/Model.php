<?php
/**
 * Created by PhpStorm.
 * User: maxi
 * Date: 3/17/17
 * Time: 8:41 PM
 */

namespace Framework\Base;


class Model
{

    /**
     * DB table name
     * @var $table string
     */
    protected $table;

    /**
     * Model constructor.
     * @param string $table
     */
    public function __construct($table)
    {
        $this->table = $table;
    }


}