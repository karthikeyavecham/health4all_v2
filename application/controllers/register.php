<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Register extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('register_model');
		$this->load->model('staff_model');
		$this->data['op_forms']=$this->staff_model->get_forms("OP");
		$this->data['ip_forms']=$this->staff_model->get_forms("IP");
	}
	public function op()
	{
		$this->load->helper('form');
		if($this->session->userdata('hospital')){
		
			$data['departments']=$this->staff_model->get_departments();
			$data['districts']=$this->staff_model->get_districts();
			$data['userdata']=$this->session->userdata('logged_in');
			$this->data['title']="Out-Patient Registration";
			$this->load->view('templates/header',$this->data);
			$this->load->library('form_validation');
			
			$this->form_validation->set_rules('first_name', 'Patient Name',
			'trim|required|xss_clean');
			$this->form_validation->set_rules('gender', 'Gender', 
			'trim|required|xss_clean');
			if ($this->form_validation->run() === FALSE)
			{
				$this->load->view('pages/op_registration');
			}
			else{
				$data['registered']=$this->register_model->register_op();
					$this->load->view('pages/op_registration',$data);

			}
			$this->load->view('templates/footer');
			
		}
		else{
			redirect('home','refresh');
		}
	}
	public function custom_form($form_id="",$visit_id=0)
	{
		$this->load->helper('form');
		if($this->session->userdata('hospital')){
			if($form_id=="")
				show_404();
			else{
			$data['departments']=$this->staff_model->get_departments();
			$data['units']=$this->staff_model->get_units();
			$data['areas']=$this->staff_model->get_areas();
			$data['districts']=$this->staff_model->get_districts();
			$data['userdata']=$this->session->userdata('logged_in');
			$this->data['title']="Patient Registration";
			$data['form_id']=$form_id;

			$data['fields']=$this->staff_model->get_form_fields($form_id);
			if(count($data['fields'])==0){
				show_404();
			}
			$form=$this->staff_model->get_form($form_id);
			$data['columns']=$form->num_columns;
			$data['form_name']=$form->form_name;
			$data['form_type']=$form->form_type;
			$data['patient']=array();
			$data['update']=0;
			$print_layout_page=$form->print_layout_page;
			$this->load->view('templates/header',$this->data);
			$this->load->library('form_validation');
			$this->form_validation->set_rules('form_type','Form Type','trim|xss_clean');
			if ($this->form_validation->run() === FALSE)
			{
				$this->load->view('pages/custom_form',$data);
			}
			else{
				if($this->input->post('search_patients')){
					$data['patients']=$this->register_model->search();
				}
				else if($this->input->post('select_patient') && $visit_id!=0){
					$data['patient']=$this->register_model->select($visit_id);
					if($this->input->post('visit_type')=="IP"){
						$data['update']=1;
					}
				}
				else if($this->input->post('register')){
					$data['registered']=$this->register_model->register();
					$data['print_layout']="pages/print_layouts/$print_layout_page";
				}
				$this->load->view('pages/custom_form',$data);

			}	
			$this->load->view('templates/footer');
			}
		}
		else{
			redirect('home','refresh');
		}
		
	}
}

