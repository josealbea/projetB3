$(function() {
	$('#type').change(function()
	{
	   if ($(this).attr('value') == "voiture") {
	   	$("#select_voiture").show();
	   	$("#select_moto").hide();
	   	$("#select_scooter").hide();
	   	$('#cylindree_m').find('option:first').attr('selected', 'selected').parent('select');
	   	$('#cylindree_s').find('option:first').attr('selected', 'selected').parent('select');	   	
	   }
	   else if ($(this).attr('value') == "moto") {
	   	$("#select_voiture").hide();
	   	$("#select_moto").show();
	   	$("#select_scooter").hide();
	    $('#cylindree_s').find('option:first').attr('selected', 'selected').parent('select');
	   	$('#boite_vitesse').find('option:first').attr('selected', 'selected').parent('select');
	   	$('#nb_places').find('option:first').attr('selected', 'selected').parent('select');
	   }
	   else if ($(this).attr('value') == "scooter") {
	   	$("#select_voiture").hide();
	   	$("#select_moto").hide();
	   	$("#select_scooter").show();
	   	$('#cylindree_m').find('option:first').attr('selected', 'selected').parent('select');
	   	$('#boite_vitesse').find('option:first').attr('selected', 'selected').parent('select');
	   	$('#nb_places').find('option:first').attr('selected', 'selected').parent('select');
	   }
	   else {
	   	$("#select_voiture").hide();
	   	$("#select_moto").hide();
	   	$("#select_scooter").hide();

	   }
	});
});