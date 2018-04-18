<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class restserver extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->helper('url');
    }

    /**
     * @todo: Carregar o view principal
     * @param: Null
     * @return: HTML
     */   
    public function index(){
        $this->load->view('vw_rest_server');
    }

    /**
     * @todo: Carregar o formulário para incluir nova task
     * @param: POST[]
     * @return: HTML
     */
    public function addforms(){
        $this->load->view('vw_form_server');
    }
    
    /**
     * @todo: Carregar o formulário para alterar uma task
     * @param: POST[]
     * @return: HTML
     */
    public function updforms(){
        $this->load->view('vw_form_server_upd');
    }
    
    /**
     * @todo: Carregar o formulário para apagar uma task
     * @param: POST[]
     * @return: HTML
     */
    public function delforms(){
        $this->load->view('vw_form_server_del');
    }
}
