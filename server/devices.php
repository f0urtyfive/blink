<?php

require_once('config.php');


?>

<html>
<head>
<?php require('include_head.php'); ?>
<script>
	var api_url = '<?php echo $_conf['server_addr'];?>';

	$(document).ready(function(){
		$.getJSON(api_url + "devices/index.php", function(data){
			for(var i = 0; i < data.length; i++){
				//According to my device, attributeTypeId == 1 is ON/OFF and == 2 is intensity 0-255
				var on_off = ($.grep(data[i].attributes, function(e){  return e.attributeTypeId == 1;}))[0].value;
				var intensity = ($.grep(data[i].attributes, function(e){  return e.attributeTypeId == 2;}))[0].value;
				var wink_id = data[i].id;
				var wink_name = data[i].name;

				var checked = "";
				if( on_off == "ON"){ 
					checked = "checked";
				}

				var new_div = "";
				new_div += "<div class='device'>";
				new_div += " <span class='device name'>"+wink_name+"</span>";
				new_div += " <br />";
				new_div += " <input "+checked+" data-toggle='toggle' type='checkbox' class='device_toggle' id='device_toggle-"+wink_id+"'>";
				new_div += " <input type='textbox' data-slider-value='"+intensity+"' data-slider-max=255 class='slider' id='intensity-"+wink_id+"'>";
				new_div += "</div>";
				new_div += "<hr>";


				$('.body').append(new_div);
				$('#device_toggle-'+wink_id).bootstrapToggle();
				$('#intensity-'+wink_id).slider();
			}
		});
	});
</script>
</head>
<body>
<?php require('include_body_header.php'); ?>
</body>

