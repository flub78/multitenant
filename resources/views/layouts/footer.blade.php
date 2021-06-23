<p>Tenant WEBAPP ©2021 Flub78</p>

<div>

	<script type="text/javascript">
	<!--
	$(document).ready( function () {
	
	    $('#maintable').DataTable({
	    paging:true,
	     dom: 'Blfrtip',
		    buttons: [
		        'csv',
		        'print',
		        'pdf', 'colvis'
		    ]
	    });

	
	$( ".datepicker" ).datepicker();
	 
$('.timepicker').timepicker({
    timeFormat: 'HH:mm',
    interval: 5,
    minTime: '09:00',
    maxTime: '20:00',
	defaultTime: '12:00',
    startTime: '09:00',
    dynamic: false,
    dropdown: true,
    scrollbar: true
});

    $(".colorpicker").spectrum({
        color: "#f00"
    });	 

    $(".namedcolorpicker").spectrum({
        color: "#f00"
    });
    
    $(".namedcolorpicker").spectrum({
    	showPaletteOnly: true,
    	showPalette:true,
    	hideAfterPaletteSelect:true,
    	color: 'blanchedalmond',
    	/*
		change: function(color) {
        	printColor(color);
    	},
    	*/
    	palette:["black", "white", "red", "green", "blue", "yellow", "orange", "cyan", "magenta", "lightgreen", "lightblue", "pink", "brown"],
	});
});

function printColor(color) {
	alert(color.toName());
   //var text = "You chose... " + color.toHexString();    
   //$(".label").text(text);    
}	 
	//-->
	</script>

</div>