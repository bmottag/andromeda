<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report extends CI_Controller {
	
    public function __construct() {
        parent::__construct();
		$this->load->model("report_model");
    }
	
	/**
	 * Search by daterange safety reports
     * @since 6/01/2017
     * @author BMOTTAG
	 */
    public function searchByDateRange($modulo) 
	{
			if (empty($modulo) ) {
				show_error('ERROR!!! - You are in the wrong place.');
			}
			$this->load->model("general_model");
			
			$data['companyList'] = FALSE;//lista para filtrar en el hauling report
			$data['vehicleList'] = FALSE;//lista para filtrar en inspection report
			$data['trailerList'] = FALSE;//lista para filtrar en inspection report
			
			//workers list
			$arrParam = array("state" => 1);
			$data['workersList'] = $this->general_model->get_user($arrParam);//workers list

			switch ($modulo) {
				case 'dailyInspection':										
					$arrParam["tipo"] = "daily";
					$data['vehicleList'] = $this->report_model->get_vehicle_by_type($arrParam);//vehicle's list
					$arrParam["tipo"] = "trailer";
					$data['trailerList'] = $this->report_model->get_vehicle_by_type($arrParam);//vehicle's list
					
					$data["estilos"] = "danger";
					$data["titulo"] = "<i class='fa fa-search fa-fw'></i> PICKUPS & TRUCKS INSPECTION REPORT";
					break;
				case 'heavyInspection':
					$arrParam["tipo"] = "heavy";
					$data['vehicleList'] = $this->report_model->get_vehicle_by_type($arrParam);//vehicle's list
					$data["estilos"] = "success";
					$data["titulo"] = "<i class='fa fa-search fa-fw'></i> CONTRUCTION EQUIPMENT INSPECTION REPORT";
					break;
				case 'specialInspection':
					$arrParam["tipo"] = "special";
					$data['vehicleList'] = $this->report_model->get_vehicle_by_type($arrParam);//vehicle's list
					$data["estilos"] = "purpura";
					$data["titulo"] = "<i class='fa fa-search fa-fw'></i> SPECIAL EQUIPMENT INSPECTION REPORT";
					break;
			}
			
			$data["view"] = "form_search";
			
			//Si envian los datos del filtro entonces lo direcciono a la lista respectiva con los datos de la consulta
			if($this->input->post('from'))
			{
				$from =  $this->input->post('from');
				$to =  $this->input->post('to');
				$data['employee'] =  $this->input->post('employee');
				$data['employee'] = $data['employee']==''?'x':$data['employee'];
				$data['from'] = formatear_fecha($from);
				$data['to'] = formatear_fecha($to);
				
				//le sumo un dia al dia final para que ingrese ese dia en la consulta
				$data['to'] = date('Y-m-d',strtotime ( '+1 day ' , strtotime ( $data['to'] ) ) );

				$arrParam = array(
					"from" => $data['from'],
					"to" => $data['to'],
					"employee" => $data['employee']
				);

				$arrParam['vehicleId'] =  $this->input->post('vehicleId');
				$arrParam['vehicleId'] = $arrParam['vehicleId']==''?'x': $arrParam['vehicleId'];
				$data['vehicleId'] = $arrParam['vehicleId'];
				switch ($modulo) 
				{					
					case 'dailyInspection':						
						$arrParam['trailerId'] =  $this->input->post('trailerId');
						$arrParam['trailerId'] = $arrParam['trailerId']==''?'x': $arrParam['trailerId'];
						$data['trailerId'] = $arrParam['trailerId'];
					
						$data['info'] = $this->report_model->get_daily_inspection($arrParam);
											
						$data["view"] = "list_daily_inspection";
						break;
					case 'heavyInspection':						
						$data['info'] = $this->report_model->get_heavy_inspection($arrParam);
						$data["view"] = "list_heavy_inspection";
						break;
					case 'specialInspection':						
						$data['infoWaterTruck'] = $this->report_model->get_water_truck_inspection($arrParam);//info de water truck
						$data['infoHydrovac'] = $this->report_model->get_hydrovac_inspection($arrParam);//info de hydrovac
						$data['infoSweeper'] = $this->report_model->get_sweeper_inspection($arrParam);//info de sweeper
						$data['infoGenerator'] = $this->report_model->get_generator_inspection($arrParam);//info de generador
						
						$data["view"] = "list_special_inspection";
						break;
				}
				
			}
			
			$this->load->view("layout", $data);
    }
	
	
	
	
	
	

	
}