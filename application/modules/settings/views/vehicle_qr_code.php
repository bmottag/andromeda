<div id="page-wrapper">
	<br>
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h4 class="list-group-item-heading">
					<i class="fa fa-gear fa-fw"></i> SETTINGS - VEHICLE QR CODE
					</h4>
				</div>
			</div>
		</div>
		<!-- /.col-lg-12 -->				
	</div>
	
	<!-- /.row -->
	<div class="row">
		<div class="col-lg-8">
			<div class="panel panel-default">
				<div class="panel-heading">
					<a class="btn btn-success btn-xs" href=" <?php echo base_url().'settings/vehicle/'. $vehicleInfo[0]["type_level_1"] . '/' . $vehicleInfo[0]["inspection_type"]; ?> "><span class="glyphicon glyphicon glyphicon-chevron-left" aria-hidden="true"></span> Go back </a> 
					<i class="fa fa-automobile"></i> VEHICLE QR CODE
				</div>
				<div class="panel-body">
						
					<?php if($vehicleInfo[0]["qr_code"]){ ?>
						<div class="form-group">
							<div class="row" align="center">
								<img src="<?php echo base_url($vehicleInfo[0]["qr_code"]); ?>" class="img-rounded" alt="QR CODE" />
							</div>
						</div>
					<?php } ?>
					
					<div class="alert alert-danger">
						<?php echo base_url("login/index/" . $vehicleInfo[0]["encryption"] );?>
					</div>
					
				</div>
				<!-- /.panel-body -->
			</div>
			<!-- /.panel -->
		</div>
		<!-- /.col-lg-8 -->
		
		<div class="col-lg-4">
			<div class="panel panel-info">
				<div class="panel-heading">
					<i class="fa fa-automobile"></i> <strong>VEHICLE INFORMATION</strong>
				</div>
				<div class="panel-body">
				
					<?php if($vehicleInfo[0]["photo"]){ ?>
						<div class="form-group">
							<div class="row" align="center">
								<img src="<?php echo base_url($vehicleInfo[0]["photo"]); ?>" class="img-rounded" alt="Vehicle Photo" />
							</div>
						</div>
					<?php } ?>
				
					<strong>Make: </strong><?php echo $vehicleInfo[0]['make']; ?><br>
					<strong>Model: </strong><?php echo $vehicleInfo[0]['model']; ?><br>
					<strong>Description: </strong><?php echo $vehicleInfo[0]['description']; ?><br>
					<strong>Unit Number: </strong><?php echo $vehicleInfo[0]['unit_number']; ?><br>
					<strong>Type: </strong><br>
					<?php
						switch ($vehicleInfo[0]['type_level_1']) {
							case 1:
								$type = 'Fleet';
								break;
							case 2:
								$type = 'Rental';
								break;
							case 99:
								$type = 'Other';
								break;
						}
						echo $type . " - " . $vehicleInfo[0]['type_2'];
					?><br>
					
					<?php
					$tipo = $vehicleInfo[0]['type_level_2'];
					
					echo "<p class='text-danger'>";
					//si es sweeper
					if($tipo == 15){
						echo "<strong>Truck engine current hours:</strong><br>";
						echo "Current: " . number_format($vehicleInfo[0]["hours"]);
						echo "<br>Next oil change: " . number_format($vehicleInfo[0]["oil_change"]);
						
						echo "<br><strong>Sweeper engine current hours:</strong><br>";
						echo "Current: " . number_format($vehicleInfo[0]["hours_2"]);
						echo "<br>Next oil change: " . number_format($vehicleInfo[0]["oil_change_2"]);
					//si es hydrovac
					}elseif($tipo == 16){
						echo "<strong>Engine hours:</strong><br>";
						echo "Current: " . number_format($vehicleInfo[0]["hours"]);
						echo "<br>Next oil change: " . number_format($vehicleInfo[0]["oil_change"]);

						echo "<br><strong>Hydraulic pump hours:</strong><br>";
						echo "Current: " . number_format($vehicleInfo[0]["hours_2"]);
						echo "<br>Next oil change: " . number_format($vehicleInfo[0]["oil_change_2"]);
						
						echo "<br><strong>Blower hours:</strong><br>";
						echo "Current: " . number_format($vehicleInfo[0]["hours_3"]);
						echo "<br>Next oil change: " . number_format($vehicleInfo[0]["oil_change_3"]);
					}else{
						echo "<strong>Current Hours/Kilometers: </strong><br>";
						echo "Current: " . number_format($vehicleInfo[0]["hours"]);
						echo "<br>Next oil change: " . number_format($vehicleInfo[0]["oil_change"]);
					}
					echo "</p>";
					
					?>

					
					
				</div>
			</div>
		</div>		
	</div>
	<!-- /.row -->
</div>
<!-- /#page-wrapper -->