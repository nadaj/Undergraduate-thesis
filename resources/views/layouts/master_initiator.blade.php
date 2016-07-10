<!DOCTYPE html>
<html>
	<head>
		<link rel="shortcut icon" href="{{ URL::asset('images/favicon.ico') }}" />
		<meta charset="UTF-8" />
		<title>е-Гласање</title>
		<link href='https://fonts.googleapis.com/css?family=Roboto+Condensed|Open+Sans+Condensed:300' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" type="text/css" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="{{ URL::asset('css/main.css') }}"/>

		<!-- Bootstrap -->
		<link rel="stylesheet" type="text/css" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">

		<!-- Bootstrap Material Design -->
		<link rel="stylesheet" type="text/css" href="../../bower_components/bootstrap-material-design/dist/css/bootstrap-material-design.css">
		<link rel="stylesheet" type="text/css" href="../../bower_components/bootstrap-material-design/dist/css/ripples.min.css">

		<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  		<link rel="stylesheet" href="/bower_components/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css" />
		<link href="../../node_modules/toastr/build/toastr.css" rel="stylesheet"/>
	</head>
	<body style="background-color:#ffffff" class="fuelux">
		@include('includes.header_initiator')

		<div class="main">
            @yield('content')
        </div>
        
        @include('includes.footer')

		<!-- SCRIPTS -->
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
		<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
		<script src="../../bower_components/bootstrap-material-design/dist/js/ripples.min.js"></script>
		<script src="../../bower_components/bootstrap-material-design/dist/js/material.min.js"></script>
		<script type="text/javascript" src="/bower_components/moment/min/moment.min.js"></script>
	  	<script type="text/javascript" src="/bower_components/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>
		<script>
		  $(function () {
		    $.material.init();
		  });

		  $(function () {
                $('#datetimepicker4').datetimepicker();
                $('#datetimepicker2').datetimepicker();
            });
		</script>
		<script type="text/javascript" src="../../js/main.js"></script>
		<script src="../../node_modules/toastr/toastr.js"></script>
		@if (Session::has('vote_success'))
        <script>
            $(document).ready(function() {
            	toastr.options = {"closeButton" : true, "showDuration": "500"};
                toastr.success("{{ Session::get('vote_success')}}");
                {{ Session::forget('vote_success') }}
            });
        </script>
    	@endif
	</body>
</html>