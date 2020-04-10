<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Home {

    private $ci;
    private $db;

    public function __construct() {
        $this->ci = & get_instance();
        !$this->ci->load->library('session') ? $this->ci->load->library('session') : false;
        !$this->ci->load->helper('url') ? $this->ci->load->helper('url') : false;
        $this->db = $this->ci->load->database("default", true);
    }

    public function check_login() {
        $error = FALSE;
        $arrModules = array("login", "gh_directorio", "ieredirect");
        if (!in_array($this->ci->uri->segment(1), $arrModules)) {
            if ($this->ci->uri->segment(1) == "menu") {
                if(($this->ci->uri->segment(2) . '/' . $this->ci->uri->segment(3)) != 'menu/salir') {
                    if (isset($this->ci->session) && $this->ci->session->userdata('id') == FALSE) {
                        $error = TRUE;
                    }
                }
            } else if ($this->ci->uri->segment(1) == "report") {
                $arrControllers = array($this->ci->uri->segment(1), "index", "generaHaulingPDF", "registro", "userAuth", "validaSesion");
                if ($this->ci->uri->segment(2) != FALSE && !in_array($this->ci->uri->segment(2), $arrControllers)) {
                    if (isset($this->ci->session) && $this->ci->session->userdata('id') == FALSE) {
                        $error = TRUE;
                    }
                }
            } else if ($this->ci->uri->segment(1) == "programming") {
                $arrControllers = array($this->ci->uri->segment(1), "verificacion", "verificacion_flha", "verificacion_tool_box");
                if ($this->ci->uri->segment(2) != FALSE && !in_array($this->ci->uri->segment(2), $arrControllers)) {
                    if (isset($this->ci->session) && $this->ci->session->userdata('id') == FALSE) {
                        $error = TRUE;
                    }
                }
            } else {
                if ($this->ci->session->userdata('id') == FALSE) {
                    $error = TRUE;
                }
            }
            
            if ($error == FALSE) {
                //Se consulta si la ruta actual tiene permiso o no en el sistema
                $this->ci->load->model('general_model', 'mm');
                $ruta_validar = '';
                for ($i = 1; $i <= 5; $i++) {
                    if ($this->ci->uri->segment($i)) {
                        $ruta_validar .= ($i == 1) ? $this->ci->uri->segment($i) : '/' . $this->ci->uri->segment($i);
                    }
                }
                
				$controller = $this->ci->uri->segment(1);
				
				$arrParam = array(
					'idRole' => $this->ci->session->userdata('role'),
					'menuURL' => $controller
				);
                if($ruta_valida = $this->ci->mm->get_role_access($arrParam)) {
					$error = FALSE;
                }else{
                    //Se consulta si el usuario actual tiene permiso para el controlador actual
                    $arrParam = array(
                        'idRole' => $this->ci->session->userdata('role'),
						'linkURL' => $controller
                    );

                    if($ruta_valida = $this->ci->mm->get_role_access($arrParam)) {
//echo $this->ci->db->last_query();						
//pr($ruta_valida); exit;
                        $error = FALSE;
                    } else {
                        $error = TRUE;
                    }
				}
            }
        }
        
        if ($error) {
            if (isset($this->ci->session) && $this->ci->session->userdata('id') == FALSE) {
                $this->ci->session->unset_userdata("auth");
                $this->ci->session->sess_destroy();
            }
            redirect(site_url("/menu/menu/salir"));
        }
    }
}
//EOC