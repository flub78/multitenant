# Localization

The application uses the [Laravel Localization mechanism](https://laravel.com/docs/8.x/localization).

Initially "en" and "fr" will be provided, more to validate the implementation than to provide a real feature. Some of the parent project had support in Dutch but there is currently no immediate requirement except for French. 

Note that it is quite obvious that local should be configurable per tenant, [see configuration](configuration.md)

## Html lang

Before starting:

    <html lang="en">
    
in former projects:

    <html lang="{{ app()->getLocale() }}">
    
By default the language is stored in config/app.php. Locale must be defined early in the request processing and possibly in one place (To to compute it in every controller to pass to the views and forget some places.

It cannot be done in the boot function of the AppServiceProvider as the tenant contexts are not set yet.

### Solution

A special tenancy bootstrapper class (LocaleTenancyBootstrapper) is used to

* Save the value of the current locale before the tenant context is established
* Switch it to the value specified in the tenant database configurations table at bootstrap
* Restore it when the tenant context is terminated

This class needs to be registered in the tenancy config file.

    'bootstrappers' => [
        Stancl\Tenancy\Bootstrappers\DatabaseTenancyBootstrapper::class,
        Stancl\Tenancy\Bootstrappers\CacheTenancyBootstrapper::class,
        Stancl\Tenancy\Bootstrappers\FilesystemTenancyBootstrapper::class,
        Stancl\Tenancy\Bootstrappers\QueueTenancyBootstrapper::class,
        App\LocaleTenancyBootstrapper::class,

With this approach, the locale can be set and changed in the tenant configuration (saved in the tenant database) and accessed the standard Laravel way with getLocale().

## Fullcalendar localization

First the locale is fetched from the HTML page.

        var locale = document.documentElement.lang;
    
Then this local is used for javascript element localization

https://stackoverflow.com/questions/2418041/how-to-load-jquery-ui-from-google-cdn-with-locale-different-than-english

## Datatable

Datatable can be simply localized by importing a javascript file, depending on the current locale, which defines some arrays with translated values.

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
    
    var months = [ 'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin',
                  'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre',
                  'Décembre' ];
                  
        $('.datedtable_ro').dataTable({
        "bFilter": true,
        "bPaginate": true,
        "iDisplayLength": 25,
        "bSort": false,
        "bInfo": true,
         "bJQueryUI": true,
        "bStateSave": false,
        "bAutoWidth": true,
        "sPaginationType": "full_numbers",
        "aoColumns" : [
            { "sType": "date-uk" },
            {"bSortable" : true},
            {"bSortable" : true},
            {"bSortable" : true},
            {"bSortable" : true},
            {"bSortable" : false},
            {"bSortable" : false},
            {"bSortable" : false},
            {"bSortable" : false},
            {"bSortable" : false}
        ],
        "oLanguage": olanguage
    });
    
    https://datatables.net/manual/i18n
    
## JQuery datepicker

