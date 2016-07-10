@extends('layouts.master_initiator')

@section('content')
    <a href="{{ route('initiator.createvoting') }}" class="btn btn-default btn-fab" 
    style="position: fixed;bottom: 1%;right: 1%;color:#ffffff;background-color:rgb(207, 45, 45)" >
        <i class="material-icons">add</i>
    </a>
	<div class="btn-group btn-group-justified btn-group-raised">
  		<a class="btn active" id="tekuca">Tekuća glasanja</a>
        <a class="btn" id="prosla">Prošla glasanja</a>
	</div>
	<br/>
    
    <div id="tekuca_glasanja">
        <?php $i = 0; ?>
        @foreach ($my_votings as $voting)
        @if ($i % 2 == 0)
    	<div class="panel panel-primary">
      		<div class="panel-heading">{{ $voting->name }}</div>
      		<div class="panel-body">
      		    <div class="bs-component">
                  <p><b>Opis: </b>{{ $voting->description }}</p>
                  <p><b>Vreme početka:</b> {{ $voting->from }}</p>
                  <p><b>Vreme završetka:</b> {{ $voting->to }}</p>
                  <br/> 
              		<div class="progress progress-striped active">
                		<div class="progress-bar" style="width: {{ $progresses[$i] }}%"></div>
              		</div>
            	</div>
      		</div>
    	</div>
        @else
        <div class="panel panel-default">
            <div class="panel-heading">{{ $voting->name }}</div>
            <div class="panel-body">
                <div class="bs-component">
                  <p><b>Opis: </b>{{ $voting->description }}</p>
                  <p><b>Vreme početka:</b> {{ $voting->from }}</p>
                  <p><b>Vreme završetka:</b> {{ $voting->to }}</p>
                  <br/> 
                    <div class="progress progress-striped active">
                        <div class="progress-bar" style="width: {{ $progresses[$i] }}%"></div>
                    </div>
                </div>
            </div>
        </div>
        @endif
        <?php $i++; ?>
    	@endforeach
        {!! $my_votings->render() !!}
    </div>

    <div id="prosla_glasanja" style="display:none">
        <?php $j = 0; ?>
        @foreach ($my_votings_past as $voting)
        @if ($j % 2 == 0)
        <div class="panel panel-primary">
            <div class="panel-heading">{{ $voting->name }}</div>
            <div class="panel-body">
                <div class="bs-component">
                  <p><b>Opis: </b>{{ $voting->description }}</p>
                  <p><b>Vreme početka:</b> {{ $voting->from }}</p>
                  <p><b>Vreme završetka:</b> {{ $voting->to }}</p>
                </div>
            </div>
        </div>
        @else
        <div class="panel panel-default">
            <div class="panel-heading">{{ $voting->name }}</div>
            <div class="panel-body">
                <div class="bs-component">
                  <p><b>Opis: </b>{{ $voting->description }}</p>
                  <p><b>Vreme početka:</b> {{ $voting->from }}</p>
                  <p><b>Vreme završetka:</b> {{ $voting->to }}</p>
                </div>
            </div>
        </div>
        @endif
        <?php $j++; ?>
        @endforeach
        {!! $my_votings_past->render() !!}
    </div>
@endsection