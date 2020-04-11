<div id="page-wrapper">
	<br>
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h4 class="list-group-item-heading">
					<i class="fa fa-gear fa-fw"></i> SETTINGS - VEHICLE PHOTO
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
					<a class="btn btn-success btn-xs" href=" <?php echo base_url().'settings/vehicle/' . $vehicleInfo[0]["type_level_1"] . '/' . $vehicleInfo[0]["inspection_type"]; ?> "><span class="glyphicon glyphicon glyphicon-chevron-left" aria-hidden="true"></span> Go back </a> 
					<i class="fa fa-automobile"></i> VEHICLE PHOTO
				</div>
				<div class="panel-body">
					<form  name="form" id="form" class="form-horizontal" method="post" enctype="multipart/form-data" action="<?php echo base_url("settings/do_upload/photo/" . $vehicleInfo[0]["type_level_1"]); ?>">
						<input type="hidden" id="hddId" name="hddId" value="<?php echo $idVehicle; ?>"/>

						<div class="alert alert-danger">
							Upload vehicle photo
						</div>

						<div class="form-group">
							<label class="col-sm-4 control-label" for="hddTask">Photo</label>
							<div class="col-sm-5">
								 <input type="file" name="userfile" />
							</div>
						</div>
									
						<div class="form-group">
							<div class="row" align="center">
								<div style="width:50%;" align="center">
									<button type="submit" id="btnSubmit" name="btnSubmit" class="btn btn-primary" >
										Submit <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true">
									</button> 
								</div>
							</div>
						</div>
			
						<?php if($error){ ?>
						<div class="alert alert-danger">
							<?php 
								echo "<strong>Error :</strong>";
								pr($error); 
							?><!--$ERROR MUESTRA LOS ERRORES QUE PUEDAN HABER AL SUBIR LA IMAGEN-->
						</div>
						<?php } ?>
						
						<div class="alert alert-danger">
								<strong>Note :</strong><br>
								Allowed format: gif - jpg - png - jpeg<br>
								Maximum size: 3000 KB<br>
								Maximum width: 2024 pixels<br>
								Maximum height: 2008 pixels
						</div>
								
					</form>
				</div>
				<!-- /.panel-body -->
			</div>
			<!-- /.panel -->
		</div>
		<!-- /.col-lg-12 -->
		
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