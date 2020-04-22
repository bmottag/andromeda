<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cron extends CI_Controller {
	
    public function __construct() {
        parent::__construct();
        $this->load->model("cron_model");
    }

	/**
	 * Limpiar base de datos de la version de ejemplo
	 * limpiar maintenance_check
	 * limpiar maintenance
	 * eliminar nuevos datos param_company
	 * eliminar nuevos datos param_vehicle
	 * eliminar nuevos datos user
	 */
	public function cleanNewData()
	{
		$user = $this->cron_model->deleteData();
	}
	
	
}