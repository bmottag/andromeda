<script>
$(function(){ 
	$(".btn-success").click(function () {	
			var oID = $(this).attr("id");
            $.ajax ({
                type: 'POST',
				url: base_url + '/settings/cargarModalJob',
                data: {'idJob': oID},
                cache: false,
                success: function (data) {
                    $('#tablaDatos').html(data);
                }
            });
	});	
});

function seleccionar_todo(){
   for (i=0;i<document.jobs_state.elements.length;i++)
      if(document.jobs_state.elements[i].type == "checkbox")
         document.jobs_state.elements[i].checked=1
} 


function deseleccionar_todo(){
   for (i=0;i<document.jobs_state.elements.length;i++)
      if(document.jobs_state.elements[i].type == "checkbox")
         document.jobs_state.elements[i].checked=0
} 



</script>

<div id="page-wrapper">
	<br>
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h4 class="list-group-item-heading">
					<i class="fa fa-gear fa-fw"></i> SETTINGS - JOB CODE/NAME
					</h4>
				</div>
			</div>
		</div>
		<!-- /.col-lg-12 -->				
	</div>
	
	<!-- /.row -->
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<i class="fa fa-briefcase"></i> JOB CODE/NAME LIST
				</div>
				<div class="panel-body">
					<button type="button" class="btn btn-success btn-block" data-toggle="modal" data-target="#modal" id="x">
							<span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Add a Job Code/Name
					</button><br>
										
<?php
$retornoExito = $this->session->flashdata('retornoExito');
if ($retornoExito) {
    ?>
	<div class="col-lg-12">	
		<div class="alert alert-success ">
			<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
			<?php echo $retornoExito ?>		
		</div>
	</div>
    <?php
}

$retornoError = $this->session->flashdata('retornoError');
if ($retornoError) {
    ?>
	<div class="col-lg-12">	
		<div class="alert alert-danger ">
			<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
			<?php echo $retornoError ?>
		</div>
	</div>
    <?php
}
?> 
				<?php
					if($info){
				?>	

<form  name="jobs_state" id="jobs_state" method="post" action="<?php echo base_url("admin/jobs_state"); ?>">

	
<a href="javascript:seleccionar_todo()">Check all</a> |
<a href="javascript:deseleccionar_todo()">Uncheck all</a> 


				
					<table width="100%" class="table table-striped table-bordered table-hover" id="dataTables">
						<thead>
							<tr>
								<th class="text-center">Job Code/Name</th>
								<th class="text-center">State 

<button type="submit" class="btn btn-primary btn-xs" id="btnSubmit2" name="btnSubmit2" >
	Update <span class="glyphicon glyphicon-edit" aria-hidden="true">
</button>

								</th>
								<th class="text-center">Edit</th>
							</tr>
						</thead>
						<tbody>							
						<?php
							foreach ($info as $lista):
									echo "<tr>";
									echo "<td>" . $lista['job_description'] . "</td>";
									echo "<td class='text-center'>";
									switch ($lista['state']) {
										case 1:
											$valor = 'Active';
											$clase = "text-success";
											$estado = TRUE;
											break;
										case 2:
											$valor = 'Inactive';
											$clase = "text-danger";
											$estado = FALSE;
											break;
									}
									echo '<p class="' . $clase . '"><strong>' . $valor . '</strong>';
									
									
									$data = array(
										'name' => 'job[]',
										'id' => 'job',
										'value' => $lista['id_job'],
										'checked' => $estado,
										'style' => 'margin:10px'
									);
									echo form_checkbox($data);
									
									echo '</p>';
									
									echo "</td>";
									echo "<td class='text-center'>";
						?>
									<button type="button" class="btn btn-success btn-xs" data-toggle="modal" data-target="#modal" id="<?php echo $lista['id_job']; ?>" >
										Edit <span class="glyphicon glyphicon-edit" aria-hidden="true">
									</button>
						<?php
									echo "</td>";
							endforeach;
						?>
						</tbody>
					</table>
					
</form>
					
				<?php } ?>
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
		
				
<!--INICIO Modal para adicionar HAZARDS -->
<div class="modal fade text-center" id="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">    
	<div class="modal-dialog" role="document">
		<div class="modal-content" id="tablaDatos">

		</div>
	</div>
</div>                       
<!--FIN Modal para adicionar HAZARDS -->

<!-- Tables -->
<script>
$(document).ready(function() {
	$('#dataTables').DataTable({
		"order": [[ 1, "asc" ], [ 0, "asc" ]],
		responsive: true,
		 paging: false,
		"searching": true
	});
});
</script>