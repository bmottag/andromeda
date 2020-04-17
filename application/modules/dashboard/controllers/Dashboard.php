<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {
	
    public function __construct() {
        parent::__construct();
		$this->load->model("dashboard_model");
    }

	/**
	 * Index Page for this controller.
	 * Basic dashboard
	 */
	public function index()
	{	

			$data["view"] = "dashboard";
			$this->load->view("layout", $data);
	}
		
	/**
	 * SUPER ADMIN DASHBOARD
	 */
	public function admin()
	{				
			$data['noDailyInspection'] = $this->dashboard_model->countDailyInspection();//cuenta registros de DailyInspection
			$data['noHeavyInspection'] = $this->dashboard_model->countHeavyInspection();//cuenta registros de HeavyInspection
				
			$data['noSpecialInspection'] = $this->dashboard_model->countSpecialInspection();//cuenta registros de SpecialInspection
			
			$arrParam["limit"] = 6;//Limite de registros para la consulta
			$data['infoWaterTruck'] = $this->general_model->get_special_inspection_water_truck($arrParam);//info de water truck
			$data['infoHydrovac'] = $this->general_model->get_special_inspection_hydrovac($arrParam);//info de hydrovac
			$data['infoSweeper'] = $this->general_model->get_special_inspection_sweeper($arrParam);//info de sweeper
			$data['infoGenerator'] = $this->general_model->get_special_inspection_generator($arrParam);//info de generador
		
			$data["view"] = "dashboard";
			$this->load->view("layout", $data);
	}
	
	/**
	 * pickup inpection list
     * @since 31/1/2018
     * @author BMOTTAG
	 */
	public function pickups_inspection()
	{		
			$this->load->model("general_model");
			$userRol = $this->session->userdata("rol");
			$data['dashboardURL'] = $this->session->userdata("dashboardURL");

			if($userRol == 7){ //If it is a BASIC USER, just show the records of the user session
				$arrParam["idEmployee"] = $this->session->userdata("id");
			}
			$arrParam["limit"] = 30;//Limite de registros para la consulta

			$data['infoDaily'] = $this->general_model->get_daily_inspection($arrParam);//info pickups inspection

			$data["view"] ='pickups_inspection_list';
			$this->load->view("layout", $data);
	}
	
	/**
	 * construction equipment inpection list
     * @since 31/1/2018
     * @author BMOTTAG
	 */
	public function construction_equipment_inspection()
	{		
			$this->load->model("general_model");
			$userRol = $this->session->userdata("rol");
			$data['dashboardURL'] = $this->session->userdata("dashboardURL");

			if($userRol == 7){ //If it is a BASIC USER, just show the records of the user session
				$arrParam["idEmployee"] = $this->session->userdata("id");
			}
			$arrParam["limit"] = 30;//Limite de registros para la consulta

			$data['infoHeavy'] = $this->general_model->get_heavy_inspection($arrParam);//info de contruction

			$data["view"] ='construction_equipment_inspection_list';
			$this->load->view("layout", $data);
	}
	
	/**
	 * Maintenance list
     * @since 14/3/2020
     * @author BMOTTAG
	 */
	public function maintenance()
	{		
			$this->load->model("general_model");
			$data['dashboardURL'] = $this->session->userdata("dashboardURL");

			$data['infoMaintenance'] = $this->general_model->get_maintenance_check();

			$data["view"] ='maintenance_list';
			$this->load->view("layout", $data);
	}
	
	/**
	 * System general info
     * @since 28/3/2020
     * @author BMOTTAG
	 */
	public function info()
	{		
			$data["view"] ='general_info';
			$this->load->view("layout", $data);
	}

	
	
}