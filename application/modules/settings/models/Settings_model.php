<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class Settings_model extends CI_Model {

	    
		/**
		 * Verify if the user already exist by the social insurance number
		 * @author BMOTTAG
		 * @since  8/11/2016
		 * @review 27/11/2016
		 */
		public function verifyUser($arrData) 
		{
				$this->db->where($arrData["column"], $arrData["value"]);
				$query = $this->db->get("user");

				if ($query->num_rows() >= 1) {
					return true;
				} else{ return false; }
		}
		
		/**
		 * Add/Edit USER
		 * @since 8/11/2016
		 */
		public function saveEmployee() 
		{
				$idUser = $this->input->post('hddId');
				
				$data = array(
					'first_name' => $this->input->post('firstName'),
					'last_name' => $this->input->post('lastName'),
					'log_user' => $this->input->post('user'),
					'movil' => $this->input->post('movilNumber'),
					'email' => $this->input->post('email'),
					'fk_id_user_role' => $this->input->post('id_role')
				);	

				//revisar si es para adicionar o editar
				if ($idUser == '') {
					$data['state'] = 0;//si es para adicionar se coloca estado inicial como usuario nuevo
					$data['password'] = 'e10adc3949ba59abbe56e057f20f883e';//123456
					$query = $this->db->insert('user', $data);
				} else {
					$data['state'] = $this->input->post('state');
					$this->db->where('id_user', $idUser);
					$query = $this->db->update('user', $data);
				}
				if ($query) {
					return true;
				} else {
					return false;
				}
		}
		
	    /**
	     * Reset user´s password
	     * @author BMOTTAG
	     * @since  11/1/2017
	     */
	    public function resetEmployeePassword($idUser)
		{
				$passwd = '123456';
				$passwd = md5($passwd);
				
				$data = array(
					'password' => $passwd,
					'state' => 0
				);

				$this->db->where('id_user', $idUser);
				$query = $this->db->update('user', $data);

				if ($query) {
					return true;
				} else {
					return false;
				}
	    }

	    /**
	     * Update user´s password
	     * @author BMOTTAG
	     * @since  8/11/2016
	     */
	    public function updatePassword()
		{
				$idUser = $this->input->post("hddId");
				$newPassword = $this->input->post("inputPassword");
				$passwd = str_replace(array("<",">","[","]","*","^","-","'","="),"",$newPassword); 
				$passwd = md5($passwd);
				
				$data = array(
					'password' => $passwd
				);

				$this->db->where('id_user', $idUser);
				$query = $this->db->update('user', $data);

				if ($query) {
					return true;
				} else {
					return false;
				}
	    }
		
		/**
		 * Add/Edit COMPANY
		 * @since 13/12/2016
		 */
		public function saveCompany() 
		{
				$idCompany = $this->input->post('hddId');
				
				$data = array(
					'company_name' => $this->input->post('company'),
					'contact' => $this->input->post('contact'),
					'movil_number' => $this->input->post('movilNumber'),
					'email' => $this->input->post('email')
				);
				
				//revisar si es para adicionar o editar
				if ($idCompany == '') {
					$query = $this->db->insert('param_company', $data);
					$idCompany = $this->db->insert_id();				
				} else {
					$this->db->where('id_company', $idCompany);
					$query = $this->db->update('param_company', $data);
				}
				if ($query) {
					return $idCompany;
				} else {
					return false;
				}
		}
		
		/**
		 * Add/Edit JOB
		 * @since 10/11/2016
		 */
		public function saveJob() 
		{				
				$idJob = $this->input->post('hddId');
				
				$data = array(
					'job_description' => $this->input->post('jobName'),
					'state' => $this->input->post('stateJob')
				);			

				//revisar si es para adicionar o editar
				if ($idJob == '') {
					$query = $this->db->insert('param_jobs', $data);
				} else {
					$this->db->where('id_job', $idJob);
					$query = $this->db->update('param_jobs', $data);
				}
				if ($query) {
					return true;
				} else {
					return false;
				}
		}
		
		/**
		 * Add/Edit HAZARD ACTIVITY
		 * @since 5/2/2017
		 */
		public function saveHazardActivity() 
		{
				$idHazardActivity = $this->input->post('hddId');
				
				$data = array(
					'hazard_activity' => $this->input->post('hazardActivity')
				);
				
				//revisar si es para adicionar o editar
				if ($idHazardActivity == '') {
					$query = $this->db->insert('param_hazard_activity', $data);
					$idHazardActivity = $this->db->insert_id();				
				} else {
					$this->db->where('id_hazard_activity', $idHazardActivity);
					$query = $this->db->update('param_hazard_activity', $data);
				}
				if ($query) {
					return $idHazardActivity;
				} else {
					return false;
				}
		}

		/**
		 * Get hazard list
		 * @since 5/2/2017
		 */
		public function get_hazard_list() 
		{		
				$this->db->select();
				$this->db->join('param_hazard_activity A', 'A.id_hazard_activity = H.fk_id_hazard_activity', 'INNER');
				$this->db->join('param_hazard_priority P', 'P.id_priority = H.fk_id_priority', 'INNER');
				$this->db->order_by('A.hazard_activity, H.hazard_description', 'asc');
				$query = $this->db->get('param_hazard H');

				if ($query->num_rows() > 0) {
					return $query->result_array();
				} else {
					return false;
				}
		}

		/**
		 * Add/Edit HAZARD
		 * @since 11/12/2016
		 */
		public function saveHazard() 
		{
				$idHazard = $this->input->post('hddId');
				
				$data = array(
					'fk_id_hazard_activity' => $this->input->post('activity'),
					'hazard_description' => $this->input->post('hazardName'),
					'solution' => $this->input->post('solution'),
					'fk_id_priority' => $this->input->post('priority')
				);
				
				//revisar si es para adicionar o editar
				if ($idHazard == '') {
					$query = $this->db->insert('param_hazard', $data);
					$idHazard = $this->db->insert_id();				
				} else {
					$this->db->where('id_hazard', $idHazard);
					$query = $this->db->update('param_hazard', $data);
				}
				if ($query) {
					return $idHazard;
				} else {
					return false;
				}
		}
		
		/**
		 * Add/Edit MATERIAL
		 * @since 13/12/2016
		 */
		public function saveMaterial() 
		{
				$idMaterial = $this->input->post('hddId');
				
				$data = array(
					'material' => $this->input->post('material')
				);
				
				//revisar si es para adicionar o editar
				if ($idMaterial == '') {
					$query = $this->db->insert('param_material_type', $data);
					$idMaterial = $this->db->insert_id();				
				} else {
					$this->db->where('id_material', $idMaterial);
					$query = $this->db->update('param_material_type', $data);
				}
				if ($query) {
					return $idMaterial;
				} else {
					return false;
				}
		}
		
		/**
		 * Get vehicle list
		 * Param int $companyType -> 1: VCI; 2: Subcontractor
		 * Param int $vehicleType -> 1: Pickup; 2: Construction Equipment; 3: Trucks; 4: Special Equipment; 99: Otros
		 * @since 5/5/2017
		 */
		public function get_vehicle_info_by($arrData) 
		{		
				$this->db->select();
				$this->db->join('param_company C', 'C.id_company = A.fk_id_company', 'INNER');
				$this->db->join('param_vehicle_type_2 T', 'T.id_type_2 = A.type_level_2', 'INNER');
				$this->db->where('A.state', 1);//solo mostrar vehiclos activos

				if (array_key_exists("companyType", $arrData)) {
					$this->db->where('C.company_type', $arrData["companyType"]);

					//si es de VCI entonces filtrar por tipo de inspeccion de lo contrario no se hace el filtro
					if($arrData["companyType"]==1){
						if (array_key_exists("vehicleType", $arrData)) {
							$this->db->where('T.inspection_type', $arrData["vehicleType"]);
						}
					}
				}
				
				if (array_key_exists("idVehicle", $arrData)) {
					$this->db->where('A.id_vehicle', $arrData["idVehicle"]);
				}
				
				$this->db->order_by('T.inspection_type, C.id_company, A.unit_number', 'asc');
				$query = $this->db->get('param_vehicle A');

				if ($query->num_rows() > 0) {
					return $query->result_array();
				} else {
					return false;
				}
		}
		
		/**
		 * Add/Edit vehicle
		 * @since 15/12/2016
		 * @review 27/12/2016
		 */
		public function saveVehicle($pass) 
		{
				$idVehicle = $this->input->post('hddId');
				
				$data = array(
					'fk_id_company' => $this->input->post('company'),
					'type_level_1' => $this->input->post('type1'),
					'type_level_2' => $this->input->post('type2'),
					'make' => $this->input->post('make'),
					'model' => $this->input->post('model'),
					'manufacturer_date' => $this->input->post('manufacturer'),
					'description' => $this->input->post('description'),
					'unit_number' => $this->input->post('unitNumber'),
					'state' => $this->input->post('state'),
					'hours' => $this->input->post('hours')
				);	

				//revisar si es para adicionar o editar
				if ($idVehicle == '') {							
					$query = $this->db->insert('param_vehicle', $data);
					$idVehicle = $this->db->insert_id();
					
					//actualizo la url del codigo QR
					$path = $idVehicle . $pass;
					$rutaQRcode = "images/vehicle/" . $idVehicle . "_qr_code.png";
			
					//actualizo campo con el path encriptado
					$sql = "UPDATE param_vehicle SET encryption = '$path',qr_code = '$rutaQRcode'  WHERE id_vehicle = $idVehicle";
					$query = $this->db->query($sql);
				} else {
					$this->db->where('id_vehicle', $idVehicle);
					$query = $this->db->update('param_vehicle', $data);
				}
				if ($query) {
					return $idVehicle;
				} else {
					return false;
				}
		}
		
		/**
		 * Get vehicle inspections history
		 * @since 13/4/2020
		 */
		public function get_resumen_inspections($infoVehicle) 
		{		
				$table = $infoVehicle[0]['table_inspection'] . ' T';
				$idTable = 'T.' . $infoVehicle[0]['id_table_inspection'];
		
				$this->db->select("A.*, T.comments, CONCAT(first_name, ' ', last_name) name");
				$this->db->join('user U', 'U.id_user = A.fk_id_user', 'INNER');
				$this->db->join("$table", "$idTable = A.fk_id_inspection", "LEFT");
				$this->db->where('A.fk_id_vehicle', $infoVehicle[0]['id_vehicle']);
				$this->db->order_by('id_inspection_total', 'desc');
				$query = $this->db->get('inspection_total A');

				if ($query->num_rows() > 0) {
					return $query->result_array();
				} else {
					return false;
				}
		}
		
		
	    
	}