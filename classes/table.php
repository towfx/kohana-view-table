<?php

class Table
{

    protected $initialized;
    protected $title = '{TABLE TITLE}';
    protected $columns;

    protected $query;
    /**
     * The resource must be initalizeed by ORM
     * @var ORM 
     */
    protected $model;
    protected $_view;
    protected $_view_template = 'table/default';
    protected $pagination;
    protected $form;
    protected $per_page = 10;

    /**
     * Method form must return Formo object
     * @param Formo $form
     * @return Formo
     */
    public function form($form=null)
    {
        return Formo::form();
    }
    
    public function form_append($form)
    {
        return $form;
    }        

    public function form_after($form=null)
    {
        $form->attr = array('class' => 'list-query', 'method' => 'GET');
        $form->add('___','rawhtml','<p></p>')
        ->add('query', 'button', __('Query'), array('attr' => array('class' => 'unprint button')))
                ->add('print', 'button', __('Print'), array('attr' => array('class' => 'button print unprint')))
                ->add('page', 'hidden')
                ->add('column', 'hidden')
                ->add('order', 'hidden');
        
        $form->query->attr=array(
                'rel'=>'fam/icons/application_view_columns.png');

        $form = $this->form_append($form);
        
        $form->load($this->params());
        

        return $form;
    }
    
    public function params($query=null)
    {
        if($query)
        {
            $this->query = $query;
        }
        
        if(!$this->query)
        {
            $this->query = Request::current()->query();
        }
        
        return $this->query;
    }        

    /**
     * Set the model
     * @param ORM $model
     * @return Database_Query
     */
    public function model()
    {
        return ORM::factory($model);

        return DB::select($model);
    }

    
    public function query($model)
    {
        return $model;
    }        

    public function filter($model, $form)
    {
        return $model;
    }

    public function count($model)
    {
        $model = clone $model;

        return $model->count_all();
    }

    public function columns()
    {
        return array();
    }

    public function columns_discover($model)
    {
        $columns = array();
        if ($model instanceof ORM)
        {
            foreach ($model->list_columns() as $column)
            {
                $c = new Table_Column;
                $c->column = $column['column_name'];
                $c->title = $column['column_name'];
                $c->ordering = true;

                $columns[] = $c;
            }
        }
        elseif ($model instanceof Database_Query)
        {
            
        }
        else
        {
            throw new Exception('Model resource :res are not supported', array(':res', get_class($model)));
        }
        return $columns;
    }

    public function rows($model)
    {
        return $model->find_all();
    }

    public function order($model, $form)
    {
        if ($form->column->val())
        {
            switch ($form->order->val())
            {
                case 'up';
                    $order = 'ASC';
                    break;

                case 'down':
                    $order = 'DESC';
                    break;
            }
            $model->order_by($this->form->column->val(), $order);
        }
        return $model;
    }

    public function offset($model, $form)
    {
        if ($form->page->val())
        {
            $model->offset(($form->page->val() - 1) * $this->per_page);
        }
        return $model;
    }

    public function limit($model)
    {
        return $model->limit($this->per_page);
    }

    protected function dependency_checks()
    {
        
    }

    /**
     * Factory sequence. Execute all machines below!
     * @return void
     */
    protected function factory()
    {
        if (Kohana::DEVELOPMENT)
        {
            $this->dependency_checks();
        }


        // get the declared form
        $this->form = $this->form();
        
      

        // get the internally required fields
        $this->form = $this->form_after($this->form);
        

        $this->model = $this->model();

        $this->columns = $this->columns();
        if (empty($this->columns))
        {
            // auto generate from model fields
            $this->columns = $this->columns_discover($this->model);
        }

        // apply query
        $this->model = $this->query($this->model, $this->form);
        // apply filter
        $this->model = $this->filter($this->model, $this->form);

        // get count
        $this->count = $this->count($this->model, $this->form);
        // apply limit
        $this->model = $this->limit($this->model, $this->form);

      //  $m = clone $this->model;

     //   $this->count_found = $m->count_all();

        $this->model = $this->offset($this->model, $this->form);
        $this->model = $this->order($this->model, $this->form);


        //    echo Debug::vars($this->columns);

        foreach ($this->columns as $ref => &$column)
        {
            $column->header = new Table_Header;
            $column->header->title = $column->title;
            $column->header->column = $column->column;
            $column->header->table = & $this;

            if (isset($column->ordering) AND $column->ordering == true)
            {
                if ($this->form->column->val() == $column->column)
                {
                    
                }
                $column->header->ordering = true;
                switch ($this->form->order->val())
                {
                    case 'down':

                        $column->header->order = 'up';
                        break;

                    case 'up':

                    default:

                        $column->header->order = 'down';
                }
            }
            else
            {
                $column->header->ordering = false;
            }
            $column->cell = new Table_Cell;
            $column->cell->column = $column->column;
            $column->cell->table = & $this;
            $column->cell->class = $column->class;
            $column->cell->callback = $column->callback;
            
            $column->cell->parameters = $column->parameters;
        }

        $this->pagination =
                Pagination::factory(array(
                    'total_items' => $this->count,
                    'items_per_page' => $this->per_page,
                    'view' => 'pagination/floating',
                ));
    }

    public function render()
    {
        if (!$this->initialized)
        {
            $this->factory();
        }

        $this->_view = new View;

        //echo $this->count; exit;

        $this->_view->set_filename($this->_view_template)
                ->set('caption', $this->title)
                ->set('columns', $this->columns)
                ->set('rows', $this->rows($this->model))
                ->set('pagination', $this->pagination)
                ->set('form', $this->form)
                ->set('count', $this->count);

        return $this->_view;
    }

    public function __toString()
    {
        return $this->render();
    }

}