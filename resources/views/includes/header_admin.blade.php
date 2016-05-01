<div class="navbar navbar-default">
  	<div class="container-fluid">
    	<div class="navbar-header">
	      	<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-responsive-collapse">
	        	<span class="icon-bar"></span>
		        <span class="icon-bar"></span>
		        <span class="icon-bar"></span>
	      	</button>
	      	<a class="navbar-brand" href="{{ route('admin.home') }}">е-Гласање</a>
	      	<div id="logo">
	      		<a href="#">ETF</a>
	      	</div>
    	</div>
    	<div class="navbar-collapse collapse navbar-responsive-collapse">
	      	<ul class="nav navbar-nav navbar-right">
		        <li><a href="{{ route('admin.users_admin') }}">Корисници</a></li>
		        <li class="dropdown">
		          	<a href="http://fezvrasta.github.io/bootstrap-material-design/bootstrap-elements.html" data-target="#" class="dropdown-toggle" data-toggle="dropdown">
		          		Налог
		            	<b class="caret"></b>
		        	</a>
		          	<ul class="dropdown-menu">
			            <li><a href="{{ route('admin.changepassword_admin') }}">Промена лозинке</a></li>
			            <li class="divider"></li>
			            <li><a href="{{ route('logout') }}">Одјава <i class="material-icons">highlight_off</i></a></li>
		          	</ul>
		        </li>
      		</ul>
    	</div>
  	</div>
</div>