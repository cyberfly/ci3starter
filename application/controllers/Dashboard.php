<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $dashboard_breadcrumb = $this->dashboard_breadcrumb;

        $this->data['breadcrumb'] = getBreadcrumb($dashboard_breadcrumb);
        $this->data['page_js'] = ['user/chart-js'];

        $this->render('templates/table');
    }

    public function template()
    {
        $this->render('user/template');
    }

    public function table()
    {
        $this->render('layout/table');
    }

    public function chart()
    {
        $this->render('layout/chart');
    }

    public function tree()
    {
        $this->data['jsonTree'] = $this->mtree->getMasterTree();

        $this->render('layout/tree');
    }

    public function tree2()
    {
        $this->data['jsonTree'] = $this->mtree->getMasterTree();

        $this->render('layout/tree2');
    }

    public function tree3()
    {
        $this->data['jsonTree'] = $this->mtree->getMasterTree();

        $this->render('layout/tree3');
    }

    public function tree4()
    {
        // $this->$this->data['jsonTree'] = $this->mtree->getMasterTree();

        $this->render('layout/tree4');
    }
}
