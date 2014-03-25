<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_panel extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('reports_model');		
	}
	function op_layout(){
		if($this->session->userdata('logged_in')){
		$this->load->helper('form');
		$data['title']="User Panel";
		$data['userdata']=$this->session->userdata('logged_in');
		$data['fields']=$this->reports_model->get_form_fields();
		$this->load->view('templates/header',$data);
		$this->load->view('templates/panel_nav',$data);
		$this->load->view('templates/reports_nav',$data);
		$this->load->view('pages/panel_index',$data);
		$this->load->view('templates/footer');	
		}
		else{
			show_404();
		}
	}

}
