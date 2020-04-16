/**
 * Vehicle information by vin number
 * @author bmottag
 * @since  14/4/2020
 */

$(document).ready(function () {
	   
    $('#vinNumber').blur(function () {
		var vinNumber = $('#vinNumber').val();
		if (vinNumber > 0 || vinNumber != '') {
			$("#div_vehicle").css("display", "inline");
			
			$.ajax ({
				type: 'POST',
				url: base_url + 'inspection/vehicleInfo',
				data: {'vinNumber': vinNumber},
				cache: false,
				success: function (data)
				{
					$('#div_vehicle_info').html(data);
				}
			});
		} else {				
			var data = '';
			$("#div_vehicle").css("display", "none");
			$('#div_vehicle_info').html(data);
		}
    });
    
});