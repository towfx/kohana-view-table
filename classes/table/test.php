<?php

class Table_Test extends Table
{

    protected $per_page = 3;

    public function __construct()
    {
        $this->title = __('Users List');
    }

    public function columns()
    {
        $username = new Table_Column;
        $username->column = 'username';
        $username->title = __('User Name');
        $username->ordering = true;

        $fullname = new Table_Column;
        $fullname->column = 'fullname';
        $fullname->title = __('Fullname');

        $email = new Table_Column;
        $email->column = 'email';
        $email->title = __('E-mail');
        $email->ordering = true;

        $check = new Table_Column;
        $check->column = 'checkbox';
        $check->title = form::checkbox('check-all');
        $check->class = 'align-center';

        return array(
            $username,
            $fullname,
            $email,
            $check
        );
    }

    public function form(Formo_Form $form = null)
    {
        $statuses = array(
            1 => 'Active',
            2 => 'Disabled'
        );

        $form = Formo::form();

        $form
                ->add('fullname', 'input')
                ->add_group('status', 'select', $statuses);

        return $form;
    }

    public function model($model = null)
    {
        // put basic ORM object here
        $model = ORM::factory('user');
        return $model;
    }

    public function query($model)
    {
        $model->with('info');
        return $model;
    }

    public function filter($model, $form)
    {
        if ($form->status->val())
        {
            $model->where('user.status', '=', $this->form->status->val());
        }

        if ($form->fullname->val())
        {
            $model->where('info.fullname', 'LIKE', '%' . $this->form->fullname->val() . '%');
        }
        return $model;
    }

    /**
     * By default callback_{COLUMN NAME} will be call
     * @param type $row_data
     * @return type
     */
    public function callback_username($row_data)
    {
        return html::anchor('whatever/path/' . $row_data->id, $row_data->username);
    }

    public function callback_fullname($user)
    {
        return $user->info->fullname;
    }

    public function callback_checkbox($user)
    {
        return form::checkbox('user[' . $user->id . ']');
    }

}