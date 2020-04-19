<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Clase para consultas generales a una tabla
 */
class General_model extends CI_Model {

    /**
     * Consulta BASICA A UNA TABLA
     * @param $TABLA: nombre de la tabla
     * @param $ORDEN: orden por el que se quiere organizar los datos
     * @param $COLUMNA: nombre de la columna en la tabla para realizar un filtro (NO ES OBLIGATORIO)
     * @param $VALOR: valor de la columna para realizar un filtro (NO ES OBLIGATORIO)
     * @since 8/11/2016
     */
    public function get_basic_search($arrData) {
        if ($arrData["id"] != 'x')
            $this->db->where($arrData["column"], $arrData["id"]);
        $this->db->order_by($arrData["order"], "ASC");
        $query = $this->db->get($arrData["table"]);

        if ($query->num_rows() >= 1) {
            return $query->result_array();
        } else
            return false;
    }
	
	/**
	 * Update field in a table
	 * @since 11/12/2016
	 */
	public function updateRecord($arrDatos) {
		$data = array(
			$arrDatos ["column"] => $arrDatos ["value"]
		);
		$this->db->where($arrDatos ["primaryKey"], $arrDatos ["id"]);
		$query = $this->db->update($arrDatos ["table"], $data);
		if ($query) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * Delete Record
	 * @since 5/12/2016
	 */
	public function deleteRecord($arrDatos) 
	{
		$query = $this->db->delete($arrDatos ["table"], array($arrDatos ["primaryKey"] => $arrDatos ["id"]));
		if ($query) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Lista de menu
	 * Modules: MENU
	 * @since 30/3/2020
	 */
	public function get_menu($arrData) 
	{		
		if (array_key_exists("idMenu", $arrData)) {
			$this->db->where('id_menu', $arrData["idMenu"]);
		}
		if (array_key_exists("menuType", $arrData)) {
			$this->db->where('menu_type', $arrData["menuType"]);
		}
		if (array_key_exists("menuState", $arrData)) {
			$this->db->where('menu_state', $arrData["menuState"]);
		}
		if (array_key_exists("columnOrder", $arrData)) {
			$this->db->order_by($arrData["columnOrder"], 'asc');
		}else{
			$this->db->order_by('menu_order', 'asc');
		}
		
		$query = $this->db->get('param_menu');

		if ($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return false;
		}
	}	

	/**
	 * Lista de roles
	 * Modules: ROL
	 * @since 30/3/2020
	 */
	public function get_roles($arrData) 
	{		
		if (array_key_exists("filtro", $arrData)) {
			$this->db->where('id_role !=', 99);
		}
		if (array_key_exists("idRole", $arrData)) {
			$this->db->where('id_role', $arrData["idRole"]);
		}
		
		$this->db->order_by('role_name', 'asc');
		$query = $this->db->get('param_role');

		if ($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return false;
		}
	}
	
	/**
	 * User list
	 * @since 30/3/2020
	 */
	public function get_user($arrData) 
	{			
		$this->db->select();
		$this->db->join('param_role R', 'R.id_role = U.fk_id_user_role', 'INNER');
		if (array_key_exists("state", $arrData)) {
			$this->db->where('U.state', $arrData["state"]);
		}
		
		//list without inactive users
		if (array_key_exists("filtroState", $arrData)) {
			$this->db->where('U.state !=', 2);
		}

		$this->db->order_by("first_name, last_name", "ASC");
		$query = $this->db->get("user U");

		if ($query->num_rows() >= 1) {
			return $query->result_array();
		} else
			return false;
	}
	
	/**
	 * Lista de enlaces
	 * Modules: MENU
	 * @since 31/3/2020
	 */
	public function get_links($arrData) 
	{		
		$this->db->select();
		$this->db->join('param_menu M', 'M.id_menu = L.fk_id_menu', 'INNER');
		
		if (array_key_exists("idMenu", $arrData)) {
			$this->db->where('fk_id_menu', $arrData["idMenu"]);
		}
		if (array_key_exists("idLink", $arrData)) {
			$this->db->where('id_link', $arrData["idLink"]);
		}
		if (array_key_exists("linkType", $arrData)) {
			$this->db->where('link_type', $arrData["linkType"]);
		}
		if (array_key_exists("linkState", $arrData)) {
			$this->db->where('link_state', $arrData["linkState"]);
		}
		
		$this->db->order_by('M.menu_order, L.order', 'asc');
		$query = $this->db->get('param_menu_links L');

		if ($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return false;
		}
	}
	
	/**
	 * Lista de permisos
	 * Modules: MENU
	 * @since 31/3/2020
	 */
	public function get_role_access($arrData) 
	{		
		$this->db->select('P.id_access, P.fk_id_menu, P.fk_id_link, P.fk_id_role, M.menu_name, M.menu_order, M.menu_type, L.link_name, L.link_url, L.order, L.link_icon, L.link_type, R.role_name, R.style');
		$this->db->join('param_menu M', 'M.id_menu = P.fk_id_menu', 'INNER');
		$this->db->join('param_menu_links L', 'L.id_link = P.fk_id_link', 'LEFT');
		$this->db->join('param_role R', 'R.id_role = P.fk_id_role', 'INNER');
		
		if (array_key_exists("idPermiso", $arrData)) {
			$this->db->where('id_access', $arrData["idPermiso"]);
		}
		if (array_key_exists("idMenu", $arrData)) {
			$this->db->where('P.fk_id_menu', $arrData["idMenu"]);
		}
		if (array_key_exists("idLink", $arrData)) {
			$this->db->where('P.fk_id_link', $arrData["idLink"]);
		}
		if (array_key_exists("idRole", $arrData)) {
			$this->db->where('P.fk_id_role', $arrData["idRole"]);
		}
		if (array_key_exists("menuType", $arrData)) {
			$this->db->where('M.menu_type', $arrData["menuType"]);
		}
		if (array_key_exists("linkState", $arrData)) {
			$this->db->where('L.link_state', $arrData["linkState"]);
		}
		if (array_key_exists("menuURL", $arrData)) {
			$this->db->where('M.menu_url', $arrData["menuURL"]);
		}
		if (array_key_exists("linkURL", $arrData)) {
			$this->db->where('L.link_url', $arrData["linkURL"]);
		}		
		
		$this->db->order_by('M.menu_order, L.order', 'asc');
		$query = $this->db->get('param_menu_access P');

		if ($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return false;
		}
	}
	
	/**
	 * menu list for a role
	 * Modules: MENU
	 * @since 2/4/2020
	 */
	public function get_role_menu($arrData) 
	{		
		$this->db->select();
		$this->db->join('param_menu M', 'M.id_menu = P.fk_id_menu', 'INNER');

		if (array_key_exists("idRole", $arrData)) {
			$this->db->where('P.fk_id_role', $arrData["idRole"]);
		}
		if (array_key_exists("menuType", $arrData)) {
			$this->db->where('M.menu_type', $arrData["menuType"]);
		}
		if (array_key_exists("menuState", $arrData)) {
			$this->db->where('M.menu_state', $arrData["menuState"]);
		}
					
		$this->db->group_by("P.fk_id_menu"); 
		$this->db->order_by('M.menu_order', 'asc');
		$query = $this->db->get('param_menu_access P');

		if ($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return false;
		}
	}
	
	/**
	 * Get vehicle list -> Se usa en el Login, en Inspection, en Manintenance
	 * Param varchar $encryption -> dato que viene del QR code
	 * Param varchar $idVehicle -> identificador del vehiculo
	 * @since 3/3/2016
	 */
	public function get_vehicle_by($arrData) 
	{		
			$this->db->select();
			$this->db->join('param_vehicle_type_2 T', 'T.id_type_2 = V.type_level_2', 'INNER');
			if (array_key_exists("encryption", $arrData)) {
				$this->db->where('V.encryption', $arrData["encryption"]);
			}
			if (array_key_exists("idVehicle", $arrData)) {
				$this->db->where('V.id_vehicle', $arrData["idVehicle"]);
			}
			if (array_key_exists("vehicleState", $arrData)) {
				$this->db->where('V.state', $arrData["vehicleState"]);
			}
			if (array_key_exists("vinNumber", $arrData)) {
				$this->db->like('V.unit_number', $arrData["vinNumber"]); 
			}
			
			$query = $this->db->get('param_vehicle V');

			if ($query->num_rows() > 0) {
				return $query->result_array();
			} else {
				return false;
			}
	}
	
    /**
     * Daily inspection list
     * Modules: Dashboard 
     * @since 14/1/2017
     */
    public function get_daily_inspection($arrData) 
	{
		$this->db->select("I.*, CONCAT(first_name, ' ', last_name) name, V.*");
		$this->db->join('user U', 'U.id_user = I.fk_id_user', 'INNER');
		$this->db->join('param_vehicle V', 'V.id_vehicle = I.fk_id_vehicle', 'INNER');
		
		if (array_key_exists("idEmployee", $arrData)) {
			$this->db->where('U.id_user', $arrData["idEmployee"]);
		}		
		
		$this->db->order_by('I.date_issue', 'desc');
		$query = $this->db->get('inspection_daily I', $arrData["limit"]);

		if ($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return false;
		}		
    }
	
    /**
     * Heavy inspection list
     * Modules: Dashboard 
     * @since 14/1/2017
     */
    public function get_heavy_inspection($arrData) 
	{
		$this->db->select("I.*, CONCAT(first_name, ' ', last_name) name, V.*");
		$this->db->join('user U', 'U.id_user = I.fk_id_user', 'INNER');
		$this->db->join('param_vehicle V', 'V.id_vehicle = I.fk_id_vehicle', 'INNER');
		
		if (array_key_exists("idEmployee", $arrData)) {
			$this->db->where('U.id_user', $arrData["idEmployee"]);
		}
		
		$this->db->order_by('I.date_issue', 'desc');
		$query = $this->db->get('inspection_heavy I', $arrData["limit"]);

		if ($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return false;
		}
    }
	
    /**
     * Special inspection list
     * Modules: Dashboard 
     * @since 8/5/2017
     */
    public function get_special_inspection_generator($arrData) 
	{
		$this->db->select("I.*, CONCAT(first_name, ' ', last_name) name, V.*");
		$this->db->join('user U', 'U.id_user = I.fk_id_user', 'INNER');
		$this->db->join('param_vehicle V', 'V.id_vehicle = I.fk_id_vehicle', 'INNER');
		
		if (array_key_exists("idEmployee", $arrData)) {
			$this->db->where('U.id_user', $arrData["idEmployee"]);
		}
		
		$this->db->order_by('I.date_issue', 'desc');
		$query = $this->db->get('inspection_generator I', $arrData["limit"]);

		if ($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return false;
		}
    }
	
    /**
     * Special inspection list
     * Modules: Dashboard 
     * @since 8/5/2017
     */
    public function get_special_inspection_hydrovac($arrData) 
	{
		$this->db->select("I.*, CONCAT(first_name, ' ', last_name) name, V.*");
		$this->db->join('user U', 'U.id_user = I.fk_id_user', 'INNER');
		$this->db->join('param_vehicle V', 'V.id_vehicle = I.fk_id_vehicle', 'INNER');
		
		if (array_key_exists("idEmployee", $arrData)) {
			$this->db->where('U.id_user', $arrData["idEmployee"]);
		}
		
		$this->db->order_by('I.date_issue', 'desc');
		$query = $this->db->get('inspection_hydrovac I', $arrData["limit"]);

		if ($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return false;
		}
    }
	
    /**
     * Special inspection list
     * Modules: Dashboard 
     * @since 8/5/2017
     */
    public function get_special_inspection_sweeper($arrData) 
	{
		$this->db->select("I.*, CONCAT(first_name, ' ', last_name) name, V.*");
		$this->db->join('user U', 'U.id_user = I.fk_id_user', 'INNER');
		$this->db->join('param_vehicle V', 'V.id_vehicle = I.fk_id_vehicle', 'INNER');
		
		if (array_key_exists("idEmployee", $arrData)) {
			$this->db->where('U.id_user', $arrData["idEmployee"]);
		}
		
		$this->db->order_by('I.date_issue', 'desc');
		$query = $this->db->get('inspection_sweeper I', $arrData["limit"]);

		if ($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return false;
		}
    }
	
    /**
     * Special inspection list
     * Modules: Dashboard 
     * @since 27/6/2017
     */
    public function get_special_inspection_water_truck($arrData) 
	{
		$this->db->select("I.*, CONCAT(first_name, ' ', last_name) name, V.*");
		$this->db->join('user U', 'U.id_user = I.fk_id_user', 'INNER');
		$this->db->join('param_vehicle V', 'V.id_vehicle = I.fk_id_vehicle', 'INNER');
		
		if (array_key_exists("idEmployee", $arrData)) {
			$this->db->where('U.id_user', $arrData["idEmployee"]);
		}
		
		$this->db->order_by('I.date_issue', 'desc');
		$query = $this->db->get('inspection_watertruck I', $arrData["limit"]);

		if ($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return false;
		}
    }
	
		/**
		 * Maintenance Check list
		 * @since 13/3/2020
		 */
		public function get_maintenance_check() 
		{
			$this->db->select('M.*, T.*, V.*, CONCAT(U.first_name, " " , U.last_name) name');
			$this->db->join('maintenance M', 'M.id_maintenance = C.fk_id_maintenance', 'INNER');
			$this->db->join('maintenance_type T', 'T.id_maintenance_type = M.fk_id_maintenance_type', 'INNER');
			$this->db->join('param_vehicle V', 'V.id_vehicle = M.fk_id_vehicle', 'INNER');
			$this->db->join('user U', 'U.id_user = M.fk_revised_by_user', 'INNER');
						
			$this->db->order_by('M.id_maintenance', 'desc');
			$query = $this->db->get('maintenance_check C');

			if ($query->num_rows() > 0) {
				return $query->result_array();
			} else {
				return false;
			}
		}



}