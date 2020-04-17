<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class Dashboard_model extends CI_Model {

		/**
		 * Contar registros del modulo DAILY INSPECTION
		 * si no es ADMIN entonces filtra por usuario
		 * @author BMOTTAG
		 * @since  14/1/2017
		 */
		public function countDailyInspection()
		{
				$userRol = $this->session->userdata("rol");
				$idUser = $this->session->userdata("id");
				
				$year = date('Y');
				$firstDay = date('Y-m-d', mktime(0,0,0, 1, 1, $year));//primer dia del año

				$sql = "SELECT count(id_inspection_daily) CONTEO";
				$sql.= " FROM inspection_daily";
				$sql.= " WHERE date_issue >= '$firstDay'";
				if($userRol == 7){ //If it is a BASIC USER, just show the records of the user session
					$sql.= " AND fk_id_user = $idUser";
				}			

				$query = $this->db->query($sql);
				$row = $query->row();
				return $row->CONTEO;
		}
		
		/**
		 * Contar registros del modulo HEAVY INSPECTION
		 * si no es ADMIN entonces filtra por usuario
		 * @author BMOTTAG
		 * @since  14/1/2017
		 */
		public function countHeavyInspection()
		{
				$userRol = $this->session->userdata("rol");
				$idUser = $this->session->userdata("id");
				
				$year = date('Y');
				$firstDay = date('Y-m-d', mktime(0,0,0, 1, 1, $year));//primer dia del año

				$sql = "SELECT count(id_inspection_heavy) CONTEO";
				$sql.= " FROM inspection_heavy";
				$sql.= " WHERE date_issue >= '$firstDay'";
				if($userRol == 7){ //If it is a BASIC USER, just show the records of the user session
					$sql.= " AND fk_id_user = $idUser";
				}			

				$query = $this->db->query($sql);
				$row = $query->row();
				return $row->CONTEO;
		}
		
		/**
		 * Contar registros del modulo SPECIAL INSPECTION
		 * si no es ADMIN entonces filtra por usuario
		 * @author BMOTTAG
		 * @since  8/5/2017
		 */
		public function countSpecialInspection()
		{
				$userRol = $this->session->userdata("rol");
				$idUser = $this->session->userdata("id");
				
				$year = date('Y');
				$firstDay = date('Y-m-d', mktime(0,0,0, 1, 1, $year));//primer dia del año

				$sql = "SELECT count(id_inspection_generator) CONTEO";
				$sql.= " FROM inspection_generator";
				$sql.= " WHERE date_issue >= '$firstDay'";
				if($userRol == 7){ //If it is a BASIC USER, just show the records of the user session
					$sql.= " AND fk_id_user = $idUser";
				}			

				$query = $this->db->query($sql);
				$row = $query->row();
				$generator = $row->CONTEO;

				$sql = "SELECT count(id_inspection_hydrovac) CONTEO";
				$sql.= " FROM inspection_hydrovac";
				$sql.= " WHERE date_issue >= '$firstDay'";
				if($userRol == 7){ //If it is a BASIC USER, just show the records of the user session
					$sql.= " AND fk_id_user = $idUser";
				}			

				$query = $this->db->query($sql);
				$row = $query->row();
				$hydrovac = $row->CONTEO;

				
				$sql = "SELECT count(id_inspection_sweeper) CONTEO";
				$sql.= " FROM inspection_sweeper";
				$sql.= " WHERE date_issue >= '$firstDay'";
				if($userRol == 7){ //If it is a BASIC USER, just show the records of the user session
					$sql.= " AND fk_id_user = $idUser";
				}			

				$query = $this->db->query($sql);
				$row = $query->row();
				$sweeper = $row->CONTEO;

				$number = $generator+ $hydrovac + $sweeper;
				return $number;
		}		
		
		
		
		
	    
	}