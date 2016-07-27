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
                <textarea class="form-control" rows="3" id="opis" name="opis">{{ old('opis') }}</textarea>
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

    <div class="row" style="margin-top:28px">
        <div class="col-md-7">
            <table class="footable table table-striped" id="tableanswers">
                <thead>
                    <tr>
                        <th>Odgovor</th>
                        <th data-hide="phone,tablet"><center>Ukloni</center></th>
                        <th style="border-bottom:0px">
                            <button style="margin-top:2px;margin-bottom:0px" class="btn btn-raised btn-default btn-xs" id="dodaj_odgovor" name="dodaj_odgovor">
                                <i class="material-icons">add</i>
                            </button>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @if (old('odg') == null)
                    <tr>
                        <td style="padding-bottom:2px;padding-top:0px">
                            <div class="form-group" style="margin-top:0px;padding-bottom:0px;">
                                <input type="text" style="margin-bottom:0px;padding-bottom:0px;padding-top:0px" class="form-control" name="odg[]" value="Za" >
                            </div>
                        </td>
                        <td style="padding-bottom:0px;padding-top:0px">
                            <center>
                                <button style="margin-top:2px;margin-bottom:0px" class="btn btn-raised btn-danger btn-xs" id="ukloni_odgovor" name="ukloni_odgovor">
                                    <i class="material-icons">delete</i>
                                </button>
                            </center>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding-bottom:2px;padding-top:0px">
                            <div class="form-group" style="margin-top:0px;padding-bottom:0px;">
                                <input type="text" style="margin-bottom:0px;padding-bottom:0px;padding-top:0px" class="form-control" name="odg[]" value="Protiv" >
                            </div>
                        </td>
                        <td style="padding-bottom:0px;padding-top:0px">
                            <center>
                                <button style="margin-top:2px;margin-bottom:0px" class="btn btn-raised btn-danger btn-xs" id="ukloni_odgovor" name="ukloni_odgovor">
                                    <i class="material-icons">delete</i>
                                </button>
                            </center>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding-bottom:2px;padding-top:0px">
                            <div class="form-group" style="margin-top:0px;padding-bottom:0px;">
                                <input type="text" style="margin-bottom:0px;padding-bottom:0px;padding-top:0px" class="form-control" name="odg[]" value="Uzdržan" >
                            </div>
                        </td>
                        <td style="padding-bottom:0px;padding-top:0px">
                            <center>
                                <button style="margin-top:2px;margin-bottom:0px" class="btn btn-raised btn-danger btn-xs" id="ukloni_odgovor" name="ukloni_odgovor">
                                    <i class="material-icons">delete</i>
                                </button>
                            </center>
                        </td>
                    </tr>
                    @else
                        @foreach (old('odg') as $odg)
                            <tr>
                                <td style="padding-bottom:2px;padding-top:0px">
                                    <div class="form-group" style="margin-top:0px;padding-bottom:0px;">
                                        <input type="text" style="margin-bottom:0px;padding-bottom:0px;padding-top:0px" class="form-control" name="odg[]" value="{{ $odg }}" >
                                    </div>
                                </td>
                                <td style="padding-bottom:0px;padding-top:0px">
                                    <center>
                                        <button style="margin-top:2px;margin-bottom:0px" class="btn btn-raised btn-danger btn-xs" id="ukloni_odgovor" name="ukloni_odgovor">
                                            <i class="material-icons">delete</i>
                                        </button>
                                    </center>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
        <div class="col-md-4 col-md-offset-1"></div>
    </div>

    <div class="row">
        <div class="col-md-7 checkbox">
          <label>
            @if (old('vise_odg'))
                <input type="checkbox" name="vise_odg" checked>  Može se odabrati više odgovora
            @else
                <input type="checkbox" name="vise_odg">  Može se odabrati više odgovora
            @endif
          </label>
      </div>
    </div>


    <div class="form-group label-floating">
      <label>Kriterijum uspešnosti glasanja:</label>
    </div>

    <div class="row">
        <div class="radio radio-primary" style="margin-left:5px">
            @if (old('criteriumRadios') == "2")
            <label style="color:#555555">
                <input type="radio" name="criteriumRadios" id="criteriumRadios1" value="1"> Broj glasača
            </label>
            <label style="color:#555555">
                <input type="radio" name="criteriumRadios" id="criteriumRadios2" value="2" checked> Broj glasova
            </label>
            @else
            <label style="color:#555555">
                <input type="radio" name="criteriumRadios" id="criteriumRadios1" value="1" checked> Broj glasača
            </label>
            <label style="color:#555555">
                <input type="radio" name="criteriumRadios" id="criteriumRadios2" value="2"> Broj glasova
            </label>
            @endif
        </div>
        <div class="form-group col-md-3 col-sm-3" style="margin-top:5px">
            @if (old('criteriumRadios') == "2")
            <select name="opcija" id="opcija" class="form-control">
                <option value="0">Odaberite odgovor:</option>
                @if (old('odg') == null)
                    <option value="Za">Za</option>
                    <option value="Protiv">Protiv</option>
                    <option value="Uzdržan">Uzdržan</option>
                @else
                    @foreach (old('odg') as $odg)
                        <option value="{{ $odg }}" {{ (old('opcija') == $odg?"selected":"") }}>{{ $odg }}</option>
                    @endforeach
                @endif
            </select>
            @else
            <select name="opcija" id="opcija" class="form-control" style="display:none">
                <option value="0">Odaberite odgovor:</option>
            </select>
            @endif
        </div>
    </div>

    <div class="row">
        <div class="form-group col-md-3 col-sm-3" style="margin-top:0px">
            <select name="relacija" id="relacija" class="form-control">
                <option value="0" selected>Odaberite relaciju:</option>
                <option value="=" {{ (old('relacija') == "="?"selected":"") }}>Jednako</option>
                <option value="<>" {{ (old('relacija') == "<>"?"selected":"") }}>Različito</option>
                <option value=">" {{ (old('relacija') == ">"?"selected":"") }}>Veće</option>
                <option value="<" {{ (old('relacija') == "<"?"selected":"") }}>Manje</option>
                <option value=">=" {{ (old('relacija') == ">="?"selected":"") }}>Veće ili jednako</option>
                <option value="<=" {{ (old('relacija') == "<="?"selected":"") }}>Manje ili jednako</option>
            </select>
        </div>
        <div class="form-group label-floating col-md-3 col-sm-3" style="margin-top:0px">
            <label for="vrednost" class="control-label" style="color:#555555">Unesite vrednost:</label>
            <input type="text" class="form-control" id="vrednost" name="vrednost" value="{{ old('vrednost') }}" >
        </div>
    </div>

    <div class="form-group label-floating">
      <label>Glasači:</label>
    </div>

    <div class="row">
        <div class="form-group col-md-3 col-sm-3" style="margin-top:0px">
            <select name="katedra" id="katedra" class="form-control">
                <option value="0">Odaberite katedru:</option>
                @foreach ($katedre as $katedra)
                <option value="{{ $katedra->id }}">{{ $katedra->name}}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-md-3 col-sm-3" style="margin-top:0px">
            <select name="zvanje" id="zvanje" class="form-control" disabled>
                <option value="0">Odaberite zvanje:</option>
            </select>
        </div>
        <div class="form-group col-md-3 col-sm-3" style="margin-top:0px">
            <select name="korisnik" id="korisnik" class="form-control" disabled>
                <option value="0">Odaberite korisnika:</option>
            </select>
        </div>
        <div class="col-md-3 col-sm-3">
            <button style="margin-left:50%" type="button" id="add_user" class="btn btn-fab btn-fab-mini">+</button>
        </div>
    </div>

    @if (old('glasaci') == null)
    <div class="row" style="margin-top:28px">
        <div class="col-md-6">
            <table class="footable table table-striped" id="tablevoters" style="display:none">
                <thead>
                    <tr>
                        <th>Glasač</th>
                        <th data-hide="phone,tablet"><center>Ukloni</center></th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
    @else
    <div class="row" style="margin-top:28px">
        <div class="col-md-6">
            <table class="footable table table-striped" id="tablevoters">
                <thead>
                    <tr>
                        <th>Glasač</th>
                        <th data-hide="phone,tablet"><center>Ukloni</center></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach (old('glasaci') as $glasac)
                    <tr>
                        <td style="line-height:1.6">{{ $glasac }}</td>
                        <td style="padding-bottom:0px;padding-top:0px"><center>
                            <button style="margin-top:2px;margin-bottom:0px" class="btn btn-raised btn-danger btn-xs" id="ukloni_glasaca" name="ukloni_glasaca"><i class="material-icons">delete</i></button>
                        </center></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <select name="glasaci[]" id="glasaci" multiple style="display:none">
        @if (old('glasaci') != null)
        @foreach (old('glasaci') as $glasac)
            <option value="{{ $glasac }}" selected>{{ $glasac }}</option>        
        @endforeach 
        @endif
    </select>

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

    <div class="center" style="margin-top:28px">
        <button type="submit" class="btn btn-raised btn-success btn-lg" name="pokreni">
            Pokrenite
        </button>

        <button type="submit" class="btn btn-raised btn-danger btn-lg" name="pregled">
            Pregled
        </button>
        <input type="hidden" name="_token" value="{{ Session::token() }}" />    
    </div>
  </form>
@endsection