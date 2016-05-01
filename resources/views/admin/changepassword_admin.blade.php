@extends('layouts.master_admin')

@section('content')
	<form id="changepassword_form">
		<h2>Промена лозинке</h2>

		<div class="form-group label-floating">
		    <label for="password" class="control-label">Стара лозинка:</label>
		    <input type="password" class="form-control" id="password" name="password" >
		</div>

		<div class="form-group label-floating">
		    <label for="password" class="control-label">Нова лозинка:</label>
		    <input type="password" class="form-control" id="password" name="password" >
		</div>

		<div class="form-group label-floating">
		    <label for="password" class="control-label">Поново нова лозинка:</label>
		    <input type="password" class="form-control" id="password" name="password" >
		</div>

		<div class="center">
			<button type="submit" class="btn btn-raised btn-danger btn-lg" name="submit">
				Пријава
			</button>
		</div>
	</form>
@endsection