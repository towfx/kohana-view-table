<?php

class Table_Column
{
    public $name;
    public $title;
    public $url;
    public $class;
    public $column;
    public $header;
    public $cell;
    public $ordering;
    public $callback;
    public $parameters;

    public static function factory($column, $title, $sortable=false)
    {
        $column = new Table_Column;
        return $column;
    }
/*
    public function init_header()
    {
        $this->header = new Table_Header;
        $this->header->title = $this->title;
        $this->header->column = $this->column;
        $this->header->table = & $this;
    }
*/
    public function column($name)
    {
        $this->column = $name;
    }

    public function title($name)
    {
        $this->title = $name;
    }

    public function callback($assigned)
    {
        $this->callback = $assigned;
    }

    public function __toString()
    {
        $this->render_header();
    }

}