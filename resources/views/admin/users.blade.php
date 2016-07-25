@extends('layouts.master_admin')

@section('content')
	<div class="btn-group btn-group-justified btn-group-raised">
  		<a class="btn active" id="postojeci">Postojeći</a>
  		<a class="btn" id="neodobreni">Neodobreni</a>
	</div>
	<br/>
	<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
	<table class="footable table table-striped table-hover" id="table1" style="display:table">
  		<thead>
  			<tr>
			    <th>#</th>
			    <th>Ime i prezime</th>
			    <th data-hide="phone,tablet">Е-mail</th>
			    <th data-hide="phone,tablet">Zvanje</th>
			    <th data-hide="phone,tablet">Katedra</th>
			    <th data-hide="phone,tablet">Uloga</th>
			</tr>
  		</thead>
  		<tbody>
  			{{-- */$x=0;/* --}}
		  	@foreach ($users as $user)
		  	{{-- */$x++;/* --}}
		  		@if ($user->active)
	  			<tr class="active">
	  				<td>{{ $x }}</td>
				    <td>{{ $user->firstname }} {{ $user->lastname }}</td>
				    <td class="email">{{ $user->email }}</td>
				    <td>{{ $user->title['name'] }}</td>
				    <td>{{ $user->department['name'] }}</td>
			    	@if ($user->role['id'] == 1)
			    	<td>
			    		Administrator
			    	</td>
			    	@endif
			    	@if ($user->role['id'] == 2)
			    	<td>
			    		Inicijator
			    	</td>
			    	@endif
			    	@if ($user->role['id'] == 3)
			    	<td>
			    		Glasač
			    	</td>
			    	@endif
				    <td>
					    <div class="togglebutton">
			              	<label>
			               		<input type="checkbox" class="checkbox_click" checked />
			              	</label>
		            	</div>
		            </td>
	  			</tr>
		  		@else
	  			<tr class="danger">
	  				<td>{{ $x }}</td>
				    <td>{{ $user->firstname }} {{ $user->lastname }}</td>
				    <td>{{ $user->email }}</td>
				    <td>{{ $user->title['name'] }}</td>
				    <td>{{ $user->department['name'] }}</td>
			    	@if ($user->role['id'] == 1)
			    	<td>
			    		Administrator
			    	</td>
			    	@endif
			    	@if ($user->role['id'] == 2)
			    	<td>
			    		Inicijator
			    	</td>
			    	@endif
			    	@if ($user->role['id'] == 3)
			    	<td>
			    		Glasač
			    	</td>
			    	@endif
				    <td>
					    <div class="togglebutton">
			              	<label>
			               		<input type="checkbox" class="checkbox_click" />
			              	</label>
		            	</div>
		            </td>
		  		@endif
		  	@endforeach
		</tbody>
	</table>
	
	<table class="footable table table-striped table-hover" style="display:none" id="table2">
  		<thead>
  			<tr>
			    <th>#</th>
			    <th>Ime i prezime</th>
			    <th data-hide="phone,tablet">Е-mail</th>
			    <th data-hide="phone,tablet">Zvanje</th>
			    <th data-hide="phone,tablet">Katedra</th>
			    <th data-hide="phone,tablet">Uloga</th>
			</tr>
  		</thead>
  		<tbody>
  			{{-- */$x=0;/* --}}
		  	@foreach ($nousers as $user)
		  	{{-- */$x++;/* --}}
  			<tr class="active">
  				<td>{{ $x }}</td>
			    <td>{{ $user->firstname }} {{ $user->lastname }}</td>
			    <td class="email">{{ $user->email }}</td>
			    <td>{{ $user->title['name'] }}</td>
			    <td>{{ $user->department['name'] }}</td>
		    	<td>
		    		<select id="role" name="role" >
		          		<option value="1">Administrator</option>
		          		<option value="2">Inicijator</option>
		          		<option value="3">Glasač</option>
		        	</select>
		    	</td>
			    <td>
			    	<input type="button" id="addUser" name="addUser" value="+" class="btn btn-fab btn-fab-mini" />
    			</td>
  			</tr>
		  	@endforeach
		</tbody>
	</table>

@endsection