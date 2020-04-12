<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Maintenance extends CI_Controller {
	
    public function __construct() {
        parent::__construct();
        $this->load->model("maintenance_model");
		$this->load->helper('form');
    }
	
	/**
	 * Entrance
     * @since 11/2/2020
     * @author BMOTTAG
	 */
	public function entrance($idVehicle)
	{
			if (empty($idVehicle)) {
				show_error('ERROR!!! - You are in the wrong place.');
			}
			
			//busco datos del vehiculo
			$arrParam['idVehicle'] = $idVehicle;
			$data['vehicleInfo'] = $this->general_model->get_vehicle_by($arrParam);
									
			//listado de registros para ese vehiculo
			$arrParam = array("idVehicle" => $idVehicle);				
			$data['infoRecords'] = $this->maintenance_model->get_maintenance($arrParam);
			
			$data["view"] = 'maintenance_list';
			$this->load->view("layout", $data);
	}
	
	/**
	 * Maintenance form
     * @since 22/3/2020
     * @author BMOTTAG
	 */
	public function maintenance_form($idVehicle, $idMaintenance = 'x')
	{
			if (empty($idVehicle)) {
				show_error('ERROR!!! - You are in the wrong place.');
			}
			
			$data['information'] = FALSE;

			//workers list
			$arrParam = array("state" => 1);
			$data['workersList'] = $this->general_model->get_user($arrParam);//workers list
						
			//busco datos del vehiculo
			$arrParam['idVehicle'] = $idVehicle;
			$data['vehicleInfo'] = $this->general_model->get_vehicle_by($arrParam);
			
			//busco tipos de mantenimiento
			$arrParam = array(
				"table" => "maintenance_type",
				"order" => "maintenance_type",
				"id" => "x"
			);
			$data['infoTypeMaintenance'] = $this->general_model->get_basic_search($arrParam);
			
			//si envio el id, entonces busco la informacion 
			if ($idMaintenance != 'x') 
			{
				$arrParam = array("idMaintenance" => $idMaintenance);				
				$data['information'] = $this->maintenance_model->get_maintenance($arrParam);
			}			

			$data["view"] = 'form_maintenance';
			$this->load->view("layout", $data);
	}
	
	/**
	 * save maintenance
     * @since 11/2/2020
     * @author BMOTTAG
	 */
	public function save_maintenance()
	{			
			header('Content-Type: application/json');
			$data = array();
			
			$data["idRecord"] = $this->input->post('hddIdVehicle');
			$hddIdMaintenance = $this->input->post('hddIdMaintenance');
			$idStock = $this->input->post('id_stock');
			$OldIdStock = $this->input->post('hddOldIdStock');
			$OldMaintenanceQuantity = $this->input->post('hddOldMaintenanceQuantity');
			
			//para el mismo tipo de mantenimiento
			//actualizo los estados a 2 (inactivo) si es un mantenimiento nuevo
			$bandera = false;//se utiliza cuando se cambia de un stock a otro
			if($hddIdMaintenance == '')
			{
				$this->maintenance_model->update_maintenance_state();				
			}

			if ($idMaintenance = $this->maintenance_model->add_maintenance()) 
			{				
				$data["result"] = true;
				$data["mensaje"] = "You have save the Maintenance.";
				$this->session->set_flashdata('retornoExito', 'You have save the Maintenance!!');
			} else {
				$data["result"] = "error";
				$data["mensaje"] = "Error!!! Ask for help.";
				$this->session->set_flashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
			}

			echo json_encode($data);
    }
	
	/**
	 * Maintenance Check
	 */
	public function maintenance_check()
	{				
		$fecha = date('Y-m-d');
		$filtroFecha = strtotime ( '+7 day' , strtotime ( $fecha ) ) ;

		//listado de registros mantenimientos activos
		$arrParam = array("maintenanceState" => 1);
		$infoMaintenance = $this->maintenance_model->get_maintenance($arrParam);	

		$this->maintenance_model->delete_maintenance_check();//elimino los registros de maintenance_check
		//revisar cuales estan proximo a vencerse por kilometros o fechas
		foreach ($infoMaintenance as $lista):
			$diferencia = $lista["next_hours_maintenance"] - $lista["hours"];
			
			if($lista["fk_id_maintenance_type"] == 8 || $lista["fk_id_maintenance_type"] == 9){
				$diferencia = $lista["next_hours_maintenance"] - $lista["hours_2"];
			}elseif($lista["fk_id_maintenance_type"] == 10){
				$diferencia = $lista["next_hours_maintenance"] - $lista["hours_3"];
			}
				
			$nextDateMaintenance = strtotime($lista["next_date_maintenance"]);
			
			if(($lista["next_hours_maintenance"] != 0 && $diferencia <= 100) || ($lista["next_date_maintenance"] != "" && $nextDateMaintenance <= $filtroFecha)){
				$this->maintenance_model->add_maintenance_check($lista["id_maintenance"]);
			}
		endforeach;
		
		redirect("/dashboard/maintenance","location",301);
	}
	
	/**
	 * Stock qutitity
     * @since 25/2/2020
     * @author BMOTTAG
	 */
    public function quantityInfo() {
        header("Content-Type: text/plain; charset=utf-8"); //Para evitar problemas de acentos
        $idStock = $this->input->post('idStock');
				
		//busco info tabla de stock
		
		$arrParam = array("idStock" => $idStock);
		$infoStock = $this->general_model->get_stock($arrParam);

        echo "<option value=''>Select...</option>";
        if ($infoStock) {
			$quantity = $infoStock[0]['quantity'];
			for ($i = 1; $i <= $quantity; $i++) {
				echo "<option value='" . $i . "' >" . $i . "</option>";
			}
        }
    }
	
	
	
}