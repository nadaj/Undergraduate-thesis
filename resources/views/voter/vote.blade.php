@extends('layouts.master_voter')

@section('content')
<form class="form-horizontal"  method="post" action="{{ route('voter.vote') }}">
    <fieldset>
        <legend class="col-md-offset-1">{{ $voting->name }}</legend>
        <!-- <div class="col-md-offset-1 ">
            {{ $voting->description }}
        </div> -->
        <div class="form-group" style="margin-top: 0;"> <!-- inline style is just to demo custom css to put checkbox below input above -->
            <div class="col-md-offset-1 col-md-10">
                @foreach ($answers as $answer)
                    <div class="radio radio-primary">
                        <label style="color:#555555">
                            <input type="radio" name="optionsRadios" id="optionsRadios1"
                             value="{{ $answer->id }}">
                            {{ $answer->answer }}
                        </label>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="col-md-offset-1">
            <button type="submit" class="btn btn-raised btn-default btn-lg" name="submit">
                Glasajte
            </button>
        </div>
        <div class="col-md-offset-1">
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
        </div>
        <input type="hidden" name="_token" value="{{ Session::token() }}" />
        <input type="hidden" name="ticket" value="{{ $ticket_id }}" />
    </fieldset>
</form>
@endsection