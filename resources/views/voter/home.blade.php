@extends('layouts.master_voter')

@section('content')
	<p class="lead" style="margin-bottom: 0px">AKTUELNA GLASANJA</p>
	<br/>
    <?php $i = 0; ?>
    
    @if (count($votings) == 0)
    <p>Nema aktuelnih glasanja.</p>
    @endif

    @foreach ($votings as $voting)
    @if ($i % 2 == 0)
	<div class="panel panel-primary">
  		<div class="panel-heading" style="text-transform: uppercase">{{ $voting->name }}</div>
  		<div class="panel-body">
  		    <div class="bs-component">
                <p><b>Opis:</b> {{ $voting->description }}</p><br/> 
                <input type="hidden" name="votings_id" value="{{ $voting->id }}" />
                <div class="progress progress-striped active">
                    <div class="progress-bar" style="width: {{ $progresses[$i] }}%"></div>
                </div>
                <a href="{{ route('voter.votinginfo', ['votings_id' => $voting->id]) }}" class="btn btn-primary btn-lg btn-block btn-raised">Više informacija</a>
            </div>
  		</div>
	</div>
    @else
    <div class="panel panel-default">
        <div class="panel-heading" style="text-transform: uppercase">{{ $voting->name }}</div>
        <div class="panel-body">
            <div class="bs-component">
                <p><b>Opis:</b> {{ $voting->description }}</p><br/> 
                <input type="hidden" name="votings_id" value="{{ $voting->id }}" />
                <div class="progress progress-striped active">
                    <div class="progress-bar" style="width: {{ $progresses[$i] }}%"></div>
                </div>
                <a href="{{ route('voter.votinginfo', ['votings_id' => $voting->id]) }}" class="btn btn-default btn-lg btn-block btn-raised">Više informacija</a>
            </div>
        </div>
    </div>
    @endif
    <?php $i++; ?>
    @endforeach
@endsection