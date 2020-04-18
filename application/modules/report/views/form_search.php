<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript" src="<?php echo base_url("assets/js/validate/report.js"); ?>"></script>

<div id="page-wrapper">

	<br>
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h4 class="list-group-item-heading">
						<i class="fa fa-bar-chart-o fa-fw"></i> REPORT CENTER
					</h4>
				</div>
			</div>
		</div>
		<!-- /.col-lg-12 -->				
	</div>
	
	<!-- /.row -->
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-<?php echo $estilos; ?>">
				<div class="panel-heading">
					<?php echo $titulo; ?>
				</div>
				<div class="panel-body">
					<div class="alert alert-info">
						<strong>Note:</strong> 
						Select the date range to search your records.
					</div>
					
					<form  name="form" id="form" role="form" method="post" class="form-horizontal" >

						<div class="form-group">
							<div class="col-sm-5 col-sm-offset-1">
								<label for="from">Employee <small>(This field is NOT required.)</small></label>
								<select name="employee" id="employee" class="form-control" >
									<option value=''>Select...</option>
									<?php for ($i = 0; $i < count($workersList); $i++) { ?>
										<option value="<?php echo $workersList[$i]["id_user"]; ?>"><?php echo $workersList[$i]["first_name"] . ' ' . $workersList[$i]["last_name"]; ?></option>	
									<?php } ?>
								</select>
							</div>
						</div>

<!-- INICIO FILTRO POR VEHICULO PARA INSPECTION -->
					<?php if($vehicleList){ ?>
						<div class="form-group">
							<div class="col-sm-5 col-sm-offset-1"">
								<label for="vehicleId">Vehicle <small class="danger">(This field is NOT required.)</small></label>
								<select name="vehicleId" id="vehicleId" class="form-control" >
									<option value=''>Select...</option>
									<?php for ($i = 0; $i < count($vehicleList); $i++) { ?>
										<option value="<?php echo $vehicleList[$i]["id_vehicle"]; ?>" ><?php echo $vehicleList[$i]["unit_number"] . ' -----> ' . $vehicleList[$i]["description"]; ?></option>	
									<?php } ?>
								</select>
							</div>
					<?php if($trailerList){ ?>		
							<div class="col-sm-5">
								<label for="trailerId">Trailer <small class="danger">(This field is NOT required.)</small></label>
								<select name="trailerId" id="trailerId" class="form-control" >
									<option value=''>Select...</option>
									<?php for ($i = 0; $i < count($trailerList); $i++) { ?>
										<option value="<?php echo $trailerList[$i]["id_vehicle"]; ?>" ><?php echo $trailerList[$i]["unit_number"] . ' -----> ' . $trailerList[$i]["description"]; ?></option>
									<?php } ?>
								</select>
							</div>
					<?php } ?>
						</div>
					<?php } ?>
<!-- FIN FILTRO POR  VEHICULO PARA INSPECTION -->

						<div class="form-group">
<script>
$( function() {
var dateFormat = "mm/dd/yy",
from = $( "#from" )
.datepicker({
	changeMonth: true,
	numberOfMonths: 2
})
.on( "change", function() {
	to.datepicker( "option", "minDate", getDate( this ) );
}),
to = $( "#to" ).datepicker({
	changeMonth: true,
	numberOfMonths: 2
})
.on( "change", function() {
	from.datepicker( "option", "maxDate", getDate( this ) );
});

function getDate( element ) {
	var date;
	try {
		date = $.datepicker.parseDate( dateFormat, element.value );
	} catch( error ) {
		date = null;
	}

	return date;
}
});
</script>

							<div class="col-sm-5 col-sm-offset-1">
								<label for="from">From Date</label>
								<input type="text" id="from" name="from" class="form-control" placeholder="From" required >
							</div>
							<div class="col-sm-5">
								<label for="to">To Date</label>
								<input type="text" id="to" name="to" class="form-control" placeholder="To" required >
							</div>
						</div>
<div class="row"></div><br>
						<div class="form-group">
							<div class="row" align="center">
								<div style="width80%;" align="center">
									
								 <button type="submit" class="btn btn-primary" id='btnSubmit' name='btnSubmit'><span class="glyphicon glyphicon-search" aria-hidden="true"></span> Search </button>
									
								</div>
							</div>
						</div>
						
					</form>

				</div>
				<!-- /.panel-body -->
			</div>
			<!-- /.panel -->
		</div>
		<!-- /.col-lg-12 -->
	</div>
	<!-- /.row -->
</div>
<!-- /#page-wrapper -->