<div class="navbar navbar-default navbar-static-top">
  	<div class="container">
    	<div class="navbar-header">
	      	<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-responsive-collapse">
	        	<span class="icon-bar"></span>
		        <span class="icon-bar"></span>
		        <span class="icon-bar"></span>
	      	</button>
	      	<a class="navbar-brand" href="{{ route('initiator.home') }}">е-Glasanje</a>
	      	<div id="logo">
	      		<a href="#">ETF</a>
	      	</div>
    	</div>
    	<div class="navbar-collapse collapse navbar-responsive-collapse">
	      	<ul class="nav navbar-nav navbar-right">
		        <li><a href="{{ route('initiator.votings') }}">Moja glasanja</a></li>
		        <li class="dropdown">
		          	<a href="http://fezvrasta.github.io/bootstrap-material-design/bootstrap-elements.html" data-target="#" class="dropdown-toggle" data-toggle="dropdown">
		          		Nalog
		            	<b class="caret"></b>
		        	</a>
		          	<ul class="dropdown-menu">
			            <li><a href="{{ route('initiator.changepassword') }}">Promena lozinke</a></li>
			            <li class="divider"></li>
			            <li><a href="{{ route('logout') }}">Odjava</a></li>
		          	</ul>
		        </li>
      		</ul>
    	</div>
  	</div>
</div>