$(function() {
	$('#type').change(function()
	{
	   if ($(this).attr('value') == "voiture") {
	   	$("#select_voiture").show();
	   	$("#select_moto").hide();
	   	$("#select_scooter").hide();
	   	$('#cylindree').find('option:first').attr('selected', 'selected').parent('select');
	   }
	   else if ($(this).attr('value') == "moto") {
	   	$("#select_voiture").hide();
	   	$("#select_moto").show();
	   	$("#select_scooter").hide();
	   }
	   else if ($(this).attr('value') == "scooter") {
	   	$("#select_voiture").hide();
	   	$("#select_moto").hide();
	   	$("#select_scooter").show();
	   }
	   else {
	   	$("#select_voiture").hide();
	   	$("#select_moto").hide();
	   	$("#select_scooter").hide();

	   }
	});
});