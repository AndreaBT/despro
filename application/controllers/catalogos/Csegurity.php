<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Csegurity extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function LoadLogin()
    {
        $this->load->view("segurity/login");
    }
}