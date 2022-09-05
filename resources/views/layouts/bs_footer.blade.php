<div class="container-fluid p-3 bg-success text-white text-center">
	<p>Tenant WEBAPP ©2021 Flub78</p>
</div>

<div> <!-- End of body -->

	<script type="text/javascript">
	<!--
	$(document).ready( function () {
	
	var locale = document.documentElement.lang;    // fr, en, ...
	
	// Localization for datatable
	var olanguage  = new Object();
    olanguage['fr'] = {
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

    olanguage['en'] = {
            "sProcessing":     "Processing...",
            "sLengthMenu":     "Show _MENU_ entries",
            "sZeroRecords":    "No matching records found",
            "sInfo":           "Showing _START_ to _END_ of _TOTAL_ entries",
            "sInfoEmpty":      "Showing 0 to 0 of 0 entries",
            "sInfoFiltered":   "(filtered from _MAX_ total entries)",
            "sInfoPostFix":    "",
            "sSearch":         "Search:",
            "sLoadingRecords": "Loading...",
            "sUrl":            "",
            "oPaginate": {
                "sFirst":    "First",
                "sPrevious": "Previous",
                "sNext":     "Next",
                "sLast":     "Last"
            }                       
    };

    
    // DataTable
	    $('#maintable').DataTable({
        	columnDefs: [ {
            	orderable: false,
            	className: 'select-checkbox',
            	targets:   0
        	} ],
        	select: {
            	style:    'multi+shift',
            	selector: 'td:first-child'
        	},		    
	    	paging:true,
	     	dom: 'lfrtipB',
	     	stateSave: true,
		    buttons: [
		    	{
		    		text: 'Nouveau',
		    	},
		    	'selectAll', 'selectNone',
		        {
		        	text: 'avec la selection',
            		extend: 'selected',
            		action: function ( e, dt, node, config ) {
                		var rows = dt.rows( { selected: true } ).count();
 
                		alert( 'There are '+rows+'(s) selected in the table' );
            		}
        		},
        		'csv', 'pdf', 'colvis'
		    ],
		    "oLanguage": olanguage[locale],
	    });
		
	
	$("#allDay").change(function() {
		if($(this).is(":checked")) {
			all_day_set();
    	} else {
    		all_day_unset();
    	}
	});
	
	all_day_visible();

	
});


	//-->
	</script>

</div>