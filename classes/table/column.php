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
        
    public function factory()
    {
        $column = new View_Table_Column;
        return $column;
    }        
    
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