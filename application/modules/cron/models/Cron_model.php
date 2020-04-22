<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class Cron_model extends CI_Model {

	    function __construct(){        
	        parent::__construct();
     
	    }
	    
	    /**
	     * Delete DATA
	     * @author BMOTTAG
	     * @since  21/4/2020
	     */
	    public function deleteData()
		{
				$sql = "TRUNCATE maintenance_check";
				$query = $this->db->query($sql);
				
				$sql = "TRUNCATE maintenance";
				$query = $this->db->query($sql);
				
				$sql = "DELETE FROM inspection_total WHERE id_inspection_total > 7";
				$query = $this->db->query($sql);
				
				$sql = "DELETE FROM inspection_daily WHERE id_inspection_daily > 1";
				$query = $this->db->query($sql);
				
				$sql = "DELETE FROM inspection_generator WHERE id_inspection_generator > 1";
				$query = $this->db->query($sql);
				
				$sql = "DELETE FROM inspection_heavy WHERE id_inspection_heavy > 1";
				$query = $this->db->query($sql);
				
				$sql = "DELETE FROM inspection_hydrovac WHERE id_inspection_hydrovac > 1";
				$query = $this->db->query($sql);
				
				$sql = "DELETE FROM inspection_sweeper WHERE id_inspection_sweeper > 1";
				$query = $this->db->query($sql);				
				
				$sql = "DELETE FROM inspection_watertruck WHERE id_inspection_watertruck > 1";
				$query = $this->db->query($sql);
								
				$sql = "DELETE FROM param_vehicle WHERE id_vehicle > 70";
				$query = $this->db->query($sql);
				
				$sql = "DELETE FROM param_company WHERE id_company > 33";
				$query = $this->db->query($sql);
				
				$sql = "DELETE FROM user WHERE id_user > 5";
				$query = $this->db->query($sql);

				if ($query) {
					return true;
				} else {
					return false;
				}
	    }
		
		
	    
	}