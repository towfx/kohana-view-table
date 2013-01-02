<?php

class Table_Cell
{
    public $column;
    public $table;
    public $class;
    
    public function render($data)
    {
        $callback = 'callback_' . $this->column;

        //auto detect callback
        if (method_exists($this->table, $callback))
        {
            return call_user_func(array($this->table, $callback), $data);
        }
        else
        {
            if($this->column)
            {
                $columns = $data->list_columns();
                
                if(key_exists($this->column, $columns))
                {
                    return $data->{$this->column};
                }
            }    
           
        }
        
        return 'column '.$data->table_name().'->'.$this->column.' and '. $callback.' are not exist.';
       // return Debug::vars($this);
    }

}