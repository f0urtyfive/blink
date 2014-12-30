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
				new_div += " <input "+checked+" data-toggle='toggle' type='checkbox' class='device_toggle' id='device_toggle-"+wink_id+"' name='"+wink_id+"'>";
				new_div += " <input data-slider-value='"+intensity+"' data-slider-max=255 class='slider' id='intensity-"+wink_id+"' name='"+wink_id+"'>";
				new_div += "</div>";
				new_div += "<hr>";


				$('.body').append(new_div);
				$('#device_toggle-'+wink_id).bootstrapToggle().change(function() {
					var checked = "OFF";
					if($(this).prop('checked') == true){
						checked = "ON";
					}
					wink_update_on_off($(this).prop('name'), checked);
					
				});
				$('#intensity-'+wink_id).slider().on('slideStop', function(ev){
					wink_update_intensity(ev.target.name, ev.value);
				});
			}
		});
	});
	function wink_update(device_id, attr_id, value){
		$.post( api_url+'/commands/index.php' , JSON.stringify([{action: "update", id: device_id, updates: [{id:attr_id, value: value}] }]) , function(data){ } );
	}

	function wink_update_intensity(device_id, value){
		wink_update(device_id, 2, value);
	}
	function wink_update_on_off(device_id, value){
		wink_update(device_id, 1, value);
	}

</script>
</head>
<body>
<?php require('include_body_header.php'); ?>
</body>

