<div class="container py-4">
	<p>Tenant WEBAPP ©2021 Flub78</p>
</div>
<div>

	<script type="text/javascript">
	<!--
	$(document).ready( function () {
	
	var locale = document.documentElement.lang;
	
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
		
$.datepicker.regional['it'] = {
    closeText: 'Chiudi', // set a close button text
    currentText: 'Oggi', // set today text
    monthNames: ['Gennaio','Febbraio','Marzo','Aprile','Maggio','Giugno',   'Luglio','Agosto','Settembre','Ottobre','Novembre','Dicembre'], // set month names
    monthNamesShort: ['Gen','Feb','Mar','Apr','Mag','Giu','Lug','Ago','Set','Ott','Nov','Dic'], // set short month names
    dayNames: ['Domenica','Luned&#236','Marted&#236','Mercoled&#236','Gioved&#236','Venerd&#236','Sabato'], // set days names
    dayNamesShort: ['Dom','Lun','Mar','Mer','Gio','Ven','Sab'], // set short day names
    dayNamesMin: ['Do','Lu','Ma','Me','Gio','Ve','Sa'], // set more short days names
    dateFormat: 'dd/mm/yy' // set format date
};

$.datepicker.regional['en'] = {
    days: ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"],
    daysShort: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"],
    daysMin: ["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa"],
    months: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
    monthsShort: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
    today: "Today",
    clear: "Clear",
    format: "mm/dd/yyyy",
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