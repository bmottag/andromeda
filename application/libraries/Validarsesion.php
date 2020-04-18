<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

	/**	  
	 * Validar si una sesion ya esta iniciada en la aplicacion.
	 * Si la sesion ya esta inicializada, se continua con el flujo
	 * normal del controlador, en caso contrario se redirecciona al 
	 * login de la aplicacion.
	 * @since  Marzo 06 de 2012	 
	 **/

	class ValidarSesion {
		
		var $url = "/login"; //Redireccionar a este controlador si el usuario no se encuentra logueado.
		
		/**
		 * Redirecciona a la direccion $url en caso de que un usuario intente
		 * acceder a un controlador sin haberse logueado y sin tener una sesion
		 * de usuario.
		 */
		function __construct(){
			$CI =& get_instance();
			$CI->load->helper("url");
			$CI->load->library("session");			 
			if (!$CI->session->userdata("auth")){				
				redirect($this->url,"refresh");
			}
    	}
	
	}//EOC