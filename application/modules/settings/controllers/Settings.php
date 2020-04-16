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
	
	/**
	 * Material List
     * @since 13/12/2016
     * @author BMOTTAG
	 */
	public function material()
	{
			$arrParam = array(
				"table" => "param_material_type",
				"order" => "material",
				"id" => "x"
			);
			$data['info'] = $this->general_model->get_basic_search($arrParam);
			
			$data["view"] = 'material';
			$this->load->view("layout", $data);
	}
	
    /**
     * Cargo modal - formulario material type
     * @since 13/12/2016
     */
    public function cargarModalMaterial() 
	{
			header("Content-Type: text/plain; charset=utf-8"); //Para evitar problemas de acentos
			
			$data['information'] = FALSE;
			$data["idMaterial"] = $this->input->post("idMaterial");	
			
			if ($data["idMaterial"] != 'x') {
				$arrParam = array(
					"table" => "param_material_type",
					"order" => "id_material",
					"column" => "id_material",
					"id" => $data["idMaterial"]
				);
				$data['information'] = $this->general_model->get_basic_search($arrParam);
			}
			
			$this->load->view("material_modal", $data);
    }
	
	/**
	 * Update material
     * @since 13/12/2016
     * @author BMOTTAG
	 */
	public function save_material()
	{			
			header('Content-Type: application/json');
			$data = array();
			
			$idMaterial = $this->input->post('hddId');
			
			$msj = "You have add a new Material Type!!";
			if ($idMaterial != '') {
				$msj = "You have update a Material Type!!";
			}

			if ($idMaterial = $this->settings_model->saveMaterial()) {
				$data["result"] = true;
				$data["idRecord"] = $idMaterial;
				
				$this->session->set_flashdata('retornoExito', $msj);
			} else {
				$data["result"] = "error";
				$data["idRecord"] = "";
				
				$this->session->set_flashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
			}

			echo json_encode($data);
    }
	
	/**
	 * vehicle List
     * @since 15/12/2016
	 * @review 5/5/2017
	 * Param int $companyType -> 1: VCI; 2: Subcontractor
	 * Param int $vehicleType -> 1: Pickup; 2: Construction Equipment; 3: Trucks; 4: Special Equipment; 99: Otros
     * @author BMOTTAG
	 */
	public function vehicle($companyType, $vehicleType=1, $vehicleState=1)
	{
			$data['companyType'] = $companyType;
			$data['vehicleType'] = $vehicleType;
			$data['vehicleState'] = $vehicleState;
			$data['title'] = $companyType==1?"VCI":"RENTALS";

			$arrParam = array(
				"companyType" => $companyType,
				"vehicleState" => $vehicleState
			);
			//si es estado en 1 entonces envio el tipo de vehiculo
			if($vehicleState==1){
				$arrParam['vehicleType'] = $vehicleType;
			}
			
			$data['info'] = $this->settings_model->get_vehicle_info_by($arrParam);//vehicle list
			$data["view"] = 'vehicle';
			$this->load->view("layout", $data);
	}
	
    /**
     * Cargo modal - formulario vehicle
     * @since 15/12/2016
	 * @review 27/12/2016
     */
    public function cargarModalVehicle() 
	{
			header("Content-Type: text/plain; charset=utf-8"); //Para evitar problemas de acentos
			
			$data['information'] = FALSE;
			$idVehicle = $this->input->post("idVehicle");
			//como se coloca un ID diferente para que no entre en conflicto con los otros modales, toca sacar el ID
			$porciones = explode("-", $idVehicle);
			
			$data["companyType"] = $porciones[0];
			$data["idVehicle"] = $porciones[1];		
			
			$arrParam = array(
				"table" => "param_company",
				"order" => "company_name",
				"column" => "company_type",
				"id" => 2
			);
			$data['company'] = $this->general_model->get_basic_search($arrParam);//company list
			
			//buscar la lista de tipo de vehiculo
			$arrParam = array(
				"table" => "param_vehicle_type_2",
				"order" => "type_2",
				"column" => "show_vehicle",
				"id" => 1
			);
			$data['vehicleType'] = $this->general_model->get_basic_search($arrParam);//vehicleType list
			
			if ($data["idVehicle"] != 'x') {
				$arrParam = array(
					"table" => "param_vehicle",
					"order" => "id_vehicle",
					"column" => "id_vehicle",
					"id" => $data["idVehicle"]
				);
				$data['information'] = $this->general_model->get_basic_search($arrParam);
			}
			
			$this->load->view("vehicle_modal", $data);
    }
	
	/**
	 * Update vehicle
     * @since 15/12/2016
	 * @review 27/12/2016
     * @author BMOTTAG
	 */
	public function save_vehicle()
	{			
			header('Content-Type: application/json');
			$data = array();
			
			$idVehicle = $this->input->post('hddId');
			$idCompany = $this->input->post('company');
			$data["compannyType"] = $idCompany==1?1:2; //1:VCI; 2:Subcontractor
			
			$msj = "You have add a new vehicle!!";
			$flag = true;
			$pass = '';
			if ($idVehicle != '') {
				$msj = "You have update a vehicle!!";
				$flag = false;
			}else{
				//para vehiculo nuevo se genera clave para generar el codigo QR
				$pass = $this->generaPass();
			}

			if ($idVehicle = $this->settings_model->saveVehicle($pass)) 
			{
				if($flag)
				{	
					//si es un registro nuevo genero el codigo QR y subo la imagen
					//INICIO - genero imagen con la libreria y la subo 
					$this->load->library('ciqrcode');

					$valorQRcode = base_url("login/index/" . $idVehicle . $pass);
					$rutaImagen = "images/vehicle/" . $idVehicle . "_qr_code.png";
					
					$params['data'] = $valorQRcode;
					$params['level'] = 'H';
					$params['size'] = 10;
					$params['savename'] = FCPATH.$rutaImagen;
									
					$this->ciqrcode->generate($params);
					//FIN - genero imagen con la libreria y la subo
				}
				
				$data["result"] = true;		
				$this->session->set_flashdata('retornoExito', $msj);
			} else {
				$data["result"] = "error";
				$this->session->set_flashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
			}
			echo json_encode($data);
    }
	
	public function generaPass()
	{
			//Se define una cadena de caractares. Te recomiendo que uses esta.
			$cadena = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
			//Obtenemos la longitud de la cadena de caracteres
			$longitudCadena=strlen($cadena);
			 
			//Se define la variable que va a contener la contraseña
			$pass = "";
			//Se define la longitud de la contraseña, en mi caso 10, pero puedes poner la longitud que quieras
			$longitudPass=50;
			 
			//Creamos la contraseña
			for($i=1 ; $i<=$longitudPass ; $i++){
				//Definimos numero aleatorio entre 0 y la longitud de la cadena de caracteres-1
				$pos=rand(0,$longitudCadena-1);
			 
				//Vamos formando la contraseña en cada iteraccion del bucle, añadiendo a la cadena $pass la letra correspondiente a la posicion $pos en la cadena de caracteres definida.
				$pass .= substr($cadena,$pos,1);
			}
			return $pass;
	}
	
	/**
	 * photo
	 */
	public function photo($idVehicle, $error = '')
	{
			if (empty($idVehicle)) {
				show_error('ERROR!!! - You are in the wrong place.');
			}
			
			//busco datos del vehiculo
			$arrParam = array(
				"idVehicle" => $idVehicle
			);
			$data['vehicleInfo'] = $this->settings_model->get_vehicle_info_by($arrParam);
			
			$data['error'] = $error; //se usa para mostrar los errores al cargar la imagen 
			$data['idVehicle'] = $idVehicle; 
			$data["view"] = 'vehicle_photo';
			$this->load->view("layout", $data);
	}	

	/**
	 * FUNCIÓN PARA SUBIR LA IMAGEN 
	 * @param int vistaRegreso -> para saber si es de VCI o RENTADA
	 */
    function do_upload($type, $vistaRegreso) 
	{
        $config['upload_path'] = './images/vehicle/';
        $config['overwrite'] = true;
        $config['allowed_types'] = 'gif|jpg|png|jpeg';
        $config['max_size'] = '3000';
        $config['max_width'] = '2024';
        $config['max_height'] = '2008';
        $idVehicle = $this->input->post("hddId");
        $config['file_name'] = $idVehicle . "_" . $type;

        $this->load->library('upload', $config);
        //SI LA IMAGEN FALLA AL SUBIR MOSTRAMOS EL ERROR EN LA VISTA 
        if (!$this->upload->do_upload()) {
            $error = $this->upload->display_errors();
            $this->$type($idVehicle,$error);
        } else {
            $file_info = $this->upload->data();//subimos la imagen
			
            //USAMOS LA FUNCIÓN create_thumbnail Y LE PASAMOS EL NOMBRE DE LA IMAGEN,
            //ASÍ YA TENEMOS LA IMAGEN REDIMENSIONADA
			if($type=="photo"){
				$this->_create_thumbnail($file_info['file_name']);
				$data = array('upload_data' => $this->upload->data());
				$imagen = $file_info['file_name'];
				$path = "images/vehicle/thumbs/" . $imagen;
			}
			
			//actualizamos el campo photo
			$arrParam = array(
				"table" => "param_vehicle",
				"primaryKey" => "id_vehicle",
				"id" => $idVehicle,
				"column" => $type,
				"value" => $path
			);

			$data['linkBack'] = "settings/vehicle/" . $vistaRegreso;
			$data['titulo'] = "<i class='fa fa-automobile'></i>VEHICLE";
			
			if($this->general_model->updateRecord($arrParam))
			{
				$data['clase'] = "alert-success";
				$data['msj'] = "Good job, you have upload the photo.";			
			}else{
				$data['clase'] = "alert-danger";
				$data['msj'] = "Ask for help.";
			}
						
			$data["view"] = 'template/answer';
			$this->load->view("layout", $data);
			//redirect('employee/photo');
        }
    }
	
    //FUNCIÓN PARA CREAR LA MINIATURA A LA MEDIDA QUE LE DIGAMOS
    function _create_thumbnail($filename) 
	{
        $config['image_library'] = 'gd2';
        //CARPETA EN LA QUE ESTÁ LA IMAGEN A REDIMENSIONAR
        $config['source_image'] = 'images/vehicle/' . $filename;
        $config['create_thumb'] = TRUE;
        $config['maintain_ratio'] = TRUE;
        //CARPETA EN LA QUE GUARDAMOS LA MINIATURA
        $config['new_image'] = 'images/vehicle/thumbs/';
        $config['width'] = 150;
        $config['height'] = 150;
        $this->load->library('image_lib', $config);
        $this->image_lib->resize();
    }
	
	/**
	 * qr_code
	 */
	public function qr_code($idVehicle)
	{
			if (empty($idVehicle)) {
				show_error('ERROR!!! - You are in the wrong place.');
			}
			
			//busco datos del vehiculo
			$arrParam = array(
				"idVehicle" => $idVehicle
			);
			$data['vehicleInfo'] = $this->settings_model->get_vehicle_info_by($arrParam);
			
			$data['idVehicle'] = $idVehicle;
			$data["view"] = 'vehicle_qr_code';
			$this->load->view("layout", $data);
	}
	
	/**
	 * List of inspections
	 * @param int $idVehicle
	 * @since 17/1/2017
	 */
	public function inspections($idVehicle)
	{
			if (empty($idVehicle)) {
				show_error('ERROR!!! - You are in the wrong place.');
			}
						
			//busco datos del vehiculo
			$arrParam['idVehicle'] = $idVehicle;
			$data['vehicleInfo'] = $this->general_model->get_vehicle_by($arrParam);
			
			$data['info'] = $this->settings_model->get_resumen_inspections($data['vehicleInfo']);//vehicle oil change history
			
			$data["view"] = 'vehicle_inspections';
			$this->load->view("layout", $data);
	}
	

	
}