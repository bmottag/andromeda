<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Settings extends CI_Controller {
	
    public function __construct() {
        parent::__construct();
        $this->load->model("settings_model");
		$this->load->helper('form');
    }
	
	/**
	 * employee List
     * @since 15/12/2016
     * @author BMOTTAG
	 */
	public function employee($state)
	{			
			$data['state'] = $state;
			
			if($state == 1){
				$arrParam = array("filtroState" => TRUE);
			}else{
				$arrParam = array("state" => $state);
			}
			
			$data['info'] = $this->general_model->get_user($arrParam);
			
			$data["view"] = 'employee';
			$this->load->view("layout", $data);
	}
	
    /**
     * Cargo modal - formulario Employee
     * @since 15/12/2016
     */
    public function cargarModalEmployee() 
	{
			header("Content-Type: text/plain; charset=utf-8"); //Para evitar problemas de acentos
			
			$data['information'] = FALSE;
			$data["idEmployee"] = $this->input->post("idEmployee");	
			
			$arrParam = array("filtro" => TRUE);
			$data['roles'] = $this->general_model->get_roles($arrParam);

			if ($data["idEmployee"] != 'x') {
				$arrParam = array(
					"table" => "user",
					"order" => "id_user",
					"column" => "id_user",
					"id" => $data["idEmployee"]
				);
				$data['information'] = $this->general_model->get_basic_search($arrParam);
			}
			
			$this->load->view("employee_modal", $data);
    }
	
	/**
	 * Update Employee
     * @since 15/12/2016
     * @author BMOTTAG
	 */
	public function save_employee()
	{			
			header('Content-Type: application/json');
			$data = array();
			
			$idUser = $this->input->post('hddId');

			$msj = "You have add a new Employee!!";
			if ($idUser != '') {
				$msj = "You have update an Employee!!";
			}			

			$log_user = $this->input->post('user');
			
			$result_user = false;

			$data["state"] = $this->input->post('state');
			if ($idUser == '') {
				$data["state"] = 1;//para el direccionamiento del JS, cuando es usuario nuevo no se envia state
				
				//Verify if the user already exist by the user name
				$arrParam = array(
					"column" => "log_user",
					"value" => $log_user
				);
				$result_user = $this->settings_model->verifyUser($arrParam);
			}

			if ($result_user) 
			{
				$data["result"] = "error";
				$data["mensaje"] = " Error. The User name already exist.";
				$this->session->set_flashdata('retornoError', '<strong>Error!!!</strong> The User name already exist.');
			} else {
					if ($this->settings_model->saveEmployee()) {
						$data["result"] = true;					
						$this->session->set_flashdata('retornoExito', $msj);
					} else {
						$data["result"] = "error";					
						$this->session->set_flashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
					}
			}

			echo json_encode($data);
    }
	
	/**
	 * Reset employee password
	 * Reset the password to '123456'
	 * And change the status to '0' to changue de password 
     * @since 11/1/2017
     * @author BMOTTAG
	 */
	public function resetPassword($idUser)
	{
			if ($this->settings_model->resetEmployeePassword($idUser)) {
				$this->session->set_flashdata('retornoExito', 'You have reset the Employee pasword to: 123456');
			} else {
				$this->session->set_flashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
			}
			
			redirect("/settings/employee/",'refresh');
	}	

	/**
	 * Change password
     * @since 15/4/2017
     * @author BMOTTAG
	 */
	public function change_password($idUser)
	{
			if (empty($idUser)) {
				show_error('ERROR!!! - You are in the wrong place. The ID USER is missing.');
			}
			
			$arrParam = array(
				"table" => "user",
				"order" => "id_user",
				"column" => "id_user",
				"id" => $idUser
			);
			$data['information'] = $this->general_model->get_basic_search($arrParam);
		
			$data["view"] = "form_password";
			$this->load->view("layout", $data);
	}
	
	/**
	 * Update user´s password
	 */
	public function update_password()
	{
			$data = array();			
			$data["titulo"] = "UPDATE PASSWORD";
			
			$newPassword = $this->input->post("inputPassword");
			$confirm = $this->input->post("inputConfirm");
			$userState = $this->input->post("hddState");
			
			//Para redireccionar el usuario
			if($userState!=2){
				$userState = 1;
			}
			
			$passwd = str_replace(array("<",">","[","]","*","^","-","'","="),"",$newPassword); 
			
			$data['linkBack'] = "settings/employee/" . $userState;
			$data['titulo'] = "<i class='fa fa-unlock fa-fw'></i>CHANGE PASSWORD";
			
			if($newPassword == $confirm)
			{					
					if ($this->settings_model->updatePassword()) {
						$data["msj"] = "You have update the password.";
						$data["msj"] .= "<br><strong>User name: </strong>" . $this->input->post("hddUser");
						$data["msj"] .= "<br><strong>Password: </strong>" . $passwd;
						$data["clase"] = "alert-success";
					}else{
						$data["msj"] = "<strong>Error!!!</strong> Ask for help.";
						$data["clase"] = "alert-danger";
					}
			}else{
				//definir mensaje de error
				echo "pailas no son iguales";
			}
						
			$data["view"] = "template/answer";
			$this->load->view("layout", $data);
	}
	
	/**
	 * Company List
     * @since 15/12/2016
     * @author BMOTTAG
	 */
	public function company()
	{
			//se filtra por company_type para que solo se pueda editar los subcontratistas
			$arrParam = array(
				"table" => "param_company",
				"order" => "id_company",
				"column" => "company_type",
				"id" => 2
			);
			$data['info'] = $this->general_model->get_basic_search($arrParam);
			
			$data["view"] = 'company';
			$this->load->view("layout", $data);
	}
	
    /**
     * Cargo modal - formulario company
     * @since 15/12/2016
     */
    public function cargarModalCompany() 
	{
			header("Content-Type: text/plain; charset=utf-8"); //Para evitar problemas de acentos
			
			$data['information'] = FALSE;
			$data["idCompany"] = $this->input->post("idCompany");	
			
			if ($data["idCompany"] != 'x') {
				$arrParam = array(
					"table" => "param_company",
					"order" => "id_company",
					"column" => "id_company",
					"id" => $data["idCompany"]
				);
				$data['information'] = $this->general_model->get_basic_search($arrParam);
			}
			
			$this->load->view("company_modal", $data);
    }
	
	/**
	 * Update Company
     * @since 15/12/2016
     * @author BMOTTAG
	 */
	public function save_company()
	{			
			header('Content-Type: application/json');
			$data = array();
			
			$idCompany = $this->input->post('hddId');
			
			$msj = "You have add a new company!!";
			if ($idCompany != '') {
				$msj = "You have update a company!!";
			}

			if ($idCompany = $this->settings_model->saveCompany()) {
				$data["result"] = true;
				$data["idRecord"] = $idCompany;
				
				$this->session->set_flashdata('retornoExito', $msj);
			} else {
				$data["result"] = "error";
				$data["idRecord"] = "";
				
				$this->session->set_flashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
			}

			echo json_encode($data);	
    }
	
	/**
	 * job List
     * @since 15/12/2016
     * @author BMOTTAG
	 */
	public function job()
	{
			$this->load->model("general_model");
			$arrParam = array(
				"table" => "param_jobs",
				"order" => "job_description",
				"id" => "x"
			);
			$data['info'] = $this->general_model->get_basic_search($arrParam);
			
			$data["view"] = 'job';
			$this->load->view("layout", $data);
	}
	
    /**
     * Cargo modal - formulario job
     * @since 15/12/2016
     */
    public function cargarModalJob() 
	{
			header("Content-Type: text/plain; charset=utf-8"); //Para evitar problemas de acentos
			
			$data['information'] = FALSE;
			$data["idJob"] = $this->input->post("idJob");	
			
			if ($data["idJob"] != 'x') {
				$this->load->model("general_model");
				$arrParam = array(
					"table" => "param_jobs",
					"order" => "id_job",
					"column" => "id_job",
					"id" => $data["idJob"]
				);
				$data['information'] = $this->general_model->get_basic_search($arrParam);
			}
			
			$this->load->view("job_modal", $data);
    }
	
	/**
	 * Update job
     * @since 15/12/2016
     * @author BMOTTAG
	 */
	public function save_job()
	{			
			header('Content-Type: application/json');
			$data = array();
			
			$idJob = $this->input->post('hddId');
			
			$msj = "You have add a new job!!";
			if ($idJob != '') {
				$msj = "You have update a Job!!";
			}

			if ($idJob = $this->settings_model->saveJob()) {
				$data["result"] = true;		
				$this->session->set_flashdata('retornoExito', $msj);
			} else {
				$data["result"] = "error";
				$this->session->set_flashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
			}

			echo json_encode($data);
    }
	
	/**
	 * Hazard Activity List
     * @since 5/2/2017
     * @author BMOTTAG
	 */
	public function hazardActivity()
	{
			$this->load->model("general_model");
			$arrParam = array(
				"table" => "param_hazard_activity",
				"order" => "hazard_activity",
				"id" => "x"
			);
			$data['info'] = $this->general_model->get_basic_search($arrParam);
			
			$data["view"] = 'hazard_activity';
			$this->load->view("layout", $data);
	}
	
    /**
     * Cargo modal - formulario hazard Activity
     * @since 5/2/2017
     */
    public function cargarModalHazardActivity() 
	{
			header("Content-Type: text/plain; charset=utf-8"); //Para evitar problemas de acentos
			
			$data['information'] = FALSE;
			$data["idHazardActivity"] = $this->input->post("idHazardActivity");	
			
			if ($data["idHazardActivity"] != 'x') {
				$this->load->model("general_model");
				$arrParam = array(
					"table" => "param_hazard_activity",
					"order" => "id_hazard_activity",
					"column" => "id_hazard_activity",
					"id" => $data["idHazardActivity"]
				);
				$data['information'] = $this->general_model->get_basic_search($arrParam);
			}
			
			$this->load->view("hazard_activity_modal", $data);
    }
	
	/**
	 * Update Hazard Activity
     * @since 5/2/2017
     * @author BMOTTAG
	 */
	public function save_hazard_activity()
	{			
			header('Content-Type: application/json');
			$data = array();
			
			$idHazardActivity = $this->input->post('hddId');
			
			$msj = "You have add a new Activity!!";
			if ($idHazardActivity != '') {
				$msj = "You have update an Activity!!";
			}

			if ($idHazardActivity = $this->settings_model->saveHazardActivity()) {
				$data["result"] = true;
				$data["idRecord"] = $idHazardActivity;
				
				$this->session->set_flashdata('retornoExito', $msj);
			} else {
				$data["result"] = "error";
				$data["idRecord"] = "";
				
				$this->session->set_flashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
			}

			echo json_encode($data);
    }
	
	/**
	 * hazard List
     * @since 15/12/2016
     * @author BMOTTAG
	 */
	public function hazard()
	{
			$data['info'] = $this->settings_model->get_hazard_list();

			$data["view"] = 'hazard';
			$this->load->view("layout", $data);
	}
	
    /**
     * Cargo modal - formulario hazard
     * @since 15/12/2016
     */
    public function cargarModalHazard() 
	{
			header("Content-Type: text/plain; charset=utf-8"); //Para evitar problemas de acentos
			
			$data['information'] = FALSE;
			$data["idHazard"] = $this->input->post("idHazard");	
			
			$this->load->model("general_model");
			$arrParam = array(
				"table" => "param_hazard_activity",
				"order" => "hazard_activity",
				"id" => "x"
			);
			$data['activityList'] = $this->general_model->get_basic_search($arrParam);
			
			$arrParam = array(
				"table" => "param_hazard_priority",
				"order" => "priority_description",
				"id" => "x"
			);
			$data['priorityList'] = $this->general_model->get_basic_search($arrParam);
			
			if ($data["idHazard"] != 'x') {
				$arrParam = array(
					"table" => "param_hazard",
					"order" => "id_hazard",
					"column" => "id_hazard",
					"id" => $data["idHazard"]
				);
				$data['information'] = $this->general_model->get_basic_search($arrParam);
			}
			
			$this->load->view("hazard_modal", $data);
    }
	
	/**
	 * Update hazard
     * @since 15/12/2016
     * @author BMOTTAG
	 */
	public function save_hazard()
	{			
			header('Content-Type: application/json');
			$data = array();
			
			$idHazard = $this->input->post('hddId');
			
			$msj = "You have add a new hazard!!";
			if ($idHazard != '') {
				$msj = "You have update a hazard!!";
			}

			if ($idHazard = $this->settings_model->saveHazard()) {
				$data["result"] = true;
				$data["mensaje"] = "Solicitud guardada correctamente.";
				$data["idRecord"] = $idHazard;
				
				$this->session->set_flashdata('retornoExito', $msj);
			} else {
				$data["result"] = "error";
				$data["mensaje"] = "Error al guardar. Intente nuevamente o actualice la p\u00e1gina.";
				$data["idRecord"] = "";
				
				$this->session->set_flashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
			}

			echo json_encode($data);
    }
	

	
}