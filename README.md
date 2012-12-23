Kohana View Table
=================

Kohana module to display a table from database resource.

E.g: (simple) 
 
  $model = ORM::factory('user');
  $table = ViewTable::factory($model);
  // display output
  echo $table->render();

