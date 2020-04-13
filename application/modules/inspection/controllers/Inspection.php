<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Inspection extends CI_Controller {
	
    public function __construct() {
        parent::__construct();
        $this->load->model("inspection_model");
    }
	
	
	/**
	 * Form Add daily Inspection
     * @since 27/12/2016
     * @author BMOTTAG
	 */
	public function add_daily_inspection($id = 'x')
	{
			$data['information'] = FALSE;
			
			//si envio el id, entonces busco la informacion 
			if ($id != 'x') {
					$arrParam = array(
						"table" => "inspection_daily",
						"order" => "id_inspection_daily",
						"column" => "id_inspection_daily",
						"id" => $id
					);
					$data['information'] = $this->general_model->get_basic_search($arrParam);//info inspection_heavy
					
					$idVehicle = $data['information'][0]['fk_id_vehicle'];
			}else{
					$idVehicle = $this->session->userdata("idVehicle");
					if (!$idVehicle || empty($idVehicle) || $idVehicle == "x" ) { 
						show_error('ERROR!!! - You are in the wrong place.');	
					}
			}
			
			//busco datos del vehiculo
			$arrParam['idVehicle'] = $idVehicle;
			$data['vehicleInfo'] = $this->general_model->get_vehicle_by($arrParam);//busco datos del vehiculo
			
			//busco lista de trailers
			$arrParam = array(
				"table" => "param_vehicle",
				"order" => "id_vehicle",
				"column" => "type_level_2",
				"id" => 5
			);
			$data['trailerList'] = $this->general_model->get_basic_search($arrParam);//busco lista de trailers

			$data["view"] = $data['vehicleInfo'][0]['form'];
			$this->load->view("layout", $data);
	}
	
	/**
	 * Save daily_inspection
     * @since 27/12/2016
     * @author BMOTTAG
	 */
	public function save_daily_inspection()
	{
			header('Content-Type: application/json');
			$data = array();
		
			$idDailyInspection = $this->input->post('hddId');
			$idVehicle = $this->input->post('hddIdVehicle');
		
			$msj = "You have save your inspection record, please do not forget to sign!!";
			$flag = true;
			if ($idDailyInspection != '') {
				$msj = "You have update the Inspection record!!";
				$flag = false;
			}

			$trailer = $this->input->post('trailer');
			$trailerLights = $this->input->post('trailerLights');
			$trailerTires = $this->input->post('trailerTires');
			$trailerSlings = $this->input->post('trailerSlings');
			$trailerClean = $this->input->post('trailerClean');
			$trailerChains = $this->input->post('trailerChains');
			$trailerRatchet = $this->input->post('trailerRatchet');
			
			if($trailer!='' && ($trailerLights=='' || $trailerTires=='' || $trailerSlings=='' || $trailerClean=='' || $trailerChains=='' || $trailerRatchet=='')){
				$data["result"] = "error";
				$data["mensaje"] = "If you are using a Tralier, you must fill out the TRAILER or PUP form.";
				$data["idDailyInspection"] = $idDailyInspection;
				$this->session->set_flashdata('retornoError', 'If you are using a Tralier, you must fill out the TRAILER or PUP form.');
			}else{
				if ($idDailyInspection = $this->inspection_model->saveDailyInspection()) 
				{
					//actualizo seguimiento en la tabla de vehiculos, para mostrar mensaje
					$this->inspection_model->saveSeguimiento();
					
					//update current hours 
					$this->inspection_model->saveCurrentHours();

					/**
					 * verifico si hay comentarios y envio correo al administrador
					 */
					if($flag)
					{						
						//busco datos del vehiculo
						$arrParam['idVehicle'] = $idVehicle;
						$vehicleInfo = $this->general_model->get_vehicle_by($arrParam);//busco datos del vehiculo
						
						//el que vaya con comentario le envio correo al administrador
						$comments = $this->input->post('comments');
						if($comments != ""){
							//mensaje del correo
							$emailMsn = "<p>The following vehicle has an inspection with comments, check the full report in the system.</p>";
							$emailMsn .= "<strong>Make: </strong>" . $vehicleInfo[0]["make"];
							$emailMsn .= "<br><strong>Model: </strong>" . $vehicleInfo[0]["model"];
							$emailMsn .= "<br><strong>Unit Number: </strong>" . $vehicleInfo[0]["unit_number"];
							$emailMsn .= "<br><strong>Description: </strong>" . $vehicleInfo[0]["description"];
							$emailMsn .= "<br><strong>Comments: </strong>" . $comments;

							//busco datos del parametricos
							$arrParam = array(
								"table" => "parametric",
								"order" => "id_parametric",
								"id" => "x"
							);
							$subjet = "Inspection with comments";
							$parametric = $this->general_model->get_basic_search($arrParam);						
							$user = $parametric[2]["value"];
							$to = $parametric[0]["value"];
							$company = $parametric[1]["value"];

							$mensaje = "<html>
							<head>
							  <title> $subjet </title>
							</head>
							<body>
								<p>Dear	$user:<br/>
								</p>

								<p>$emailMsn</p>

								<p>Cordially,</p>
								<p><strong>$company</strong></p>
							</body>
							</html>";

							$cabeceras  = 'MIME-Version: 1.0' . "\r\n";
							$cabeceras .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
							$cabeceras .= 'To: ' . $user . '<' . $to . '>' . "\r\n";
							$cabeceras .= 'From: VCI APP <info@v-contracting.ca>' . "\r\n";

							//enviar correo
							mail($to, $subjet, $mensaje, $cabeceras);
						}
						
//si hay un FAIL de los siguientes campos envio correo al ADMINISTRADOR
$headLamps = $this->input->post('headLamps');
$hazardLights = $this->input->post('hazardLights');
$bakeLights = $this->input->post('bakeLights');
$workLights = $this->input->post('workLights');
$turnSignals = $this->input->post('turnSignals');
$beaconLight = $this->input->post('beaconLight');
$clearanceLights = $this->input->post('clearanceLights');

$lights_check = 1;
if($headLamps == 0 || $hazardLights == 0 || $bakeLights == 0 || $workLights == 0 || $turnSignals == 0 || $beaconLight == 0 || $clearanceLights == 0){
	$lights_check = 0;
}
						
$heater_check = $this->input->post('heater');
$brakes_check = $this->input->post('brakePedal');
$steering_wheel_check = $this->input->post('steering_wheel');
$suspension_system_check = $this->input->post('suspension_system');
$tires_check = $this->input->post('nuts');
$wipers_check = $this->input->post('wipers');
$air_brake_check = $this->input->post('air_brake');
$driver_seat_check = $this->input->post('passengerDoor');
$fuel_system_check = $this->input->post('fuel_system');

//preguntar especiales para HYDROVAC para que muestre mensaje si es inseguro sacar el camion
if ($heater_check == 0 || $brakes_check == 0 || $lights_check == 0 || $steering_wheel_check == 0 || $suspension_system_check == 0 || $tires_check == 0 || $wipers_check == 0 || $air_brake_check == 0 || $driver_seat_check == 0 || $fuel_system_check == 0) {

						//mensaje del correo
						$emailMsn = "<p>A major defect has beed identified in the last inspecton, a driver is not legally permitted to operate the vehicle until that defect is prepared.</p>";
						$emailMsn .= "<strong>Make: </strong>" . $vehicleInfo[0]["make"];
						$emailMsn .= "<br><strong>Model: </strong>" . $vehicleInfo[0]["model"];
						$emailMsn .= "<br><strong>Unit Number: </strong>" . $vehicleInfo[0]["unit_number"];
						$emailMsn .= "<br><strong>Description: </strong>" . $vehicleInfo[0]["description"];
						$emailMsn .= "<br>";

						
if ($heater_check == 0) {
	$emailMsn .= "<br>Heater - Fail"; 
}
if ($brakes_check == 0) {
	$emailMsn .= "<br>Brake pedal - Fail"; 
}
if ($lights_check == 0) {
	$emailMsn .= "<br>Lamps and reflectors - Fail"; 
}
if ($steering_wheel_check == 0) {
	$emailMsn .= "<br>Steering wheel - Fail"; 
}
if ($suspension_system_check == 0) {
	$emailMsn .= "<br>Suspension system - Fail"; 
}
if ($tires_check == 0) {
	$emailMsn .= "<br>Tires/Lug Nuts/Pressure - Fail"; 
}
if ($wipers_check == 0) {
	$emailMsn .= "<br>Wipers/Washers - Fail"; 
}
if ($air_brake_check == 0) {
	$emailMsn .= "<br>Air brake system - Fail"; 
}
if ($driver_seat_check == 0) {
	$emailMsn .= "<br>Driver and Passenger door - Fail"; 
}
if ($fuel_system_check == 0) {
	$emailMsn .= "<br>Fuel system - Fail"; 
}
						
						
						//busco datos del parametricos
						$arrParam = array(
							"table" => "parametric",
							"order" => "id_parametric",
							"id" => "x"
						);
						$subjet = "Inspection with major defect";
						$parametric = $this->general_model->get_basic_search($arrParam);						
						$user = $parametric[2]["value"];
						$to = $parametric[0]["value"];
						$company = $parametric[1]["value"];

						$mensaje = "<html>
						<head>
						  <title> $subjet </title>
						</head>
						<body>
							<p>Dear	$user:<br/>
							</p>

							<p>$emailMsn</p>

							<p>Cordially,</p>
							<p><strong>$company</strong></p>
						</body>
						</html>";

						$cabeceras  = 'MIME-Version: 1.0' . "\r\n";
						$cabeceras .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
						$cabeceras .= 'To: ' . $user . '<' . $to . '>' . "\r\n";
						$cabeceras .= 'From: VCI APP <info@v-contracting.ca>' . "\r\n";

						//enviar correo
						mail($to, $subjet, $mensaje, $cabeceras);
					
}
					
						
						//verificar el kilometraje
						//si se paso del cambio de aceite o esta cerca entonces enviar correo al administrador
						$hours = $this->input->post('hours');
						$oilChange = $this->input->post('oilChange');
						$diferencia = $oilChange - $hours;
						
						if($diferencia <= 50)
						{
							//enviar correo
							//mensaje del correo
							$emailMsn = "<p>The following vehicle should change the oil as soon as possible.</p>";
							$emailMsn .= "<strong>Make: </strong>" . $vehicleInfo[0]["make"];
							$emailMsn .= "<br><strong>Model: </strong>" . $vehicleInfo[0]["model"];
							$emailMsn .= "<br><strong>Unit Number: </strong>" . $vehicleInfo[0]["unit_number"];
							$emailMsn .= "<br><strong>Description: </strong>" . $vehicleInfo[0]["description"];
							$emailMsn .= "<br><strong>Current Hours/Kilometers: </strong>" . number_format($hours);
							$emailMsn .= "<br><strong>Next Oil Change: </strong>" . number_format($oilChange);
							$emailMsn .= "<p>If you change the Oil, do not forget to update the next Oil Change in the system.</p>";

							//busco datos del parametricos
							$arrParam = array(
								"table" => "parametric",
								"order" => "id_parametric",
								"id" => "x"
							);
							$subjet = "Oil Change";
							$parametric = $this->general_model->get_basic_search($arrParam);						
							$user = $parametric[2]["value"];
							$to = $parametric[0]["value"];
							$company = $parametric[1]["value"];

							$mensaje = "<html>
							<head>
							  <title> $subjet </title>
							</head>
							<body>
								<p>Dear	$user:<br/>
								</p>

								<p>$emailMsn</p>

								<p>Cordially,</p>
								<p><strong>$company</strong></p>
							</body>
							</html>";

							$cabeceras  = 'MIME-Version: 1.0' . "\r\n";
							$cabeceras .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
							$cabeceras .= 'To: ' . $user . '<' . $to . '>' . "\r\n";
							$cabeceras .= 'From: VCI APP <info@v-contracting.ca>' . "\r\n";

							//enviar correo
							mail($to, $subjet, $mensaje, $cabeceras);
						} 
						
					}

					$data["result"] = true;
					$data["idDailyInspection"] = $idDailyInspection;
					$this->session->set_flashdata('retornoExito', $msj);
				} else {
					$data["result"] = "error";
					$data["mensaje"] = "Error!!! Ask for help.";
					$data["idDailyInspection"] = "";
					$this->session->set_flashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
				}
			}

			echo json_encode($data);
    }	

	/**
	 * Form Add Heavy Inspection
     * @since 17/12/2016
     * @author BMOTTAG
	 */
	public function add_heavy_inspection($id = 'x')
	{
			$data['information'] = FALSE;
					
			//si envio el id, entonces busco la informacion 
			if ($id != 'x') {
					$arrParam = array(
						"table" => "inspection_heavy",
						"order" => "id_inspection_heavy",
						"column" => "id_inspection_heavy",
						"id" => $id
					);
					$data['information'] = $this->general_model->get_basic_search($arrParam);//info inspection_heavy
					
					$idVehicle = $data['information'][0]['fk_id_vehicle'];
			}else{
					$idVehicle = $this->session->userdata("idVehicle");
					if (!$idVehicle || empty($idVehicle) || $idVehicle == "x" ) { 
						show_error('ERROR!!! - You are in the wrong place.');	
					}
			}
			
			//busco datos del vehiculo
			$arrParam['idVehicle'] = $idVehicle;
			$data['vehicleInfo'] = $this->general_model->get_vehicle_by($arrParam);

			$data["view"] = $data['vehicleInfo'][0]['form'];
			$this->load->view("layout", $data);
	}
	
	/**
	 * Save heavy_inspection
     * @since 27/12/2016
     * @author BMOTTAG
	 */
	public function save_heavy_inspection()
	{			
			header('Content-Type: application/json');
			$data = array();
			
			$idHeavyInspection = $this->input->post('hddId');
			$idVehicle = $this->input->post('hddIdVehicle');
			
			$msj = "You have save your inspection record, please do not forget to sign!!";
			$flag = true;
			if ($idHeavyInspection != '') {
				$flag = false;
				$msj = "You have update the Inspection record!!";
			}

			if ($idHeavyInspection = $this->inspection_model->saveHeavyInspection()) 
			{
				//update current hours 
				$this->inspection_model->saveCurrentHours();
				
				/**
				 * verifico si hay comentarios y envio correo al administrador
				 */
				if($flag)
				{					
					//busco datos del vehiculo
					$arrParam['idVehicle'] = $idVehicle;
					$vehicleInfo = $this->general_model->get_vehicle_by($arrParam);//busco datos del vehiculo
					
					//el que vaya con comentario le envio correo al administrador
					$comments = $this->input->post('comments');
					if($comments != ""){
						//mensaje del correo
						$emailMsn = "<p>The following vehicle has an inspection with comments, check the full report in the system.</p>";
						$emailMsn .= "<strong>Make: </strong>" . $vehicleInfo[0]["make"];
						$emailMsn .= "<br><strong>Model: </strong>" . $vehicleInfo[0]["model"];
						$emailMsn .= "<br><strong>Unit Number: </strong>" . $vehicleInfo[0]["unit_number"];
						$emailMsn .= "<br><strong>Description: </strong>" . $vehicleInfo[0]["description"];
						$emailMsn .= "<br><strong>Comments: </strong>" . $comments;

						//busco datos del parametricos
						$arrParam = array(
							"table" => "parametric",
							"order" => "id_parametric",
							"id" => "x"
						);
						$subjet = "Inspection with comments";
						$parametric = $this->general_model->get_basic_search($arrParam);						
						$user = $parametric[2]["value"];
						$to = $parametric[0]["value"];
						$company = $parametric[1]["value"];

						$mensaje = "<html>
						<head>
						  <title> $subjet </title>
						</head>
						<body>
							<p>Dear	$user:<br/>
							</p>

							<p>$emailMsn</p>

							<p>Cordially,</p>
							<p><strong>$company</strong></p>
						</body>
						</html>";

						$cabeceras  = 'MIME-Version: 1.0' . "\r\n";
						$cabeceras .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
						$cabeceras .= 'To: ' . $user . '<' . $to . '>' . "\r\n";
						$cabeceras .= 'From: VCI APP <info@v-contracting.ca>' . "\r\n";

						//enviar correo
						mail($to, $subjet, $mensaje, $cabeceras);
					}
										
					//verificar el kilometraje
					//si se paso del cambio de aceite o esta cerca entonces enviar correo al administrador
					$hours = $this->input->post('hours');
					$oilChange = $this->input->post('oilChange');
					$diferencia = $oilChange - $hours;
										
					if($diferencia <= 50)
					{
						//enviar correo
						//mensaje del correo
						$emailMsn = "<p>The following vehicle should change the oil as soon as possible.</p>";
						$emailMsn .= "<strong>Make: </strong>" . $vehicleInfo[0]["make"];
						$emailMsn .= "<br><strong>Model: </strong>" . $vehicleInfo[0]["model"];
						$emailMsn .= "<br><strong>Unit Number: </strong>" . $vehicleInfo[0]["unit_number"];
						$emailMsn .= "<br><strong>Description: </strong>" . $vehicleInfo[0]["description"];
						$emailMsn .= "<br><strong>Current Hours/Kilometers: </strong>" . number_format($hours);
						$emailMsn .= "<br><strong>Next Oil Change: </strong>" . number_format($oilChange);
						$emailMsn .= "<p>If you change the Oil, do not forget to update the next Oil Change in the system.</p>";

						//busco datos del parametricos
						$arrParam = array(
							"table" => "parametric",
							"order" => "id_parametric",
							"id" => "x"
						);
						$subjet = "Oil Change";
						$parametric = $this->general_model->get_basic_search($arrParam);						
						$user = $parametric[2]["value"];
						$to = $parametric[0]["value"];
						$company = $parametric[1]["value"];

						$mensaje = "<html>
						<head>
						  <title> $subjet </title>
						</head>
						<body>
							<p>Dear	$user:<br/>
							</p>

							<p>$emailMsn</p>

							<p>Cordially,</p>
							<p><strong>$company</strong></p>
						</body>
						</html>";

						$cabeceras  = 'MIME-Version: 1.0' . "\r\n";
						$cabeceras .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
						$cabeceras .= 'To: ' . $user . '<' . $to . '>' . "\r\n";
						$cabeceras .= 'From: VCI APP <info@v-contracting.ca>' . "\r\n";

						//enviar correo
						mail($to, $subjet, $mensaje, $cabeceras);
					} 
					
				}
				
				$data["result"] = true;
				$data["idHeavyInspection"] = $idHeavyInspection;
				$this->session->set_flashdata('retornoExito', $msj);
			} else {
				$data["result"] = "error";
				$data["mensaje"] = "Error!!! Ask for help.";
				$data["idHeavyInspection"] = "";
				$this->session->set_flashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
			}

			echo json_encode($data);
    }
	
	/**
	 * Signature
     * @since 27/12/2016
     * @author BMOTTAG
	 */
	public function add_signature($typo, $idInspection)
	{
			if (empty($typo) || empty($idInspection) ) {
				show_error('ERROR!!! - You are in the wrong place.');
			}
		
			if($_POST){
				
				//update signature with the name of de file
				$name = "images/signature/inspection/" . $typo . "_" . $idInspection . ".png";
				
				$arrParam = array(
					"table" => "inspection_" . $typo,
					"primaryKey" => "id_inspection_" . $typo,
					"id" => $idInspection,
					"column" => "signature",
					"value" => $name
				);
				
				$data_uri = $this->input->post("image");
				$encoded_image = explode(",", $data_uri)[1];
				$decoded_image = base64_decode($encoded_image);
				file_put_contents($name, $decoded_image);
				
				$data['linkBack'] = "inspection/add_" . $typo . "_inspection/" . $idInspection;
				$data['titulo'] = "<i class='fa fa-life-saver fa-fw'></i>SIGNATURE";
				if ($this->general_model->updateRecord($arrParam)) {
					//$this->session->set_flashdata('retornoExito', 'You just save your signature!!!');
					
					$data['clase'] = "alert-success";
					$data['msj'] = "Good job, you have save your signature.";	
				} else {
					//$this->session->set_flashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
					
					$data['clase'] = "alert-danger";
					$data['msj'] = "Ask for help.";
				}
				
				$data["view"] = 'template/answer';
				$this->load->view("layout", $data);
				//redirect("/inspection/add_" . $typo . "_inspection/" . $idInspection,'refresh');
			}else{		
				$this->load->view('template/make_signature');
			}
	}
	
	/**
	 * Form Generator Inspection
     * @since 16/3/2017
     * @author BMOTTAG
	 */
	public function add_generator_inspection($id = 'x')
	{
			$data['information'] = FALSE;
					
			//si envio el id, entonces busco la informacion 
			if ($id != 'x') {
					$arrParam = array(
						"table" => "inspection_generator",
						"order" => "id_inspection_generator",
						"column" => "id_inspection_generator",
						"id" => $id
					);
					$data['information'] = $this->general_model->get_basic_search($arrParam);//info inspection_generator
					
					$idVehicle = $data['information'][0]['fk_id_vehicle'];
			}else{
					$idVehicle = $this->session->userdata("idVehicle");
					if (!$idVehicle || empty($idVehicle) || $idVehicle == "x" ) { 
						show_error('ERROR!!! - You are in the wrong place.');	
					}
			}
						
			//busco datos del vehiculo
			$arrParam['idVehicle'] = $idVehicle;
			$data['vehicleInfo'] = $this->general_model->get_vehicle_by($arrParam);

			$data["view"] = $data['vehicleInfo'][0]['form'];
			$this->load->view("layout", $data);
	}	
	
	/**
	 * Save generator_inspection
     * @since 17/3/2017
     * @author BMOTTAG
	 */
	public function save_generator_inspection()
	{			
			header('Content-Type: application/json');
			$data = array();
			
			$idGeneratorInspection = $this->input->post('hddId');
			$idVehicle = $this->input->post('hddIdVehicle');
			
			$msj = "You have save your inspection record, please do not forget to sign!!";
			$flag = true;
			if ($idGeneratorInspection != '') {
				$flag = false;
				$msj = "You have update the Inspection record!!";
			}

			if ($idGeneratorInspection = $this->inspection_model->saveGeneratorInspection()) 
			{
				//update current hours 
				$this->inspection_model->saveCurrentHours();
				
				/**
				 * verifico si hay comentarios y envio correo al administrador
				 */
				if($flag)
				{
					//busco datos del vehiculo
					$arrParam['idVehicle'] = $idVehicle;
					$vehicleInfo = $this->general_model->get_vehicle_by($arrParam);//busco datos del vehiculo
					
					//el que vaya con comentario le envio correo al administrador
					$comments = $this->input->post('comments');
					if($comments != ""){
						//mensaje del correo
						$emailMsn = "<p>The following vehicle has an inspection with comments, check the full report in the system.</p>";
						$emailMsn .= "<strong>Make: </strong>" . $vehicleInfo[0]["make"];
						$emailMsn .= "<br><strong>Model: </strong>" . $vehicleInfo[0]["model"];
						$emailMsn .= "<br><strong>Unit Number: </strong>" . $vehicleInfo[0]["unit_number"];
						$emailMsn .= "<br><strong>Description: </strong>" . $vehicleInfo[0]["description"];
						$emailMsn .= "<br><strong>Comments: </strong>" . $comments;

						//busco datos del parametricos
						$arrParam = array(
							"table" => "parametric",
							"order" => "id_parametric",
							"id" => "x"
						);
						$subjet = "Inspection with comments";
						$parametric = $this->general_model->get_basic_search($arrParam);						
						$user = $parametric[2]["value"];
						$to = $parametric[0]["value"];
						$company = $parametric[1]["value"];

						$mensaje = "<html>
						<head>
						  <title> $subjet </title>
						</head>
						<body>
							<p>Dear	$user:<br/>
							</p>

							<p>$emailMsn</p>

							<p>Cordially,</p>
							<p><strong>$company</strong></p>
						</body>
						</html>";

						$cabeceras  = 'MIME-Version: 1.0' . "\r\n";
						$cabeceras .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
						$cabeceras .= 'To: ' . $user . '<' . $to . '>' . "\r\n";
						$cabeceras .= 'From: VCI APP <info@v-contracting.ca>' . "\r\n";

						//enviar correo
						mail($to, $subjet, $mensaje, $cabeceras);
					}
					
					//verificar el kilometraje
					//si se paso del cambio de aceite o esta cerca entonces enviar correo al administrador
					$hours = $this->input->post('hours');
					$oilChange = $this->input->post('oilChange');
					$diferencia = $oilChange - $hours;
										
					if($diferencia <= 50)
					{
						//enviar correo
						//mensaje del correo
						$emailMsn = "<p>The following vehicle should change the oil as soon as possible.</p>";
						$emailMsn .= "<strong>Make: </strong>" . $vehicleInfo[0]["make"];
						$emailMsn .= "<br><strong>Model: </strong>" . $vehicleInfo[0]["model"];
						$emailMsn .= "<br><strong>Unit Number: </strong>" . $vehicleInfo[0]["unit_number"];
						$emailMsn .= "<br><strong>Description: </strong>" . $vehicleInfo[0]["description"];
						$emailMsn .= "<br><strong>Current Hours/Kilometers: </strong>" . number_format($hours);
						$emailMsn .= "<br><strong>Next Oil Change: </strong>" . number_format($oilChange);
						$emailMsn .= "<p>If you change the Oil, do not forget to update the next Oil Change in the system.</p>";

						//busco datos del parametricos
						$arrParam = array(
							"table" => "parametric",
							"order" => "id_parametric",
							"id" => "x"
						);
						$subjet = "Oil Change";
						$parametric = $this->general_model->get_basic_search($arrParam);						
						$user = $parametric[2]["value"];
						$to = $parametric[0]["value"];
						$company = $parametric[1]["value"];

						$mensaje = "<html>
						<head>
						  <title> $subjet </title>
						</head>
						<body>
							<p>Dear	$user:<br/>
							</p>

							<p>$emailMsn</p>

							<p>Cordially,</p>
							<p><strong>$company</strong></p>
						</body>
						</html>";

						$cabeceras  = 'MIME-Version: 1.0' . "\r\n";
						$cabeceras .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
						$cabeceras .= 'To: ' . $user . '<' . $to . '>' . "\r\n";
						$cabeceras .= 'From: VCI APP <info@v-contracting.ca>' . "\r\n";

						//enviar correo
						mail($to, $subjet, $mensaje, $cabeceras);
					} 
					
				}
				
				$data["result"] = true;
				$data["idGeneratorInspection"] = $idGeneratorInspection;
				$this->session->set_flashdata('retornoExito', $msj);
			} else {
				$data["result"] = "error";
				$data["mensaje"] = "Error!!! Ask for help.";
				$data["idGeneratorInspection"] = "";
				$this->session->set_flashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
			}

			echo json_encode($data);
    }
	
	/**
	 * Form SWEEPER Inspection
     * @since 22/4/2017
     * @author BMOTTAG
	 */
	public function add_sweeper_inspection($id = 'x')
	{
			$data['information'] = FALSE;
					
			//si envio el id, entonces busco la informacion 
			if ($id != 'x') {
					$arrParam = array(
						"table" => "inspection_sweeper",
						"order" => "id_inspection_sweeper",
						"column" => "id_inspection_sweeper",
						"id" => $id
					);
					$data['information'] = $this->general_model->get_basic_search($arrParam);//info inspection_generator

					$idVehicle = $data['information'][0]['fk_id_vehicle'];
			}else{
					$idVehicle = $this->session->userdata("idVehicle");
					if (!$idVehicle || empty($idVehicle) || $idVehicle == "x" ) { 
						show_error('ERROR!!! - You are in the wrong place.');	
					}
			}
						
			//busco datos del vehiculo
			$arrParam['idVehicle'] = $idVehicle;
			$data['vehicleInfo'] = $this->general_model->get_vehicle_by($arrParam);

			$data["view"] = $data['vehicleInfo'][0]['form'];
			$this->load->view("layout", $data);
	}	
	
	/**
	 * Save sweeper inspection
     * @since 22/4/2017
     * @author BMOTTAG
	 */
	public function save_sweeper_inspection()
	{			
			header('Content-Type: application/json');
			$data = array();
			
			$idSweeperInspection = $this->input->post('hddId');
			$idVehicle = $this->input->post('hddIdVehicle');
			
			$msj = "You have save your inspection record, please do not forget to sign!!";
			$flag = true;
			if ($idSweeperInspection != '') {
				$flag = false;
				$msj = "You have update the Inspection record!!";
			}

			if ($idSweeperInspection = $this->inspection_model->saveSweeperInspection()) 
			{
				//update current hours 
				$this->inspection_model->saveCurrentHours();
				
				/**
				 * verifico si hay comentarios y envio correo al administrador
				 */
				if($flag)
				{					
					//busco datos del vehiculo
					$arrParam['idVehicle'] = $idVehicle;
					$vehicleInfo = $this->general_model->get_vehicle_by($arrParam);//busco datos del vehiculo
					
					//el que vaya con comentario le envio correo al administrador
					$comments = $this->input->post('comments');
					if($comments != ""){
						//mensaje del correo
						$emailMsn = "<p>The following vehicle has an inspection with comments, check the full report in the system.</p>";
						$emailMsn .= "<strong>Make: </strong>" . $vehicleInfo[0]["make"];
						$emailMsn .= "<br><strong>Model: </strong>" . $vehicleInfo[0]["model"];
						$emailMsn .= "<br><strong>Unit Number: </strong>" . $vehicleInfo[0]["unit_number"];
						$emailMsn .= "<br><strong>Description: </strong>" . $vehicleInfo[0]["description"];
						$emailMsn .= "<br><strong>Comments: </strong>" . $comments;

						//busco datos del parametricos
						$arrParam = array(
							"table" => "parametric",
							"order" => "id_parametric",
							"id" => "x"
						);
						$subjet = "Inspection with comments";
						$parametric = $this->general_model->get_basic_search($arrParam);						
						$user = $parametric[2]["value"];
						$to = $parametric[0]["value"];
						$company = $parametric[1]["value"];

						$mensaje = "<html>
						<head>
						  <title> $subjet </title>
						</head>
						<body>
							<p>Dear	$user:<br/>
							</p>

							<p>$emailMsn</p>

							<p>Cordially,</p>
							<p><strong>$company</strong></p>
						</body>
						</html>";

						$cabeceras  = 'MIME-Version: 1.0' . "\r\n";
						$cabeceras .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
						$cabeceras .= 'To: ' . $user . '<' . $to . '>' . "\r\n";
						$cabeceras .= 'From: VCI APP <info@v-contracting.ca>' . "\r\n";

						//enviar correo
						mail($to, $subjet, $mensaje, $cabeceras);
					}
										
					//verificar el kilometraje
					//si se paso del cambio de aceite o esta cerca entonces enviar correo al administrador
					$hours = $this->input->post('hours');
					$oilChange = $this->input->post('oilChange');
					$diferencia = $oilChange - $hours;
					
					$hours2 = $this->input->post('hours2');
					$oilChange2 = $this->input->post('oilChange2');
					$diferencia2 = $oilChange2 - $hours2;
										
					if($diferencia <= 50 || $diferencia2 <= 50)
					{
						//enviar correo
						//mensaje del correo
						$emailMsn = "<p>The following vehicle need to chage the oil as soon as posible.</p>";
						$emailMsn .= "<strong>Make: </strong>" . $vehicleInfo[0]["make"];
						$emailMsn .= "<br><strong>Model: </strong>" . $vehicleInfo[0]["model"];
						$emailMsn .= "<br><strong>Unit Number: </strong>" . $vehicleInfo[0]["unit_number"];
						$emailMsn .= "<br><strong>Description: </strong>" . $vehicleInfo[0]["description"];
						$emailMsn .= "<br><strong>Current Hours/Kilometers: </strong>" . number_format($hours);
						$emailMsn .= "<br><strong>Next Oil Change: </strong>" . number_format($oilChange);
						$emailMsn .= "<p>If you change the Oil, do not forget to update the next Oil Change in the system.</p>";

						//busco datos del parametricos
						$arrParam = array(
							"table" => "parametric",
							"order" => "id_parametric",
							"id" => "x"
						);
						$subjet = "Oil Change";
						$parametric = $this->general_model->get_basic_search($arrParam);						
						$user = $parametric[2]["value"];
						$to = $parametric[0]["value"];
						$company = $parametric[1]["value"];

						$mensaje = "<html>
						<head>
						  <title> $subjet </title>
						</head>
						<body>
							<p>Dear	$user:<br/>
							</p>

							<p>$emailMsn</p>

							<p>Cordially,</p>
							<p><strong>$company</strong></p>
						</body>
						</html>";

						$cabeceras  = 'MIME-Version: 1.0' . "\r\n";
						$cabeceras .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
						$cabeceras .= 'To: ' . $user . '<' . $to . '>' . "\r\n";
						$cabeceras .= 'From: VCI APP <info@v-contracting.ca>' . "\r\n";

						//enviar correo
						mail($to, $subjet, $mensaje, $cabeceras);
					} 
					
				}
				
				$data["result"] = true;
				$data["idSweeperInspection"] = $idSweeperInspection;
				$this->session->set_flashdata('retornoExito', $msj);
			} else {
				$data["result"] = "error";
				$data["mensaje"] = "Error!!! Ask for help.";
				$data["idSweeperInspection"] = "";
				$this->session->set_flashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
			}

			echo json_encode($data);
    }
	
	/**
	 * Form hydrovac Inspection
     * @since 23/4/2017
     * @author BMOTTAG
	 */
	public function add_hydrovac_inspection($id = 'x')
	{
			$data['information'] = FALSE;
					
			//si envio el id, entonces busco la informacion 
			if ($id != 'x') {
					$arrParam = array(
						"table" => "inspection_hydrovac",
						"order" => "id_inspection_hydrovac",
						"column" => "id_inspection_hydrovac",
						"id" => $id
					);
					$data['information'] = $this->general_model->get_basic_search($arrParam);//info inspection_generator
					
					$idVehicle = $data['information'][0]['fk_id_vehicle'];
			}else{
					$idVehicle = $this->session->userdata("idVehicle");
					if (!$idVehicle || empty($idVehicle) || $idVehicle == "x" ) { 
						show_error('ERROR!!! - You are in the wrong place.');	
					}
			}
						
			//busco datos del vehiculo
			$arrParam['idVehicle'] = $idVehicle;
			$data['vehicleInfo'] = $this->general_model->get_vehicle_by($arrParam);

			$data["view"] = $data['vehicleInfo'][0]['form'];
			$this->load->view("layout", $data);
	}	
	
	/**
	 * Save hydrovac inspection
     * @since 23/4/2017
     * @author BMOTTAG
	 */
	public function save_hydrovac_inspection()
	{			
			header('Content-Type: application/json');
			$data = array();
			
			$idHydrovacInspection = $this->input->post('hddId');
			$idVehicle = $this->input->post('hddIdVehicle');

			$msj = "You have save your inspection record, please do not forget to sign!!";
			$flag = true;
			if ($idHydrovacInspection != '') {
				$flag = false;
				$msj = "You have update the Inspection record!!";
			}

			if ($idHydrovacInspection = $this->inspection_model->saveHydrovacInspection()) 
			{
				//actualizo seguimiento en la tabla de vehiculos, para mostrar mensaje
				$this->inspection_model->saveSeguimientoHydrovac();
				
				//update current hours 
				$this->inspection_model->saveCurrentHours();
				
				/**
				 * si es un registro nuevo entonces guardo el historial de cambio de aceite
				 * y verifico si hay comentarios y envio correo al administrador
				 */
				if($flag)
				{					
					//busco datos del vehiculo
					$arrParam['idVehicle'] = $idVehicle;
					$vehicleInfo = $this->general_model->get_vehicle_by($arrParam);//busco datos del vehiculo
					
					//el que vaya con comentario le envio correo al administrador
					$comments = $this->input->post('comments');
					if($comments != ""){
						//mensaje del correo
						$emailMsn = "<p>The following vehicle has an inspection with comments, check the full report in the system.</p>";
						$emailMsn .= "<strong>Make: </strong>" . $vehicleInfo[0]["make"];
						$emailMsn .= "<br><strong>Model: </strong>" . $vehicleInfo[0]["model"];
						$emailMsn .= "<br><strong>Unit Number: </strong>" . $vehicleInfo[0]["unit_number"];
						$emailMsn .= "<br><strong>Description: </strong>" . $vehicleInfo[0]["description"];
						$emailMsn .= "<br><strong>Comments: </strong>" . $comments;

						//busco datos del parametricos
						$arrParam = array(
							"table" => "parametric",
							"order" => "id_parametric",
							"id" => "x"
						);
						$subjet = "Inspection with comments";
						$parametric = $this->general_model->get_basic_search($arrParam);						
						$user = $parametric[2]["value"];
						$to = $parametric[0]["value"];
						$company = $parametric[1]["value"];

						$mensaje = "<html>
						<head>
						  <title> $subjet </title>
						</head>
						<body>
							<p>Dear	$user:<br/>
							</p>

							<p>$emailMsn</p>

							<p>Cordially,</p>
							<p><strong>$company</strong></p>
						</body>
						</html>";

						$cabeceras  = 'MIME-Version: 1.0' . "\r\n";
						$cabeceras .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
						$cabeceras .= 'To: ' . $user . '<' . $to . '>' . "\r\n";
						$cabeceras .= 'From: VCI APP <info@v-contracting.ca>' . "\r\n";

						//enviar correo
						mail($to, $subjet, $mensaje, $cabeceras);
					}
					
//si hay un FAIL de los siguientes campos envio correo al ADMINISTRADOR
$headLamps = $this->input->post('headLamps');
$hazardLights = $this->input->post('hazardLights');
$clearanceLights = $this->input->post('clearanceLights');
$tailLights = $this->input->post('tailLights');
$workLights = $this->input->post('workLights');
$turnSignals = $this->input->post('turnSignals');
$beaconLight = $this->input->post('beaconLights');


$lights_check = 1;
if($headLamps == 0 || $hazardLights == 0 || $tailLights == 0 || $workLights == 0 || $turnSignals == 0 || $beaconLight == 0 || $clearanceLights == 0){
	$lights_check = 0;
}
						
$heater_check = $this->input->post('heater');
$brakes_check = $this->input->post('brake');
$steering_wheel_check = $this->input->post('steering_wheel');
$suspension_system_check = $this->input->post('suspension_system');
$tires_check = $this->input->post('tires');
$wipers_check = $this->input->post('wipers');
$air_brake_check = $this->input->post('air_brake');
$driver_seat_check = $this->input->post('door');
$fuel_system_check = $this->input->post('fuel_system');

//preguntar especiales para HYDROVAC para que muestre mensaje si es inseguro sacar el camion
if ($heater_check == 0 || $brakes_check == 0 || $lights_check == 0 || $steering_wheel_check == 0 || $suspension_system_check == 0 || $tires_check == 0 || $wipers_check == 0 || $air_brake_check == 0 || $driver_seat_check == 0 || $fuel_system_check == 0) {

						//mensaje del correo
						$emailMsn = "<p>A major defect has beed identified in the last inspecton, a driver is not legally permitted to operate the vehicle until that defect is prepared.</p>";
						$emailMsn .= "<strong>Make: </strong>" . $vehicleInfo[0]["make"];
						$emailMsn .= "<br><strong>Model: </strong>" . $vehicleInfo[0]["model"];
						$emailMsn .= "<br><strong>Unit Number: </strong>" . $vehicleInfo[0]["unit_number"];
						$emailMsn .= "<br><strong>Description: </strong>" . $vehicleInfo[0]["description"];
						$emailMsn .= "<br>";

						
if ($heater_check == 0) {
	$emailMsn .= "<br>Heater - Fail"; 
}
if ($brakes_check == 0) {
	$emailMsn .= "<br>Brake pedal - Fail"; 
}
if ($lights_check == 0) {
	$emailMsn .= "<br>Lamps and reflectors - Fail"; 
}
if ($steering_wheel_check == 0) {
	$emailMsn .= "<br>Steering wheel - Fail"; 
}
if ($suspension_system_check == 0) {
	$emailMsn .= "<br>Suspension system - Fail"; 
}
if ($tires_check == 0) {
	$emailMsn .= "<br>Tires/Lug Nuts/Pressure - Fail"; 
}
if ($wipers_check == 0) {
	$emailMsn .= "<br>Wipers/Washers - Fail"; 
}
if ($air_brake_check == 0) {
	$emailMsn .= "<br>Air brake system - Fail"; 
}
if ($driver_seat_check == 0) {
	$emailMsn .= "<br>Driver and Passenger door - Fail"; 
}
if ($fuel_system_check == 0) {
	$emailMsn .= "<br>Fuel system - Fail"; 
}
						
						
						//busco datos del parametricos
						$arrParam = array(
							"table" => "parametric",
							"order" => "id_parametric",
							"id" => "x"
						);
						$subjet = "Inspection with major defect";
						$parametric = $this->general_model->get_basic_search($arrParam);						
						$user = $parametric[2]["value"];
						$to = $parametric[0]["value"];
						$company = $parametric[1]["value"];

						$mensaje = "<html>
						<head>
						  <title> $subjet </title>
						</head>
						<body>
							<p>Dear	$user:<br/>
							</p>

							<p>$emailMsn</p>

							<p>Cordially,</p>
							<p><strong>$company</strong></p>
						</body>
						</html>";

						$cabeceras  = 'MIME-Version: 1.0' . "\r\n";
						$cabeceras .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
						$cabeceras .= 'To: ' . $user . '<' . $to . '>' . "\r\n";
						$cabeceras .= 'From: VCI APP <info@v-contracting.ca>' . "\r\n";

						//enviar correo
						mail($to, $subjet, $mensaje, $cabeceras);
					
}
										
					//verificar el kilometraje
					//si se paso del cambio de aceite o esta cerca entonces enviar correo al administrador
					$hours = $this->input->post('hours');
					$oilChange = $this->input->post('oilChange');
					$diferencia = $oilChange - $hours;
					
					$hours2 = $this->input->post('hours2');
					$oilChange2 = $this->input->post('oilChange2');
					$diferencia2 = $oilChange2 - $hours2;
					
					$hours3 = $this->input->post('hours3');
					$oilChange3 = $this->input->post('oilChange3');
					$diferencia3 = $oilChange3 - $hours3;
										
					if($diferencia <= 50 || $diferencia2 <= 50 || $diferencia3 <= 50)
					{
						//enviar correo
						//mensaje del correo
						$emailMsn = "<p>The following vehicle need to chage the oil as soon as posible.</p>";
						$emailMsn .= "<strong>Make: </strong>" . $vehicleInfo[0]["make"];
						$emailMsn .= "<br><strong>Model: </strong>" . $vehicleInfo[0]["model"];
						$emailMsn .= "<br><strong>Unit Number: </strong>" . $vehicleInfo[0]["unit_number"];
						$emailMsn .= "<br><strong>Description: </strong>" . $vehicleInfo[0]["description"];
						$emailMsn .= "<br><strong>Current Hours/Kilometers: </strong>" . number_format($hours);
						$emailMsn .= "<br><strong>Next Oil Change: </strong>" . number_format($oilChange);
						$emailMsn .= "<p>If you change the Oil, do not forget to update the next Oil Change in the system.</p>";

						//busco datos del parametricos
						$arrParam = array(
							"table" => "parametric",
							"order" => "id_parametric",
							"id" => "x"
						);
						$subjet = "Oil Change";
						$parametric = $this->general_model->get_basic_search($arrParam);						
						$user = $parametric[2]["value"];
						$to = $parametric[0]["value"];
						$company = $parametric[1]["value"];

						$mensaje = "<html>
						<head>
						  <title> $subjet </title>
						</head>
						<body>
							<p>Dear	$user:<br/>
							</p>

							<p>$emailMsn</p>

							<p>Cordially,</p>
							<p><strong>$company</strong></p>
						</body>
						</html>";

						$cabeceras  = 'MIME-Version: 1.0' . "\r\n";
						$cabeceras .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
						$cabeceras .= 'To: ' . $user . '<' . $to . '>' . "\r\n";
						$cabeceras .= 'From: VCI APP <info@v-contracting.ca>' . "\r\n";

						//enviar correo
						mail($to, $subjet, $mensaje, $cabeceras);
					} 
					
				}
				
				$data["result"] = true;
				$data["idHydrovacInspection"] = $idHydrovacInspection;
				$this->session->set_flashdata('retornoExito', $msj);
			} else {
				$data["result"] = "error";
				$data["mensaje"] = "Error!!! Ask for help.";
				$data["idHydrovacInspection"] = "";
				$this->session->set_flashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
			}

			echo json_encode($data);
    }
	
	/**
	 * Form water truck Inspection
     * @since 11/6/2017
     * @author BMOTTAG
	 */
	public function add_watertruck_inspection($id = 'x')
	{			
			$data['information'] = FALSE;
					
			//si envio el id, entonces busco la informacion 
			if ($id != 'x') {
					$arrParam = array(
						"table" => "inspection_watertruck",
						"order" => "id_inspection_watertruck",
						"column" => "id_inspection_watertruck",
						"id" => $id
					);
					$data['information'] = $this->general_model->get_basic_search($arrParam);//info inspection watertruck
					
					$idVehicle = $data['information'][0]['fk_id_vehicle'];
			}else{
					$idVehicle = $this->session->userdata("idVehicle");
					if (!$idVehicle || empty($idVehicle) || $idVehicle == "x" ) { 
						show_error('ERROR!!! - You are in the wrong place.');	
					}
			}
			
			//busco datos del vehiculo
			$arrParam['idVehicle'] = $idVehicle;
			$data['vehicleInfo'] = $this->general_model->get_vehicle_by($arrParam);

			$data["view"] = $data['vehicleInfo'][0]['form'];
			$this->load->view("layout", $data);
	}	
	
	/**
	 * Save water truck inspection
     * @since 12/6/2017
     * @author BMOTTAG
	 */
	public function save_watertruck_inspection()
	{			
			header('Content-Type: application/json');
			$data = array();
			
			$idWatertruckInspection = $this->input->post('hddId');
			$idVehicle = $this->input->post('hddIdVehicle');

			$msj = "You have save your inspection record, please do not forget to sign!!";
			$flag = true;
			if ($idWatertruckInspection != '') {
				$flag = false;
				$msj = "You have update the Inspection record!!";
			}

			if ($idWatertruckInspection = $this->inspection_model->saveWatertruckInspection()) 
			{
				//actualizo seguimiento en la tabla de vehiculos, para mostrar mensaje
				$this->inspection_model->saveSeguimientoWatertruck();
				
				//update current hours 
				$this->inspection_model->saveCurrentHours();
				
				/**
				 * verifico si hay comentarios y envio correo al administrador
				 */
				if($flag)
				{					
					//busco datos del vehiculo
					$arrParam['idVehicle'] = $idVehicle;
					$vehicleInfo = $this->general_model->get_vehicle_by($arrParam);//busco datos del vehiculo
					
					//el que vaya con comentario le envio correo al administrador
					$comments = $this->input->post('comments');
					if($comments != ""){
						//mensaje del correo
						$emailMsn = "<p>The following vehicle has an inspection with comments, check the full report in the system.</p>";
						$emailMsn .= "<strong>Make: </strong>" . $vehicleInfo[0]["make"];
						$emailMsn .= "<br><strong>Model: </strong>" . $vehicleInfo[0]["model"];
						$emailMsn .= "<br><strong>Unit Number: </strong>" . $vehicleInfo[0]["unit_number"];
						$emailMsn .= "<br><strong>Description: </strong>" . $vehicleInfo[0]["description"];
						$emailMsn .= "<br><strong>Comments: </strong>" . $comments;

						//busco datos del parametricos
						$arrParam = array(
							"table" => "parametric",
							"order" => "id_parametric",
							"id" => "x"
						);
						$subjet = "Inspection with comments";
						$parametric = $this->general_model->get_basic_search($arrParam);						
						$user = $parametric[2]["value"];
						$to = $parametric[0]["value"];
						$company = $parametric[1]["value"];

						$mensaje = "<html>
						<head>
						  <title> $subjet </title>
						</head>
						<body>
							<p>Dear	$user:<br/>
							</p>

							<p>$emailMsn</p>

							<p>Cordially,</p>
							<p><strong>$company</strong></p>
						</body>
						</html>";

						$cabeceras  = 'MIME-Version: 1.0' . "\r\n";
						$cabeceras .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
						$cabeceras .= 'To: ' . $user . '<' . $to . '>' . "\r\n";
						$cabeceras .= 'From: VCI APP <info@v-contracting.ca>' . "\r\n";

						//enviar correo
						mail($to, $subjet, $mensaje, $cabeceras);
					}
					
//si hay un FAIL de los siguientes campos envio correo al ADMINISTRADOR
$headLamps = $this->input->post('headLamps');
$hazardLights = $this->input->post('hazardLights');
$clearanceLights = $this->input->post('clearanceLights');
$tailLights = $this->input->post('tailLights');
$workLights = $this->input->post('workLights');
$turnSignals = $this->input->post('turnSignals');
$beaconLight = $this->input->post('beaconLights');

$lights_check = 1;
if($headLamps == 0 || $hazardLights == 0 || $tailLights == 0 || $workLights == 0 || $turnSignals == 0 || $beaconLight == 0 || $clearanceLights == 0){
	$lights_check = 0;
}
						
$heater_check = $this->input->post('heater');
$brakes_check = $this->input->post('brake');
$steering_wheel_check = $this->input->post('steering_wheel');
$suspension_system_check = $this->input->post('suspension_system');
$tires_check = $this->input->post('tires');
$wipers_check = $this->input->post('wipers');
$air_brake_check = $this->input->post('air_brake');
$driver_seat_check = $this->input->post('door');
$fuel_system_check = $this->input->post('fuel_system');


//preguntar especiales para HYDROVAC para que muestre mensaje si es inseguro sacar el camion
if ($heater_check == 0 || $brakes_check == 0 || $lights_check == 0 || $steering_wheel_check == 0 || $suspension_system_check == 0 || $tires_check == 0 || $wipers_check == 0 || $air_brake_check == 0 || $driver_seat_check == 0 || $fuel_system_check == 0) {

						//mensaje del correo
						$emailMsn = "<p>A major defect has beed identified in the last inspecton, a driver is not legally permitted to operate the vehicle until that defect is prepared.</p>";
						$emailMsn .= "<strong>Make: </strong>" . $vehicleInfo[0]["make"];
						$emailMsn .= "<br><strong>Model: </strong>" . $vehicleInfo[0]["model"];
						$emailMsn .= "<br><strong>Unit Number: </strong>" . $vehicleInfo[0]["unit_number"];
						$emailMsn .= "<br><strong>Description: </strong>" . $vehicleInfo[0]["description"];
						$emailMsn .= "<br>";

						
if ($heater_check == 0) {
	$emailMsn .= "<br>Heater - Fail"; 
}
if ($brakes_check == 0) {
	$emailMsn .= "<br>Brake pedal - Fail"; 
}
if ($lights_check == 0) {
	$emailMsn .= "<br>Lamps and reflectors - Fail"; 
}
if ($steering_wheel_check == 0) {
	$emailMsn .= "<br>Steering wheel - Fail"; 
}
if ($suspension_system_check == 0) {
	$emailMsn .= "<br>Suspension system - Fail"; 
}
if ($tires_check == 0) {
	$emailMsn .= "<br>Tires/Lug Nuts/Pressure - Fail"; 
}
if ($wipers_check == 0) {
	$emailMsn .= "<br>Wipers/Washers - Fail"; 
}
if ($air_brake_check == 0) {
	$emailMsn .= "<br>Air brake system - Fail"; 
}
if ($driver_seat_check == 0) {
	$emailMsn .= "<br>Driver and Passenger door - Fail"; 
}
if ($fuel_system_check == 0) {
	$emailMsn .= "<br>Fuel system - Fail"; 
}
						
						
						//busco datos del parametricos
						$arrParam = array(
							"table" => "parametric",
							"order" => "id_parametric",
							"id" => "x"
						);
						$subjet = "Inspection with major defect";
						$parametric = $this->general_model->get_basic_search($arrParam);						
						$user = $parametric[2]["value"];
						$to = $parametric[0]["value"];
						$company = $parametric[1]["value"];

						$mensaje = "<html>
						<head>
						  <title> $subjet </title>
						</head>
						<body>
							<p>Dear	$user:<br/>
							</p>

							<p>$emailMsn</p>

							<p>Cordially,</p>
							<p><strong>$company</strong></p>
						</body>
						</html>";

						$cabeceras  = 'MIME-Version: 1.0' . "\r\n";
						$cabeceras .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
						$cabeceras .= 'To: ' . $user . '<' . $to . '>' . "\r\n";
						$cabeceras .= 'From: VCI APP <info@v-contracting.ca>' . "\r\n";

						//enviar correo
						mail($to, $subjet, $mensaje, $cabeceras);
					
}
										
					//verificar el kilometraje
					//si se paso del cambio de aceite o esta cerca entonces enviar correo al administrador
					$hours = $this->input->post('hours');
					$oilChange = $this->input->post('oilChange');
					$diferencia = $oilChange - $hours;
										
					if($diferencia <= 50){
						//enviar correo
						
						//mensaje del correo
						$emailMsn = "<p>The following vehicle should change the oil as soon as possible.</p>";
						$emailMsn .= "<strong>Make: </strong>" . $vehicleInfo[0]["make"];
						$emailMsn .= "<br><strong>Model: </strong>" . $vehicleInfo[0]["model"];
						$emailMsn .= "<br><strong>Unit Number: </strong>" . $vehicleInfo[0]["unit_number"];
						$emailMsn .= "<br><strong>Description: </strong>" . $vehicleInfo[0]["description"];
						$emailMsn .= "<br><strong>Current Hours/Kilometers: </strong>" . number_format($hours);
						$emailMsn .= "<br><strong>Next Oil Change: </strong>" . number_format($oilChange);
						$emailMsn .= "<p>If you change the Oil, do not forget to update the next Oil Change in the system.</p>";

						//busco datos del parametricos
						$arrParam = array(
							"table" => "parametric",
							"order" => "id_parametric",
							"id" => "x"
						);
						$subjet = "Oil Change";
						$parametric = $this->general_model->get_basic_search($arrParam);						
						$user = $parametric[2]["value"];
						$to = $parametric[0]["value"];
						$company = $parametric[1]["value"];

						$mensaje = "<html>
						<head>
						  <title> $subjet </title>
						</head>
						<body>
							<p>Dear	$user:<br/>
							</p>

							<p>$emailMsn</p>

							<p>Cordially,</p>
							<p><strong>$company</strong></p>
						</body>
						</html>";

						$cabeceras  = 'MIME-Version: 1.0' . "\r\n";
						$cabeceras .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
						$cabeceras .= 'To: ' . $user . '<' . $to . '>' . "\r\n";
						$cabeceras .= 'From: VCI APP <info@v-contracting.ca>' . "\r\n";

						//enviar correo
						mail($to, $subjet, $mensaje, $cabeceras);
					} 
					
				}
				
				$data["result"] = true;
				$data["idWatertruckInspection"] = $idWatertruckInspection;
				$this->session->set_flashdata('retornoExito', $msj);
			} else {
				
				$data["result"] = "error";
				$data["mensaje"] = "Error!!! Ask for help.";
				$data["idWatertruckInspection"] = "";
				$this->session->set_flashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
			}

			echo json_encode($data);
    }
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
}