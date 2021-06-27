<div class="container py-4">
	<p>Tenant WEBAPP ©2021 Flub78</p>
</div>
<div>

	<script type="text/javascript">
	<!--
	$(document).ready( function () {
	
    var olanguage = {
            "sProcessing":     "Traitement en cours...",
            "sLengthMenu":     "Afficher _MENU_ &eacute;l&eacute;ments",
            "sZeroRecords":    "Aucun &eacute;l&eacute;ment &agrave; afficher",
            "sInfo":           "Affichage de l'&eacute;lement _START_ &agrave; _END_ sur _TOTAL_ &eacute;l&eacute;ments",
            "sInfoEmpty":      "Affichage de l'&eacute;lement 0 &agrave; 0 sur 0 &eacute;l&eacute;ments",
            "sInfoFiltered":   "(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)",
            "sInfoPostFix":    "",
            "sSearch":         "Rechercher&nbsp;:",
            "sLoadingRecords": "Téléchargement...",
            "sUrl":            "",
            "oPaginate": {
                "sFirst":    "Premier",
                "sPrevious": "Pr&eacute;c&eacute;dent",
                "sNext":     "Suivant",
                "sLast":     "Dernier"
            }                       
    };
    
	    $('#maintable').DataTable({
	    	paging:true,
	     	dom: 'Blfrtip',
		    buttons: [
		        'csv',
		        'print',
		        'pdf', 'colvis'
		    ],
		    "oLanguage": olanguage
	    });
		
	$( ".datepicker" ).datepicker($.datepicker.regional[ "fr" ]);
	 
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