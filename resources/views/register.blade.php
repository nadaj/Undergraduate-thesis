<!DOCTYPE html>
<html>
	<head>
		<link rel="shortcut icon" href="{{ URL::asset('images/favicon.ico') }}" />
		<meta charset="UTF-8" />
		<title>е-Glasanje</title>
		<link href='https://fonts.googleapis.com/css?family=Roboto+Condensed|Open+Sans+Condensed:300' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" type="text/css" href="{{ URL::asset('css/main.css') }}"/>
		<link rel="stylesheet" type="text/css" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">

		<!-- Bootstrap -->
  		<link rel="stylesheet" type="text/css" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">

		<!-- Bootstrap Material Design -->
		<link rel="stylesheet" type="text/css" href="../bower_components/bootstrap-material-design/dist/css/bootstrap-material-design.css">
		<link rel="stylesheet" type="text/css" href="../bower_components/bootstrap-material-design/dist/css/ripples.min.css">
	</head>
	<body id="generalbody">
		<div id="regwrapper" class="well">
			<header id="header">
				<span id="coltitle">
					ELEKTROTEHNIČKI FAKULTET
				</span>
				<span id="unititle">
					UNIVERZITET U BEOGRADU
				</span>
			</header>
			<section id="content">
				<form id="login_form">
					<h2>Registracija</h2>
					<h4>Unesite Vaše podatke i e-mail adresu na kojoj ćе Vam stići potvrda о registraciji.</h4>
					<div class="form-group">
					    <label for="email" class="col-md-2 control-label">Е-mail adresa:</label>
					    <div class="col-md-10">
					    	<input type="text" class="form-control" id="email" name="email" >
						</div>
					</div>
					<div class="form-group">
					    <label for="fname" class="col-md-2 control-label">Ime:</label>
					    <div class="col-md-10">
					    	<input type="text" class="form-control" id="fname" name="fname" >
						</div>
					</div>
					<div class="form-group">
					    <label for="lname" class="col-md-2 control-label">Prezime:</label>
					    <div class="col-md-10">
					    	<input type="text" class="form-control" id="lname" name="lname" >
						</div>
					</div>
					<div class="form-group">
					    <label for="title" class="col-md-2 control-label">Zvanje:</label>
					    <div class="col-md-10">
						    <select id="title" class="form-control">
						    	<option>Odaberite zvanje:</option>
				          		<option>Redovni profesor</option>
				          		<option>Vanredni profesor</option>
				          		<option>Docent</option>
				          		<option>Asistent</option>
				          		<option>Saradnik u nastavi</option>
				          		<option>Gostujući profesor</option>
				        	</select>
				        </div>
					</div>
					<div class="form-group">
					    <label for="title" class="col-md-2 control-label">Katedra:</label>
					   	<div class="col-md-10">
						    <select id="title" class="form-control">
						    	<option>Оdaberite katedru:</option>
				          		<option>Katedra za elektroniku</option>
				          		<option>Katedra za elektroenergetske sisteme</option>
				          		<option>Katedra za energetske pretvarače i pogone</option>
				          		<option>Katedra za mikroelektroniku i tehničku fiziku</option>
				          		<option>Katedra za opštu elektrotehniku</option>
				          		<option>Katedra za opšte obrazovanje</option>
				          		<option>Katedra za primenjenu matematiku</option>
				          		<option>Katedra za računarsku tehniku i informatiku</option>
				          		<option>Katedra za signale i sisteme</option>
				          		<option>Katedra za telekomunikacije</option>
				        	</select>
				        </div>
					</div>
					<div class="center">
						<button type="submit" class="btn btn-raised btn-danger btn-lg" name="submit">
							Registrujte se
						</button>
						<a href="{{ route('login') }}" class="btn btn-raised btn-default btn-lg">
							Odustanite	
						</a>
					</div>
				</form>
			</section>
		</div>
		<!-- SCRIPTS -->
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
		<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
		<script src="../bower_components/bootstrap-material-design/dist/js/ripples.min.js"></script>
		<script src="../bower_components/bootstrap-material-design/dist/js/material.min.js"></script>
		<script>
		  $(function () {
		    $.material.init();
		  });
		</script>
	</body>
</html>