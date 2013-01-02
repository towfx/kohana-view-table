Kohana View Table
=================

Kohana module to display a table from database resource. This module intended to make thinner controller
by keeping important objects in the separate model.

E.g: (basic usage) 
    
    class Model_Table_User extends View_Table
    {
        public function model()
        {
            $this->model = ORM::factory("user")
        }
    }

    $table = new Model_Table_User;

    // display all data and columns of the ORM
    echo $table->render();

