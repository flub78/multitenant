    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    
    <!-- Initially I have considered to use specific layouts for the views using calendar and datatable. The objective
    was to load pages faster when these features are not required. However it is considered that in most of the cases
    a user will display at least one view with the calendar and at least one view with the datatable. So these
    library will be cached and there is no gain to have lighter weight pages -->
    
	<!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Font awsome -->
    <script src="https://kit.fontawesome.com/408316024a.js" crossorigin="anonymous"></script>

    <!-- Datatable, Jequery is required for datatable -->
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>
    <!--  link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.1.3/css/bootstrap.min.css">
    -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css">
    
    <!-- Datatable Extensions 
-->
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css">
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.colVis.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
    <script src=""></script>

    <!-- Fullcalendar -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.2/main.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.2/locales-all.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.2/main.min.css">
   
    <!-- Third party utilities ->
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
	<!-- ############################"""" -
	

	<script src="https://cdn.jsdelivr.net/combine/npm/fullcalendar@5.8.0/locales-all.js,npm/fullcalendar@5.8.0/main.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.8.0/locales-all.js"></script>
	    
    <!-- Application settings -->
    <script src="{{ asset('js/calendar_setting.js') }}"></script> 
      
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    
    <!--  link href="{{ asset('css/app.css') }}" rel="stylesheet" -->
    <link href="{{ asset('css/multi.css') }}" rel="stylesheet" >
    
<style>
  .uper {
    margin-top: 40px;
  }
</style>
    
    
