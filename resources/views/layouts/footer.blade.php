<div class="container py-4">
	<p>Tenant WEBAPP ©2021 Flub78</p>
</div>
<div>

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
	    	paging:true,
	     	dom: 'Blfrtip',
		    buttons: [
		        'csv',
		        'print',
		        'pdf', 'colvis'
		    ],
		    "oLanguage": olanguage[locale]
	    });
		
$.datepicker.regional['en'] = {
    days: ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"],
    daysShort: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"],
    daysMin: ["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa"],
    months: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
    monthsShort: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
    today: "Today",
    clear: "Clear",
    dateFormat: "mm-dd-yy",
    titleFormat: "MM yyyy", /* Leverages same syntax as 'format' */
    weekStart: 0
};

$.datepicker.regional['fr'] = {
    closeText: "Fermer",
    prevText: "Précédent",
    nextText: "Suivant",
    currentText: "Aujourd'hui",
    monthNames: [ "janvier", "février", "mars", "avril", "mai", "juin",
        "juillet", "août", "septembre", "octobre", "novembre", "décembre" ],
    monthNamesShort: [ "janv.", "févr.", "mars", "avr.", "mai", "juin",
        "juil.", "août", "sept.", "oct.", "nov.", "déc." ],
    dayNames: [ "dimanche", "lundi", "mardi", "mercredi", "jeudi", "vendredi", "samedi" ],
    dayNamesShort: [ "dim.", "lun.", "mar.", "mer.", "jeu.", "ven.", "sam." ],
    dayNamesMin: [ "D","L","M","M","J","V","S" ],
    weekHeader: "Sem.",
    dateFormat: "dd/mm/yy",
    firstDay: 1,
    isRTL: false,
    showMonthAfterYear: false,
    yearSuffix: "" };
    		
	$( ".datepicker" ).datepicker($.datepicker.regional[locale]);
	 
	$('.timepicker').timepicker({
    	timeFormat: 'HH:mm',
    	interval: 5,
    	minTime: '09:00',
    	maxTime: '20:00',
    	startTime: '09:00',
    	dynamic: false,
    	dropdown: true,
    	scrollbar: true
	});

    $(".colorpicker").colorpicker({
    });

});


	//-->
	</script>

</div>