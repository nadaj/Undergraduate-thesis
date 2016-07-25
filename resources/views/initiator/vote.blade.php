@extends('layouts.master_initiator')

@section('content')
<div class="well bs-component">
    <form class="form-horizontal" method="post" action="{{ route('initiator.vote') }}">
        <fieldset>
            <center>    
                <legend>{{ $voting->name }}</legend>
                <div class="form-group" style="margin-top: 0; display:inline-block; text-align: left">
                    @if (! $voting->multiple_answers)
                        @foreach ($answers as $answer)
                            <div class="radio radio-primary">
                                <label style="color:#555555">
                                    <input type="radio" name="optionsRadios" id="optionsRadios1"
                                     value="{{ $answer->id }}">
                                    {{ $answer->answer }}
                                </label>
                            </div>
                        @endforeach
                    @else
                        @foreach ($answers as $answer)
                        <div class="checkbox">
                            <label style="color:#555555">
                                <input type="checkbox" name="optionsCheckbox[]" value="{{ $answer->id }}" />
                                {{ $answer->answer }}
                            </label>
                        </div>
                        @endforeach
                    @endif
                </div>
                <div>
                    <button type="submit" style="margin-top:22px" class="btn btn-raised btn-default btn-lg" name="submit">
                        Glasajte
                    </button>
                </div>
            </center>
            <div>
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
</div>
@endsection