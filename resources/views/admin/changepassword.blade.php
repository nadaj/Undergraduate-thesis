@extends('layouts.master_admin')

@section('content')
	<form id="changepassword_form" action="{{ route('admin.changepassword') }}" method="post">
		<h2>Promena lozinke</h2>

		<div class="form-group label-floating">
		    <label for="old_password" class="control-label">Stara lozinka:</label>
		    <input type="password" class="form-control" id="old_password" name="old_password" >
		</div>

		<div class="form-group label-floating">
		    <label for="password" class="control-label">Nova lozinka:</label>
		    <input type="password" class="form-control" id="password" name="password" >
		</div>

		<div class="form-group label-floating">
		    <label for="password_confirmation" class="control-label">Potvrda lozinke:</label>
		    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" >
		</div>

		<div class="center">
			@if(count($errors) > 0)
			<div class="alert alert-dismissible alert-danger">
				<button type="button" class="close" data-dismiss="alert">×</button>
				@foreach($errors->all() as $error)
				<p>
					{{ $error }}
				</p>
				@endforeach
			</div>
			@endif

			@if(Session::has('fail'))
				<div class="alert alert-dismissible alert-danger">
  					<button type="button" class="close" data-dismiss="alert">×</button>
					{{ Session::get('fail') }}
				</div>
			@endif
			@if(Session::has('success'))
				<div class="alert alert-dismissible alert-success">
					<button type="button" class="close" data-dismiss="alert">×</button>
					{{ Session::get('success') }}
				</div>
			@endif
		</div>

		<div class="center">
			<button type="submit" class="btn btn-raised btn-danger btn-lg" name="submit">
				Promeni
			</button>
			<input type="hidden" name="_token" value="{{ Session::token() }}" />
			<a href="{{ route('login') }}" class="btn btn-raised btn-default btn-lg">
				Odustanite	
			</a>		
		</div>
	</form>
@endsection