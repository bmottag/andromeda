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
	
	/**
	 * Generate Inspection Daily Report in PDF
	 * @param int $idEmployee
	 * @param date $from
	 * @param date $to
	 * @param date $idInspection
     * @since 8/01/2017
	 * @since 31/01/2018
	 * @review 28/01/2017
     * @author BMOTTAG
	 */
	public function generaInsectionDailyPDF($idEmployee, $idVehicle, $idTrailer, $from, $to, $idInspection='x')
	{
			$this->load->library('Pdf');
			
			// create new PDF document
			$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

			// set document information
			$pdf->SetCreator(PDF_CREATOR);
			$pdf->SetAuthor('VCI');
			$pdf->SetTitle('Inspection Report');
			$pdf->SetSubject('TCPDF Tutorial');

			// set default header data
			$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, '', PDF_HEADER_STRING, array(0,64,255), array(0,64,128));

			// set header and footer fonts
			$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
			$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

			// set default monospaced font
			$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
			
			$pdf->setPrintFooter(false); //no imprime el pie ni la linea 

			// set margins
			$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
			$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
			$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

			// set auto page breaks
			$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

			// set image scale factor
			$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

			// set some language-dependent strings (optional)
			if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
				require_once(dirname(__FILE__).'/lang/eng.php');
				$pdf->setLanguageArray($l);
			}

			// ---------------------------------------------------------

			// set font
			$pdf->SetFont('dejavusans', '', 7);

			// writeHTML($html, $ln=true, $fill=false, $reseth=false, $cell=false, $align='')
			// writeHTMLCell($w, $h, $x, $y, $html='', $border=0, $ln=0, $fill=0, $reseth=true, $align='', $autopadding=true)

			$arrParam = array(
				"employee" => $idEmployee,
				"vehicleId" => $idVehicle,
				"trailerId" => $idTrailer,
				"from" => $from,
				"to" => $to,
				"idInspection" => $idInspection
			);
			$info = $this->report_model->get_daily_inspection($arrParam);
			
			// - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
			// Print a table
			foreach ($info as $lista):
				
				// add a page
				$pdf->AddPage();

				switch ($lista['type_level_1']) {
					case 1:
						$type1 = 'Fleet';
						break;
					case 2:
						$type1 = 'Rental';
						break;
					case 99:
						$type1 = 'Other';
						break;
				}
				

				//bandera para saber el tipo de inpeccion si es TRUCK o PICKUP
				$inspectionType = $lista["inspection_type"];
				$truck = FALSE; //cargo bandera para utilizarla en los campos que son para TRUCK -> inpection type 3
				$title = "PICKUP";
				if($inspectionType == 3){
					$truck = TRUE;
					$title = "TRUCK";
				}
				//FIN BANDERA

				// create some HTML content
				$html = '<h1 align="center" style="color:#337ab7;">' . $title . ' INSPECTION REPORT</h1>
							<style>
							table {
								font-family: arial, sans-serif;
								border-collapse: collapse;
								width: 100%;
							}

							td, th {
								border: 1px solid #dddddd;
								text-align: left;
								padding: 8px;
							}
							</style>
						<table border="1" cellspacing="0" cellpadding="5">
							<tr bgcolor="#337ab7" style="color:white;">
								<th><strong>Type: </strong><br>' . $type1 . ' - ' . $lista['type_2'] . '</th>
								<th><strong>Make: </strong><br>' . $lista['make'] . '</th>
								<th><strong>Model: </strong><br>' . $lista['model'] . '</th>
								<th><strong>Unit Number: </strong><br>' . $lista['unit_number'] . '</th>
								<th><strong>Date & Time: </strong><br>' . $lista['date_issue'] . '</th>
							</tr>
						</table>
						<br><br>';
						
				$html.= '<table border="1" cellspacing="0" cellpadding="5">
							<tr bgcolor="#337ab7" style="color:white;">
								<th align="center" width="30%"><strong>Items to Check</strong></th>
								<th align="center" width="10%"><strong>Pass</strong></th>
								<th align="center" width="10%"><strong>Fail</strong></th>
								<th align="center" width="30%"><strong>Items to Check</strong></th>
								<th align="center" width="10%"><strong>Pass</strong></th>
								<th align="center" width="10%"><strong>Fail</strong></th>
							</tr>';

						$html.='<tr bgcolor="#337ab7" style="color:white;">
								<th align="center" colspan="3"><strong>ENGINE</strong></th>
								<th align="center" colspan="3"><strong>LIGHTS</strong></th>
								</tr>';

						$html.='<tr>
								<th align="center"><strong>Belts/Hoses</strong></th>';
							if($lista["belt"] == 1){
								$html.= '<th align="center"><strong>Y</strong></th>
										 <th align="center"><strong></strong></th>';
							}elseif($lista["belt"] == 0){
								$html.='<th align="center"><strong></strong></th>
										 <th align="center"><strong>X</strong></th>';					
							}else{
								$html.='<th align="center"><strong>N/A</strong></th>
										 <th align="center"><strong>N/A</strong></th>';													
							}
							
						$html.='<th align="center"><strong>Head Lamps</strong></th>';
							if($lista["head_lamps"] == 1){
								$html.= '<th align="center"><strong>Y</strong></th>
										 <th align="center"><strong></strong></th>';
							}elseif($lista["head_lamps"] == 0){
								$html.='<th align="center"><strong></strong></th>
										 <th align="center"><strong>X</strong></th>';					
							}else{
								$html.='<th align="center"><strong>N/A</strong></th>
										 <th align="center"><strong>N/A</strong></th>';													
							}
					
						$html.='</tr>';
					
						$html.='<tr>
								<th align="center"><strong>Power Steering Fluid</strong></th>';
							if($lista["power_steering"] == 1){
								$html.= '<th align="center"><strong>Y</strong></th>
										 <th align="center"><strong></strong></th>';
							}elseif($lista["power_steering"] == 0){
								$html.='<th align="center"><strong></strong></th>
										 <th align="center"><strong>X</strong></th>';					
							}else{
								$html.='<th align="center"><strong>N/A</strong></th>
										 <th align="center"><strong>N/A</strong></th>';													
							}
					
						$html.='<th align="center"><strong>Hazard Lights</strong></th>';
							if($lista["hazard_lights"] == 1){
								$html.= '<th align="center"><strong>Y</strong></th>
										 <th align="center"><strong></strong></th>';
							}elseif($lista["hazard_lights"] == 0){
								$html.='<th align="center"><strong></strong></th>
										 <th align="center"><strong>X</strong></th>';					
							}else{
								$html.='<th align="center"><strong>N/A</strong></th>
										 <th align="center"><strong>N/A</strong></th>';													
							}
						$html.='</tr>';
						
						$html.='<tr>
								<th align="center"><strong>Oil Level</strong></th>';
							if($lista["oil_level"] == 1){
								$html.= '<th align="center"><strong>Y</strong></th>
										 <th align="center"><strong></strong></th>';
							}elseif($lista["oil_level"] == 0){
								$html.='<th align="center"><strong></strong></th>
										 <th align="center"><strong>X</strong></th>';					
							}else{
								$html.='<th align="center"><strong>N/A</strong></th>
										 <th align="center"><strong>N/A</strong></th>';													
							}
							
						$html.='<th align="center"><strong>Tail Lights</strong></th>';
							if($lista["bake_lights"] == 1){
								$html.= '<th align="center"><strong>Y</strong></th>
										 <th align="center"><strong></strong></th>';
							}elseif($lista["bake_lights"] == 0){
								$html.='<th align="center"><strong></strong></th>
										 <th align="center"><strong>X</strong></th>';					
							}else{
								$html.='<th align="center"><strong>N/A</strong></th>
										 <th align="center"><strong>N/A</strong></th>';													
							}
					
						$html.='</tr>';
						
						$html.='<tr>
								<th align="center"><strong>Coolant Level</strong></th>';
							if($lista["coolant_level"] == 1){
								$html.= '<th align="center"><strong>Y</strong></th>
										 <th align="center"><strong></strong></th>';
							}elseif($lista["coolant_level"] == 0){
								$html.='<th align="center"><strong></strong></th>
										 <th align="center"><strong>X</strong></th>';					
							}else{
								$html.='<th align="center"><strong>N/A</strong></th>
										 <th align="center"><strong>N/A</strong></th>';													
							}
							
						$html.='<th align="center"><strong>Work Lights</strong></th>';
							if($lista["work_lights"] == 1){
								$html.= '<th align="center"><strong>Y</strong></th>
										 <th align="center"><strong></strong></th>';
							}elseif($lista["work_lights"] == 0){
								$html.='<th align="center"><strong></strong></th>
										 <th align="center"><strong>X</strong></th>';					
							}else{
								$html.='<th align="center"><strong>N/A</strong></th>
										 <th align="center"><strong>N/A</strong></th>';													
							}
					
						$html.='</tr>';
						
						$html.='<tr>
								<th align="center"><strong>Coolant/Oil Leaks</strong></th>';
							if($lista["water_leaks"] == 1){
								$html.= '<th align="center"><strong>Y</strong></th>
										 <th align="center"><strong></strong></th>';
							}elseif($lista["water_leaks"] == 0){
								$html.='<th align="center"><strong></strong></th>
										 <th align="center"><strong>X</strong></th>';					
							}else{
								$html.='<th align="center"><strong>N/A</strong></th>
										 <th align="center"><strong>N/A</strong></th>';													
							}
							
						$html.='<th align="center"><strong>Turn signals lights</strong></th>';
							if($lista["turn_signals"] == 1){
								$html.= '<th align="center"><strong>Y</strong></th>
										 <th align="center"><strong></strong></th>';
							}elseif($lista["turn_signals"] == 0){
								$html.='<th align="center"><strong></strong></th>
										 <th align="center"><strong>X</strong></th>';					
							}else{
								$html.='<th align="center"><strong>N/A</strong></th>
										 <th align="center"><strong>N/A</strong></th>';													
							}
							
						$html.='</tr>';

$change = '';
if($truck){
	$change = 'rowspan="2"';
}
						$html.='<tr>
								<th colspan="3" ' . $change . '></th>';

						$html.='<th align="center"><strong>Beacon Light:</strong></th>';
							if($lista["beacon_light"] == 1){
								$html.= '<th align="center"><strong>Y</strong></th>
										 <th align="center"><strong></strong></th>';
							}elseif($lista["beacon_light"] == 0){
								$html.='<th align="center"><strong></strong></th>
										 <th align="center"><strong>X</strong></th>';					
							}else{
								$html.='<th align="center"><strong>N/A</strong></th>
										 <th align="center"><strong>N/A</strong></th>';													
							}
							
						$html.='</tr>';
					
if($truck){
						$html.='<tr>';
	
						$html.='<th align="center"><strong>Clearance Lights</strong></th>';
							if($lista["clearance_lights"] == 1){
								$html.= '<th align="center"><strong>Y</strong></th>
										 <th align="center"><strong></strong></th>';
							}elseif($lista["clearance_lights"] == 0){
								$html.='<th align="center"><strong></strong></th>
										 <th align="center"><strong>X</strong></th>';					
							}else{
								$html.='<th align="center"><strong>N/A</strong></th>
										 <th align="center"><strong>N/A</strong></th>';													
							}
							
						$html.='</tr>';
}					
						
						$html.='<tr bgcolor="#337ab7" style="color:white;">
								<th align="center" colspan="3"><strong>SERVICE</strong></th>
								<th align="center" colspan="3"><strong>EXTERIOR</strong></th>
								</tr>';

						$html.='<tr>
								<th align="center"><strong>Brake pedal</strong></th>';
							if($lista["brake_pedal"] == 1){
								$html.= '<th align="center"><strong>Y</strong></th>
										 <th align="center"><strong></strong></th>';
							}elseif($lista["brake_pedal"] == 0){
								$html.='<th align="center"><strong></strong></th>
										 <th align="center"><strong>X</strong></th>';					
							}else{
								$html.='<th align="center"><strong>N/A</strong></th>
										 <th align="center"><strong>N/A</strong></th>';													
							}
							
						$html.='<th align="center"><strong>Tires/Lug Nuts/Pressure</strong></th>';
							if($lista["nuts"] == 1){
								$html.= '<th align="center"><strong>Y</strong></th>
										 <th align="center"><strong></strong></th>';
							}elseif($lista["nuts"] == 0){
								$html.='<th align="center"><strong></strong></th>
										 <th align="center"><strong>X</strong></th>';					
							}else{
								$html.='<th align="center"><strong>N/A</strong></th>
										 <th align="center"><strong>N/A</strong></th>';													
							}
					
						$html.='</tr>';
					
						$html.='<tr>
								<th align="center"><strong>Emergency brake</strong></th>';
							if($lista["emergency_brake"] == 1){
								$html.= '<th align="center"><strong>Y</strong></th>
										 <th align="center"><strong></strong></th>';
							}elseif($lista["emergency_brake"] == 0){
								$html.='<th align="center"><strong></strong></th>
										 <th align="center"><strong>X</strong></th>';					
							}else{
								$html.='<th align="center"><strong>N/A</strong></th>
										 <th align="center"><strong>N/A</strong></th>';													
							}
					
						$html.='<th align="center"><strong>Glass (All) & Mirror</strong></th>';
							if($lista["glass"] == 1){
								$html.= '<th align="center"><strong>Y</strong></th>
										 <th align="center"><strong></strong></th>';
							}elseif($lista["glass"] == 0){
								$html.='<th align="center"><strong></strong></th>
										 <th align="center"><strong>X</strong></th>';					
							}else{
								$html.='<th align="center"><strong>N/A</strong></th>
										 <th align="center"><strong>N/A</strong></th>';													
							}
						$html.='</tr>';
						
						$html.='<tr>
								<th align="center"><strong>Gauges: Volt/Fuel/Temp/Oil</strong></th>';
							if($lista["gauges"] == 1){
								$html.= '<th align="center"><strong>Y</strong></th>
										 <th align="center"><strong></strong></th>';
							}elseif($lista["gauges"] == 0){
								$html.='<th align="center"><strong></strong></th>
										 <th align="center"><strong>X</strong></th>';					
							}else{
								$html.='<th align="center"><strong>N/A</strong></th>
										 <th align="center"><strong>N/A</strong></th>';													
							}
							
						$html.='<th align="center"><strong>Clean exterior</strong></th>';
							if($lista["clean_exterior"] == 1){
								$html.= '<th align="center"><strong>Y</strong></th>
										 <th align="center"><strong></strong></th>';
							}elseif($lista["clean_exterior"] == 0){
								$html.='<th align="center"><strong></strong></th>
										 <th align="center"><strong>X</strong></th>';					
							}else{
								$html.='<th align="center"><strong>N/A</strong></th>
										 <th align="center"><strong>N/A</strong></th>';													
							}
					
						$html.='</tr>';
						
						$html.='<tr>
								<th align="center"><strong>Electrical & Air Horn</strong></th>';
							if($lista["horn"] == 1){
								$html.= '<th align="center"><strong>Y</strong></th>
										 <th align="center"><strong></strong></th>';
							}elseif($lista["horn"] == 0){
								$html.='<th align="center"><strong></strong></th>
										 <th align="center"><strong>X</strong></th>';					
							}else{
								$html.='<th align="center"><strong>N/A</strong></th>
										 <th align="center"><strong>N/A</strong></th>';													
							}

						$html.='<th align="center"><strong>Wipers/Washers</strong></th>';
							if($lista["wipers"] == 1){
								$html.= '<th align="center"><strong>Y</strong></th>
										 <th align="center"><strong></strong></th>';
							}elseif($lista["wipers"] == 0){
								$html.='<th align="center"><strong></strong></th>
										 <th align="center"><strong>X</strong></th>';					
							}else{
								$html.='<th align="center"><strong>N/A</strong></th>
										 <th align="center"><strong>N/A</strong></th>';													
							}
							
						$html.='</tr>';
						
						$html.='<tr>
								<th align="center"><strong>Seatbelts </strong></th>';
							if($lista["seatbelts"] == 1){
								$html.= '<th align="center"><strong>Y</strong></th>
										 <th align="center"><strong></strong></th>';
							}elseif($lista["seatbelts"] == 0){
								$html.='<th align="center"><strong></strong></th>
										 <th align="center"><strong>X</strong></th>';					
							}else{
								$html.='<th align="center"><strong>N/A</strong></th>
										 <th align="center"><strong>N/A</strong></th>';													
							}
							
						$html.='<th align="center"><strong>Backup Beeper </strong></th>';
							if($lista["backup_beeper"] == 1){
								$html.= '<th align="center"><strong>Y</strong></th>
										 <th align="center"><strong></strong></th>';
							}elseif($lista["backup_beeper"] == 0){
								$html.='<th align="center"><strong></strong></th>
										 <th align="center"><strong>X</strong></th>';					
							}else{
								$html.='<th align="center"><strong>N/A</strong></th>
										 <th align="center"><strong>N/A</strong></th>';													
							}
					
						$html.='</tr>';
						
						$html.='<tr>
								<th align="center"><strong>Driver & Passenger seat</strong></th>';
							if($lista["driver_seat"] == 1){
								$html.= '<th align="center"><strong>Y</strong></th>
										 <th align="center"><strong></strong></th>';
							}elseif($lista["driver_seat"] == 0){
								$html.='<th align="center"><strong></strong></th>
										 <th align="center"><strong>X</strong></th>';					
							}else{
								$html.='<th align="center"><strong>N/A</strong></th>
										 <th align="center"><strong>N/A</strong></th>';													
							}
							
						$html.='<th align="center"><strong>Driver and Passenger door</strong></th>';
							if($lista["passenger_door"] == 1){
								$html.= '<th align="center"><strong>Y</strong></th>
										 <th align="center"><strong></strong></th>';
							}elseif($lista["passenger_door"] == 0){
								$html.='<th align="center"><strong></strong></th>
										 <th align="center"><strong>X</strong></th>';					
							}else{
								$html.='<th align="center"><strong>N/A</strong></th>
										 <th align="center"><strong>N/A</strong></th>';													
							}
												
						$html.='</tr>';
						
						$html.='<tr>
								<th align="center"><strong>Insurance information</strong></th>';
							if($lista["insurance"] == 1){
								$html.= '<th align="center"><strong>Y</strong></th>
										 <th align="center"><strong></strong></th>';
							}elseif($lista["insurance"] == 0){
								$html.='<th align="center"><strong></strong></th>
										 <th align="center"><strong>X</strong></th>';					
							}else{
								$html.='<th align="center"><strong>N/A</strong></th>
										 <th align="center"><strong>N/A</strong></th>';													
							}

						$html.='<th align="center"><strong>Decals</strong></th>';
							if($lista["proper_decals"] == 1){
								$html.= '<th align="center"><strong>Y</strong></th>
										 <th align="center"><strong></strong></th>';
							}elseif($lista["proper_decals"] == 0){
								$html.='<th align="center"><strong></strong></th>
										 <th align="center"><strong>X</strong></th>';					
							}else{
								$html.='<th align="center"><strong>N/A</strong></th>
										 <th align="center"><strong>N/A</strong></th>';													
							}

						$html.='</tr>';
						
						$html.='<tr>
								<th align="center"><strong>Registration</strong></th>';
							if($lista["registration"] == 1){
								$html.= '<th align="center"><strong>Y</strong></th>
										 <th align="center"><strong></strong></th>';
							}elseif($lista["registration"] == 0){
								$html.='<th align="center"><strong></strong></th>
										 <th align="center"><strong>X</strong></th>';					
							}else{
								$html.='<th align="center"><strong>N/A</strong></th>
										 <th align="center"><strong>N/A</strong></th>';													
							}
if($truck){
						$html.='<th align="center" colspan="3" rowspan="2"><strong></strong></th>';
}
						$html.='</tr>';
						
						$html.='<tr>
								<th align="center"><strong>Clean interior</strong></th>';
							if($lista["clean_interior"] == 1){
								$html.= '<th align="center"><strong>Y</strong></th>
										 <th align="center"><strong></strong></th>';
							}elseif($lista["clean_interior"] == 0){
								$html.='<th align="center"><strong></strong></th>
										 <th align="center"><strong>X</strong></th>';					
							}else{
								$html.='<th align="center"><strong>N/A</strong></th>
										 <th align="center"><strong>N/A</strong></th>';													
							}
					
						$html.='</tr>';
						
						$html.='<tr bgcolor="#337ab7" style="color:white;">
								<th align="center" colspan="3"><strong>SAFETY</strong></th>';

$grease = "";
if($truck){
	$grease = "GREASING";
}
						$html.='<th align="center" colspan="3">' . $grease . '<strong></strong></th>
								</tr>';

						$html.='<tr>
								<th align="center"><strong>Fire extinguisher</strong></th>';
							if($lista["fire_extinguisher"] == 1){
								$html.= '<th align="center"><strong>Y</strong></th>
										 <th align="center"><strong></strong></th>';
							}elseif($lista["fire_extinguisher"] == 0){
								$html.='<th align="center"><strong></strong></th>
										 <th align="center"><strong>X</strong></th>';					
							}else{
								$html.='<th align="center"><strong>N/A</strong></th>
										 <th align="center"><strong>N/A</strong></th>';													
							}
if($truck){
						$html.='<th align="center"><strong>Steering Axle</strong></th>';
							if($lista["steering_axle"] == 1){
								$html.= '<th align="center"><strong>Y</strong></th>
										 <th align="center"><strong></strong></th>';
							}elseif($lista["steering_axle"] == 0){
								$html.='<th align="center"><strong></strong></th>
										 <th align="center"><strong>X</strong></th>';					
							}else{
								$html.='<th align="center"><strong>N/A</strong></th>
										 <th align="center"><strong>N/A</strong></th>';													
							}

}else{
						$html.='<th colspan="3" rowspan="4"></th>';
}					
						$html.='</tr>';
						
						$html.='<tr>
								<th align="center"><strong>First Aid</strong></th>';
							if($lista["first_aid"] == 1){
								$html.= '<th align="center"><strong>Y</strong></th>
										 <th align="center"><strong></strong></th>';
							}elseif($lista["first_aid"] == 0){
								$html.='<th align="center"><strong></strong></th>
										 <th align="center"><strong>X</strong></th>';					
							}else{
								$html.='<th align="center"><strong>N/A</strong></th>
										 <th align="center"><strong>N/A</strong></th>';													
							}
if($truck){
						$html.='<th align="center"><strong>Drives Axles</strong></th>';
							if($lista["drives_axle"] == 1){
								$html.= '<th align="center"><strong>Y</strong></th>
										 <th align="center"><strong></strong></th>';
							}elseif($lista["drives_axle"] == 0){
								$html.='<th align="center"><strong></strong></th>
										 <th align="center"><strong>X</strong></th>';					
							}else{
								$html.='<th align="center"><strong>N/A</strong></th>
										 <th align="center"><strong>N/A</strong></th>';													
							}	

}
						$html.='</tr>';
						
						$html.='<tr>
								<th align="center"><strong>Emergency Kit</strong></th>';
							if($lista["emergency_reflectors"] == 1){
								$html.= '<th align="center"><strong>Y</strong></th>
										 <th align="center"><strong></strong></th>';
							}elseif($lista["emergency_reflectors"] == 0){
								$html.='<th align="center"><strong></strong></th>
										 <th align="center"><strong>X</strong></th>';					
							}else{
								$html.='<th align="center"><strong>N/A</strong></th>
										 <th align="center"><strong>N/A</strong></th>';													
							}
if($truck){							
						$html.='<th align="center"><strong>Front drive shaft</strong></th>';
							if($lista["grease_front"] == 1){
								$html.= '<th align="center"><strong>Y</strong></th>
										 <th align="center"><strong></strong></th>';
							}elseif($lista["grease_front"] == 0){
								$html.='<th align="center"><strong></strong></th>
										 <th align="center"><strong>X</strong></th>';					
							}else{
								$html.='<th align="center"><strong>N/A</strong></th>
										 <th align="center"><strong>N/A</strong></th>';													
							}

}
						$html.='</tr>';
						
						$html.='<tr>
								<th align="center"><strong>Spill Kit</strong></th>';
							if($lista["spill_kit"] == 1){
								$html.= '<th align="center"><strong>Y</strong></th>
										 <th align="center"><strong></strong></th>';
							}elseif($lista["spill_kit"] == 0){
								$html.='<th align="center"><strong></strong></th>
										 <th align="center"><strong>X</strong></th>';					
							}else{
								$html.='<th align="center"><strong>N/A</strong></th>
										 <th align="center"><strong>N/A</strong></th>';													
							}
if($truck){
						$html.='<th align="center"><strong>Back drive shaft</strong></th>';
							if($lista["grease_end"] == 1){
								$html.= '<th align="center"><strong>Y</strong></th>
										 <th align="center"><strong></strong></th>';
							}elseif($lista["grease_end"] == 0){
								$html.='<th align="center"><strong></strong></th>
										 <th align="center"><strong>X</strong></th>';					
							}else{
								$html.='<th align="center"><strong>N/A</strong></th>
										 <th align="center"><strong>N/A</strong></th>';													
							}	
}
						$html.='</tr>';

if($truck){
						$html.='<tr>';
						$html.='<th colspan="3" rowspan="2"></th>';
						
						$html.='<th align="center"><strong>Grease 5th wheel</strong></th>';
							if($lista["grease"] == 1){
								$html.= '<th align="center"><strong>Y</strong></th>
										 <th align="center"><strong></strong></th>';
							}elseif($lista["grease"] == 0){
								$html.='<th align="center"><strong></strong></th>
										 <th align="center"><strong>X</strong></th>';					
							}else{
								$html.='<th align="center"><strong>N/A</strong></th>
										 <th align="center"><strong>N/A</strong></th>';													
							}


						$html.='</tr>';
						
						$html.='<tr>';
						
						$html.='<th align="center"><strong>Box hoist & hinge</strong></th>';
							if($lista["hoist"] == 1){
								$html.= '<th align="center"><strong>Y</strong></th>
										 <th align="center"><strong></strong></th>';
							}elseif($lista["hoist"] == 0){
								$html.='<th align="center"><strong></strong></th>
										 <th align="center"><strong>X</strong></th>';					
							}else{
								$html.='<th align="center"><strong>N/A</strong></th>
										 <th align="center"><strong>N/A</strong></th>';													
							}

						$html.='</tr>';
}
						
						$html.='<tr>';
						$html.= '<th colspan="6"><strong>Comments : </strong>' . $lista["comments"] . '</th>';
						$html.='</tr>';
						
						
if($lista["with_trailer"] == 1){
					$html.='<tr bgcolor="#337ab7" style="color:white;">
								<th colspan="6"><strong>TRAILER :  ' . $lista["trailer"] . '</strong></th>
							 </tr>';
						
						$html.='<tr>
								<th align="center"><strong>Lights</strong></th>';
							if($lista["trailer_lights"] == 1){
								$html.= '<th align="center"><strong>Y</strong></th>
										 <th align="center"><strong></strong></th>';
							}elseif($lista["trailer_lights"] == 2){
								$html.='<th align="center"><strong></strong></th>
										 <th align="center"><strong>X</strong></th>';					
							}else{
								$html.='<th align="center"><strong>N/A</strong></th>
										 <th align="center"><strong>N/A</strong></th>';													
							}
					
						$html.='<th align="center"><strong>Tires</strong></th>';
							if($lista["trailer_tires"] == 1){
								$html.= '<th align="center"><strong>Y</strong></th>
										 <th align="center"><strong></strong></th>';
							}elseif($lista["trailer_tires"] == 2){
								$html.='<th align="center"><strong></strong></th>
										 <th align="center"><strong>X</strong></th>';					
							}else{
								$html.='<th align="center"><strong>N/A</strong></th>
										 <th align="center"><strong>N/A</strong></th>';													
							}
						$html.='</tr>';
						
						$html.='<tr>
								<th align="center"><strong>Clean</strong></th>';
							if($lista["trailer_clean"] == 1){
								$html.= '<th align="center"><strong>Y</strong></th>
										 <th align="center"><strong></strong></th>';
							}elseif($lista["trailer_clean"] == 2){
								$html.='<th align="center"><strong></strong></th>
										 <th align="center"><strong>X</strong></th>';					
							}else{
								$html.='<th align="center"><strong>N/A</strong></th>
										 <th align="center"><strong>N/A</strong></th>';													
							}
					
						$html.='<th align="center"><strong>Slings</strong></th>';
						$html.= '<th colspan="2" align="center"><strong>' . $lista["trailer_slings"] . '</strong></th>';
						$html.='</tr>';
						
						$html.='<tr>';
						$html.='<th align="center"><strong>Chains</strong></th>';
						$html.= '<th colspan="2" align="center"><strong>' . $lista["trailer_chains"] . '</strong></th>';
					
						$html.='<th align="center"><strong>Ratchet</strong></th>';
						$html.= '<th colspan="2" align="center"><strong>' . $lista["trailer_ratchet"] . '</strong></th>';
						$html.='</tr>';
							
						$html.='<tr>';
						$html.= '<th colspan="6"><strong>Comments : </strong>' . $lista["trailer_comments"] . '</th>';
						$html.='</tr>';
}
							
						$html.='</table>';
				
				$signature = '';
				if($lista['signature']){
					//$urlSignature = base_url($lista['signature']);
					$signature = '<img src="'.$lista['signature'].'" border="0" width="70" height="70" />';
				}
				$html.= '<br><br>';
				$html.= '<table border="1" cellspacing="0" cellpadding="5" width="40%">
						<tr>
							<th align="center" ><strong><p>Driver</p></strong></th>
							<th align="center">' . $signature . '</th>
						</tr>
						<tr bgcolor="#337ab7" style="color:white;">
							<th align="center" ><strong>Name</strong></th>
							<th align="center"><strong>' . $lista['name']. '</strong></th>
						</tr>
						</table>';						
		
				// output the HTML content
				$pdf->writeHTML($html, true, false, true, false, '');
			
			endforeach;
			// Print some HTML Cells


			// reset pointer to the last page
			$pdf->lastPage();

// Clean any content of the output buffer
ob_end_clean();

			//Close and output PDF document
			$pdf->Output('daily_inspection.pdf', 'I');

			//============================================================+
			// END OF FILE
			//============================================================+
		
	}

	/**
	 * Generate Inspection Heavy Report in PDF
	 * @param int $idEmployee
	 * @param date $from
	 * @param date $to
	 * @param date $idInspection
     * @since 9/01/2017
	 * @review 29/01/2017
	 * @review 1/02/2018
     * @author BMOTTAG
	 */
	public function generaInsectionHeavyPDF($idEmployee, $idVehicle, $from, $to, $idInspection='x')
	{
			$this->load->library('Pdf');
			
			// create new PDF document
			$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

			// set document information
			$pdf->SetCreator(PDF_CREATOR);
			$pdf->SetAuthor('VCI');
			$pdf->SetTitle('Inspection Report');
			$pdf->SetSubject('TCPDF Tutorial');

			// set default header data
			$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, '', PDF_HEADER_STRING, array(0,64,255), array(0,64,128));

			// set header and footer fonts
			$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
			$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

			// set default monospaced font
			$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
			
			$pdf->setPrintFooter(false); //no imprime el pie ni la linea 

			// set margins
			$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
			$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
			$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

			// set auto page breaks
			$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

			// set image scale factor
			$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

			// set some language-dependent strings (optional)
			if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
				require_once(dirname(__FILE__).'/lang/eng.php');
				$pdf->setLanguageArray($l);
			}

			// ---------------------------------------------------------

			// set font
			$pdf->SetFont('dejavusans', '', 8);

			// writeHTML($html, $ln=true, $fill=false, $reseth=false, $cell=false, $align='')
			// writeHTMLCell($w, $h, $x, $y, $html='', $border=0, $ln=0, $fill=0, $reseth=true, $align='', $autopadding=true)
			
			$arrParam = array(
				"employee" => $idEmployee,
				"vehicleId" => $idVehicle,
				"from" => $from,
				"to" => $to,
				"idInspection" => $idInspection
			);
			$data['info'] = $this->report_model->get_heavy_inspection($arrParam);
			
			// - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
			// Print a table
			$data['consecutivo'] = 0;
			$html = "";
			foreach ($data['info'] as $lista):
				
				// add a page
				$pdf->AddPage();

				$html = $this->load->view($lista['form'], $data, true);
											
				// output the HTML content
				$pdf->writeHTML($html, true, false, true, false, '');
		
				$data['consecutivo']++;
			endforeach;
			// Print some HTML Cells

			// reset pointer to the last page
			$pdf->lastPage();


// Clean any content of the output buffer
ob_end_clean();

			//Close and output PDF document
			$pdf->Output('heavy_inspection.pdf', 'I');

			//============================================================+
			// END OF FILE
			//============================================================+
		
	}	
	
	/**
	 * Generate Inspection Specail Report in PDF
	 * @param int $idEmployee
	 * @param int $idVehicle
	 * @param date $from
	 * @param date $to
	 * @param date $idInspection
     * @since 23/04/2017
	 * @review 1/02/2018
     * @author BMOTTAG
	 */
	public function generaInsectionSpecialPDF($idEmployee, $idVehicle, $from, $to, $type, $idInspection='x')
	{
			$this->load->library('Pdf');
			
			// create new PDF document
			$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

			// set document information
			$pdf->SetCreator(PDF_CREATOR);
			$pdf->SetAuthor('VCI');
			$pdf->SetTitle('Inspection Report');
			$pdf->SetSubject('TCPDF Tutorial');

			// set default header data
			$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, '', PDF_HEADER_STRING, array(0,64,255), array(0,64,128));

			// set header and footer fonts
			$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
			$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

			// set default monospaced font
			$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
			
			$pdf->setPrintFooter(false); //no imprime el pie ni la linea 

			// set margins
			$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
			$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
			$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

			// set auto page breaks
			$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

			// set image scale factor
			$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

			// set some language-dependent strings (optional)
			if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
				require_once(dirname(__FILE__).'/lang/eng.php');
				$pdf->setLanguageArray($l);
			}

			// ---------------------------------------------------------

			// set font
			$pdf->SetFont('dejavusans', '', 8);

			// writeHTML($html, $ln=true, $fill=false, $reseth=false, $cell=false, $align='')
			// writeHTMLCell($w, $h, $x, $y, $html='', $border=0, $ln=0, $fill=0, $reseth=true, $align='', $autopadding=true)
			
			$arrParam = array(
				"employee" => $idEmployee,
				"vehicleId" => $idVehicle,
				"from" => $from,
				"to" => $to,
				"idInspection" => $idInspection
			);
						
			switch ($type) {
				case 'watertruck':
					$data['info'] = $this->report_model->get_water_truck_inspection($arrParam);
					break;
				case 'hydrovac':
					$data['info'] = $this->report_model->get_hydrovac_inspection($arrParam);
					break;
				case 'sweeper':
					$data['info'] = $this->report_model->get_sweeper_inspection($arrParam);
					break;
				case 'generator':
					$data['info'] = $this->report_model->get_generator_inspection($arrParam);
					break;
			}
			
			// - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
			// Print a table
			$data['consecutivo'] = 0;
			$html = "";
			foreach ($data['info'] as $lista):
				
				// add a page
				$pdf->AddPage();

				$html = $this->load->view($lista['form'], $data, true);
											
				// output the HTML content
				$pdf->writeHTML($html, true, false, true, false, '');
		
				$data['consecutivo']++;
			endforeach;
			// Print some HTML Cells

			// reset pointer to the last page
			$pdf->lastPage();

// Clean any content of the output buffer
ob_end_clean();

			//Close and output PDF document
			$pdf->Output('special_inspection.pdf', 'I');

			//============================================================+
			// END OF FILE
			//============================================================+
		
	}


	
}