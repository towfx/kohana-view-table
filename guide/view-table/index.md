Kohana Table
=================

Kohana module to display a table from database resource. 

This module basically transform an ORM query into  HTML Table.

## How it works<sup>TM</sup>

We create another implementation class file. The model, queries, filters, form,
and callbacks are declared within this file.  

## Basic Usage

Below are the example to display the database table into html table.

### Table Declaration

The table must be declared in class. The basic is to specify the model.
The model function must return a resource. E.g. ORM model.
    
    class Table_User extends Table
    {
        public function model()
        {
            return ORM::factory("user");
        }
    }

### Initialize the object

Initialize the table class to start making table.

    $table = new Table_User;

### Print the output

Generate the table for the given resource.

    echo $table->render();

## Normal Usage

For the normal operations, the following task are required.

### Customizing Columns

The Table->columns() method must return a list of columns object.

    class Table_User extends Table
    {
        public function columns()
        {
            $username = new View_Table_Column;
            $username->column = 'username';
            $username->title = __('User Name');
            $username->ordering = true;

            $email = new View_Table_Column;
            $email->column = 'email';
            $email->title = __('E-mail');
            $email->ordering = false;

            // declare table has 2 colums, username and email
            return array(
                                $username,
                                $email
                            );
        }
    }

### Table Cell Callbacks

Declare callback to decorate table cells. By default the Table_Cell::render() will 
try to locate **Table->callback_{FIELD_NAME}()** method.

    class Table_User extends Table
    {
        public function callback_username($row_data)
        {
            return html::anchor('view/user/' . $row_data->id, $row_data->username);
        }
    }

#### Custom callback

Pass callback to $this->callback during initializing $this->columns into Table_Column

### Form to make queries

Declare forms inside **Table->form()** and return the form object. 

    class Table_User extends Table
    {
        public function form()
        {   
            $statuses = array(
                1=>'Active',
                2=>'Disabled'
            );

            $form = Formo::form();

            $form
            ->add('fullname', 'input')
            ->add_group('status', 'select', $statuses);

            return $form;
        }
    }

### Filtering data

Declare filtering inside **Table->filter()** and return the model
    
    class Model_Table_User extends Table
    {
        public function model()
        {
            $this->model = ORM::factory("user");
        }

        public function filter($model, $form)
        {
            if($form->status->val())
            {
                $model->where('user.status','=', $form->status->val());
            }

            if($form->fullname->val())
            {
                $model->where('info.fullname','LIKE', '%'.$form->fullname->val().'%');
            }    
            return $model;
        }
    }

### Additional Queries

Optional. All joins can be declared inside **Table->query()** and return the model.

    class Table_User extends Table
    {
        public function query($model)
        {
            $model->with('info');
            return $model;
        }
    }

### Custom HTML 

Limited custom HTML are supported to allow simple injection.

#### in header 

pass raw html 

    class Table_User extends Table
    {
        public function columns()
        {
            $username = new View_Table_Column;
            $username->column = 'username';
            $username->title = __('User Name');
            $username->ordering = true;

            $email = new View_Table_Column;
            $email->column = 'email';
            
            // ->title can be raw html
            $email->title = '<h3>Big Header</h3>';

            $email->ordering = false;

            return array(
                $username,
                $email
            );
        }
    }

#### in cell

Callback are intended for custom HTML
