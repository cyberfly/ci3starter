<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        //get all members
        $groups = array(2);

        $users = $this->ion_auth->users($groups)->result();

        $this->data['users'] = $users;

        $this->render('admin/user/index');
    }

    public function create()
    {
        //initial config

        $tables = $this->config->item('tables','ion_auth');
        $identity_column = $this->config->item('identity','ion_auth');
        $this->data['identity_column'] = $identity_column;

        $this->form_validation->set_rules('identity', 'ID Pengguna', 'required|numeric');
        $this->form_validation->set_rules('first_name', 'Nama', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('password', 'Email', 'required');


       if ($this->form_validation->run() == FALSE)
        {
            $this->data['title'] = 'Daftar';

            $this->data['page_js'] = array('admin/user/create-js');
            $this->render('admin/user/create');
        }
        else{

            $email    = strtolower($this->input->post('email'));
            $identity = ($identity_column==='email') ? $email : $this->input->post('identity');
            $password = $this->input->post('password');

            $additional_data = array(
                'first_name' => $this->input->post('first_name'),
                'last_name'  => $this->input->post('last_name'),
                'company'    => $this->input->post('company'),
                'phone'      => $this->input->post('phone'),
            );

            $this->ion_auth->register($identity, $password, $email, $additional_data);

            $this->session->set_flashdata('success', 'Pendaftaran Pengguna Baru Berjaya');

            redirect("admin/user", 'refresh');
        }
    }

    public function edit()
    {
        //initial config

        $tables = $this->config->item('tables','ion_auth');
        $identity_column = $this->config->item('identity','ion_auth');
        $this->data['identity_column'] = $identity_column;

        $this->form_validation->set_rules('identity', 'ID Pengguna', 'required|numeric');
        $this->form_validation->set_rules('first_name', 'Nama', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('password', 'Email', 'required');


        if ($this->form_validation->run() == FALSE)
        {
            $this->data['title'] = 'Daftar';

            $this->data['page_js'] = array('admin/user/create-js');
            $this->render('admin/user/create');
        }
        else{

            $email    = strtolower($this->input->post('email'));
            $identity = ($identity_column==='email') ? $email : $this->input->post('identity');
            $password = $this->input->post('password');

            $additional_data = array(
                'first_name' => $this->input->post('first_name'),
                'last_name'  => $this->input->post('last_name'),
                'company'    => $this->input->post('company'),
                'phone'      => $this->input->post('phone'),
            );

            $this->ion_auth->register($identity, $password, $email, $additional_data);

            $this->session->set_flashdata('success', 'Pendaftaran Pengguna Baru Berjaya');

            redirect("admin/user", 'refresh');
        }
    }

    public function delete()
    {

    }

}
