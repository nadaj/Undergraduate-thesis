@extends('layouts.master_initiator')

@section('content')
<div class="btn-group btn-group-justified btn-group-raised">
      <a href="{{ route('initiator.createvoting') }}" class="btn">Kreiraj novo glasanje</a>
  </div>
  <br/>
	<div class="btn-group btn-group-justified btn-group-raised">
  		<a href="javascript:void(0)" class="btn active">Tekuća glasanja</a>
  		<a href="javascript:void(0)" class="btn">Prošla glasanja</a>
	</div>
	<br/>
  @foreach ($my_votings as $voting)
	<div class="panel panel-primary">
  		<div class="panel-heading">{{ $voting->name }}</div>
  		<div class="panel-body">
  		    <div class="bs-component">
              <p><b>Opis: </b>{{ $voting->description }}</p>
              <p><b>Vreme početka:</b> {{ $voting->from }}</p>
              <p><b>Vreme završetka:</b> {{ $voting->to }}</p>
              <br/> 
          		<div class="progress progress-striped active">
            		<div class="progress-bar" style="width: 10%"></div>
          		</div>
        	</div>
  		</div>
	</div>
	@endforeach
  
@endsection