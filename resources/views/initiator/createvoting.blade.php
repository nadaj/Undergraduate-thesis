@extends('layouts.master_initiator')

@section('content')
	<form style="margin-left:2%" id="createVoting" action="{{ route('initiator.createvoting') }}" method="post">
    <p class="lead" style="margin-bottom: 0px">KREIRANJE NOVOG GLASANJA</p>

    <div class="row">
        <div class='col-md-6'>
            <div class="form-group">
                <label for="naslov" class="control-label">Naslov:</label>
                <input type="text" class="form-control" id="naslov" name="naslov" value="{{ old('naslov') }}" >
            </div>
        </div>
    </div>

    <div class="row">
        <div class='col-md-6'>
            <div class="form-group">
                <label for="opis" class="control-label">Opis:</label>
                <textarea class="form-control" rows="3" id="opis" name="opis" value="{{ old('opis') }}"></textarea>
            </div>
        </div>
    </div>

    <div class="row">
        <div class='col-md-6'>
            <div class="form-group">
                <div class='input-group date' id='datetimepicker4'>
                  <label for="vreme1" class="control-label">Datum i vreme početka glasanja:</label>
                    <input name="vreme1" type='text' class="form-control" value="{{ old('vreme1') }}" />
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class='col-md-6'>
            <div class="form-group">
                <div class='input-group date' id='datetimepicker2'>
                  <label for="vreme2" class="control-label">Datum i vreme završetka glasanja:</label>
                    <input name="vreme2" type='text' class="form-control" value="{{ old('vreme2') }}" />
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group label-floating">
      <label>Odgovori:</label>
    </div>

    <div class="row">
      <div class="col-md-6">
        <div class="form-group is-empty is-fileinput" style="margin-top:0px">
          <div class="input-group">
              <input type="text" id="unet_odgovor" class="form-control" placeholder="Unesite opciju" />
                <span class="input-group-btn input-group-sm">
                  <button type="button" id="add_answer" class="btn btn-fab btn-fab-mini">
                    +
                  </button>
                </span>
            </div>
        </div>
      </div>

      <div class="col-md-4 col-md-offset-1">
        <select name="odgovori[]" id="odgovori" multiple>
            <option value="Kreirani odgovori:" disabled>Kreirani odgovori:</option>
        </select>
      </div>
    </div>

    <div class="form-group label-floating">
      <label>Glasači:</label>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="input-group">
                <div class="form-group" style="margin-top:0px">
                    <select name="katedra" id="katedra" class="form-control">
                        <option value="0">Odaberite katedru:</option>
                        @foreach ($katedre as $katedra)
                        <option value="{{ $katedra->id }}">{{ $katedra->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group" style="margin-top:0px">
                    <select name="zvanje" id="zvanje" class="form-control" disabled>
                        <option value="0">Odaberite zvanje:</option>
                    </select>
                </div>
                <div class="form-group" style="margin-top:0px">
                    <select name="korisnik" id="korisnik" class="form-control" disabled>
                        <option value="0">Odaberite korisnika:</option>
                    </select>
                </div>
                <span class="input-group-btn input-group-sm">
                    <button type="button" id="add_user" class="btn btn-fab btn-fab-mini">+</button>
                </span>
            </div>
        </div>
        <div class="col-md-4 col-md-offset-1">
            <select name="glasaci[]" id="glasaci" multiple>
                <option value="0" disabled>Dodati glasači:</option>
            </select>
        </div>
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
        <button type="submit" class="btn btn-raised btn-success btn-lg" name="pokreni">
            Pokreni
        </button>

        <button type="submit" class="btn btn-raised btn-danger btn-lg" name="pregled">
            Pregled
        </button>
        <input type="hidden" name="_token" value="{{ Session::token() }}" />    
    </div>
  </form>
@endsection