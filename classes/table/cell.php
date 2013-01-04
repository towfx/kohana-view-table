<?php

class Table_Cell
{

    public $column;
    public $table;
    public $class;
    public $callback;
    /**
     * The resolable parameters for the callback. 
     * 
     * array(
     *       // current row data
     *      ':data',
     * 
     *       // any column value
     *      ':username',
     * 
     *      // relationship
     *      'rel'   => 'direct_column.relation1.relation2',
     *      // direct input value     
     *      'raw' => array('class' => 'context-url', 'rel' => '#' . $block_id)
     * 
     *       // pattern replacement
     *      ':id' => 'admin/user/edit/:id', 
     * )
     *  
     * @var array
     */
    public $parameters;

    public function render($data)
    {
        if ($this->callback)
        {
            
            if (is_array($this->callback))
            {
                
              
                
                // callback is assigned, have to resolve the given callback
                $class = $this->callback[0];

                if (isset($this->callback[1]))
                {
                    $method = $this->callback[1];
                }
                else
                {
                    throw new Kohana_Exception('The object callback suppose to have method given.');
                }

                switch ($class)
                {
                    case ':data':
                        
                        $class = clone $data;
                        break;
                }
  //return Debug::vars($data->status);
                $callback = array($class, $method);
                
            }
            
            
                $arguments = array();
//echo Debug::vars($this->parameters); exit;
            if (is_array($this->parameters))
            {
                
                
                // resolve callback parameter
                

                foreach ($this->parameters as $search => $parameter)
                {                    
                    if ($parameter == ':data')
                    {
                        $arguments[] = $data;
                    }
                    elseif (is_int($search)) 
                    {
                        // $search is not a defined key, 
                        // resolve as data object attribute
                        $key = substr($parameter, 1);
                        $arguments[] = $data->{$key};
                    }
                    elseif ($search == 'rel') 
                    {           
                        // resolve as relationships
                         $arguments[] = $this->resolve_relationship($parameter, $data);
                                 
                    }
                    elseif ($search == 'raw') 
                    {
                        // take the given value literally
                        $arguments[] = $parameter;
                    }
                    else
                    {
                        // assume string replacement
                        $key = substr($search, 1);
                        $arguments[] = str_replace($search, $data->{$key}, $parameter);
                    }
                }              
            }
            else
            {
                $arguments = $this->parameters;
            }
            
            //return $data->status;
            if(is_array($arguments))
            {
                return call_user_func_array($callback, $arguments);
            }
            else
            {
                return call_user_func($callback, $arguments);
            }    
        }
        elseif (method_exists($this->table, $callback='td_' . $this->column))
        {
            // auto detect callback
            return call_user_func(array($this->table, $callback), $data);
        }
        elseif (strpos($this->column, '.'))
        {
            // is this a relationship?

            return $this->resolve_relationship($this->column, $data);
        }
        elseif ($this->column_exist($this->column, $data))
        {
            return $data->{$this->column};
        }

        return 'column ' . $data->table_name() . '->' . $this->column . ' and ' . $callback . ' are not exist.';
        // return Debug::vars($this);
    }

    public function column_exist($column, $data)
    {
        $columns = $data->list_columns();

        if (key_exists($column, $columns))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function resolve_relationship($relationship, $data)
    {

        $refs = explode('.', $relationship);

        $object = $data;

        foreach ($refs as $ref)
        {
            // go trough the given relationship
            $object = $object->{$ref};
        }
        return $object;
    }

}