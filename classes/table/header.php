<?php

class Table_Header
{

    public $title;
    public $order;
    public $column;
    public $ordering;
    public $table;

    public function render()
    {
        if ($this->ordering AND $this->table->count)
        {
            return html::anchor(
                            Request::current()->uri()
                            . URL::query(
                                    array_merge(
                                            Request::current()->query(), array('column' => $this->column, 'order' => $this->order
                                            )
                                    )
                            ), $this->title);
        }
        else
        {
            if($this->title)
            {
                return $this->title;
            }
            else
            {
                return $this->column;
            }    
        }
    }

}