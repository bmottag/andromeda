<div id="page-wrapper">
	<br>

	<!-- /.row -->
	<div class="row">
		<div class="col-lg-8">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<i class="fa fa-user"></i> USER PROFILE
				</div>
				<div class="panel-body">
					<form  name="form" id="form" class="form-horizontal" method="post" enctype="multipart/form-data" action="<?php echo base_url("employee/do_upload"); ?>">

						<div class="alert alert-info">
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
					<i class="fa fa-user"></i> <strong>USER PROFILE</strong>
				</div>
				<div class="panel-body">
				
					<?php if($UserInfo[0]["photo"]){ ?>
						<div class="form-group">
							<div class="row" align="center">
								<img src="<?php echo base_url($UserInfo[0]["photo"]); ?>" class="img-rounded" alt="User Photo" />
							</div>
						</div>
					<?php } ?>
<?php
$movil = $UserInfo[0]["movil"];
// Separa en grupos de tres 
$count = strlen($movil); 
	
$num_tlf1 = substr($movil, 0, 3); 
$num_tlf2 = substr($movil, 3, 3); 
$num_tlf3 = substr($movil, 6, 2); 
$num_tlf4 = substr($movil, -2); 

if($count == 10){
	$resultado = "$num_tlf1 $num_tlf2 $num_tlf3 $num_tlf4";  
}else{
	
	$resultado = chunk_split($movil,3," "); 
}
?>
					<strong>Name: </strong><?php echo $UserInfo[0]['first_name'] . " " . $UserInfo[0]['last_name']; ?><br>
					<strong>Movil number: </strong><?php echo $resultado; ?><br>
					<strong>Email: </strong><?php echo $UserInfo[0]['email']; ?><br>

				</div>
			</div>
		</div>		
		
	</div>
	<!-- /.row -->
</div>
<!-- /#page-wrapper -->