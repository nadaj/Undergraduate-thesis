@extends('layouts.master_voter')

@section('content')
	<div class="panel panel-primary">
  		<div class="panel-heading" style="text-transform: uppercase">{{ $voting->name }}</div>
  		<div class="panel-body">
  		    <div class="bs-component">
                <p><b>Opis: </b>{{ $voting->description }}</p>
                <p onLoad="set_start_date(this)"><b>Vreme početka:</b> {{ $voting->from }}</p>
                <p onLoad="set_start_date(this)"><b>Vreme završetka:</b> {{ $voting->to }}</p>
                <br/> 
                <div class="progress progress-striped active">
                    <div class="progress-bar" style="width: 10%"></div>
                </div>
                <button data-toggle="modal" data-target="#votingmodal" 
                class="btn btn-primary btn-lg btn-block btn-raised">Glasaj</button>
            </div>
  		</div>
	</div>

    <div class="modal fade" id="votingmodal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Unos tiketa za glasanje</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group label-floating">
                      <label class="control-label" for="focusedInput2">Tiket</label>
                      <input class="form-control" name="ticket" id="focusedInput2" type="text" />
                      <input type="hidden" name="voting_id" value="{{ $voting->id }}" />
                      <p class="help-block">Unesite tiket dobijen u emailu kako biste nastavili sa glasanjem</p>
                    </div>
                    <br/>
                    <div class="center"></div>
                </div>
                    
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Odustani</button>
                    <button type="button" id="nastavi_sa_tiketom" class="btn btn-primary">Nastavi</button>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" name="_token" value="{{ Session::token() }}" />
@endsection