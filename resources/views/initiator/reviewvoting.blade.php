@extends('layouts.master_initiator')

@section('content')

<form id="createReviewedVoting" action="{{ route('initiator.createreviewedvoting') }}" method="post">
    <div class="panel panel-default">
    	<div class="panel-heading" style="text-transform: uppercase">{{ $voting->name }}</div>
    	<div class="panel-body">
    	    <div class="bs-component">
                <p><b>Opis: </b>{{ $voting->description }}</p>
                <p><b>Vreme početka:</b> {{ $voting->from }}</p>
                <p><b>Vreme završetka:</b> {{ $voting->to }}</p>
                <p><b>Odgovori:</b></p>
                @foreach($answers as $answer)
                    <p>{{ $answer->answer }}</p>
                @endforeach
                <p>
                    <b>Može se odabrati više odgovora:</b> 
                    @if ($more_ans)
                    Da [{{ $voting->min }} - {{ $voting->max }}]
                    @else
                    Ne
                    @endif
                </p>
                <p><b>Kriterijum uspešnosti glasanja:</b></p>
                <p>
                    @if ($criteriumRadios == "1")
                        Broj glasača je {{ $relacija }} {{ $vrednost }}
                    @else
                        Broj glasova za odgovor <i>{{ $opcija }}</i> je {{ $relacija }} {{ $vrednost }}
                    @endif
                </p>
                <p><b>Glasači:</b></p>
                @foreach($voters as $voter)
                    <p>{{ $voter }}</p>
                @endforeach
            </div>
            <button type="submit" class="btn btn-raised btn-success btn-lg" name="pokreni">
                Pokreni
            </button>
            <button type="submit" class="btn btn-raised btn-default btn-lg" name="povratak">
                Povratak
            </button>
        </div>
    </div>
    <input type="hidden" name="_token" value="{{ Session::token() }}" /> 
    <div class="center">
      @if(Session::has('success'))
        <div class="alert alert-dismissible alert-success">
          <button type="button" class="close" data-dismiss="alert">×</button>
          {{ Session::get('success') }}
        </div>
      @endif
    </div> 
</form>
@endsection