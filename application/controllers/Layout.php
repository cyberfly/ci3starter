<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Layout extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function table()
    {
        $this->render('templates/table');
    }

    public function form()
    {
        $this->render('templates/form');
    }

    public function card()
    {
        $this->render('templates/card');
    }

    public function button()
    {
        $this->render('templates/button');
    }

    public function chart()
    {
        $this->render('templates/chart');
    }

    public function register()
    {
        $this->render('templates/register','public_template');
    }

    public function login()
    {
        $this->render('templates/login','public_template');
    }
}
