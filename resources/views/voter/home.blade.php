@extends('layouts.master_voter')

@section('content')
	
    <div class="btn-group btn-group-justified btn-group-raised">
        <a class="btn active" id="tekuca">Tekuća glasanja</a>
        <a class="btn" id="prosla">Prošla glasanja</a>
    </div>
    <br/>

    <div id="tekuca_glasanja">
        <?php $i = 0; ?>
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
        {!! $votings->render() !!}
    </div>

    <div id="prosla_glasanja" style="display:none">
        <?php $j = 0; ?>
        @foreach ($votings_past as $voting)
        @if ($j % 2 == 0)
        <div class="panel panel-primary">
            <div class="panel-heading">{{ $voting->name }}</div>
            <div class="panel-body">
                <div class="bs-component">
                  <p><b>Opis: </b>{{ $voting->description }}</p>
                  <p><b>Vreme početka:</b> <?php $m = new \Moment\Moment($voting->from); echo $m->format('d-m-Y H:i:s'); ?></p>
                  <p><b>Vreme završetka:</b> <?php $m = new \Moment\Moment($voting->to); echo $m->format('d-m-Y H:i:s'); ?></p>
                  <p><b>Kriterijum uspešnosti glasanja:</b>
                    @if ($past_successes[$j]->answer_id)
                        Broj glasova za odgovor <i>{{ $past_successes[$j]->answer->answer }}</i> {{ $past_successes[$j]->relation }} {{ $past_successes[$j]->value }}
                    @else
                        Broj glasača {{ $past_successes[$j]->relation }} {{ $past_successes[$j]->value }}
                    @endif
                  </p>
                  <p><b>Uspešno: </b><?php if ($voting->status == 1) echo "Da"; else echo "Ne"; ?></p>
                  <p><b>Odgovori: </b><?php echo $past_answers[$j] ?></p>
                </div>
            </div>
        </div>
        @else
        <div class="panel panel-default">
            <div class="panel-heading">{{ $voting->name }}</div>
            <div class="panel-body">
                <div class="bs-component">
                  <p><b>Opis: </b>{{ $voting->description }}</p>
                  <p><b>Vreme početka:</b> <?php $m = new \Moment\Moment($voting->from); echo $m->format('d-m-Y H:i:s'); ?></p>
                  <p><b>Vreme završetka:</b> <?php $m = new \Moment\Moment($voting->to); echo $m->format('d-m-Y H:i:s'); ?></p> 
                  <p><b>Kriterijum uspešnosti glasanja:</b>
                    @if ($past_successes[$j]->answer_id)
                        Broj glasova za odgovor <i>{{ $past_successes[$j]->answer->answer }}</i> {{ $past_successes[$j]->relation }} {{ $past_successes[$j]->value }}
                    @else
                        Broj glasača {{ $past_successes[$j]->relation }} {{ $past_successes[$j]->value }}
                    @endif
                  </p>
                  <p><b>Uspešno: </b><?php if ($voting->status == 1) echo "Da"; else echo "Ne"; ?></p>
                  <p><b>Odgovori: </b><?php echo $past_answers[$j] ?></p>
                </div>
            </div>
        </div>
        @endif
        <?php $j++; ?>
        @endforeach
        {!! $votings_past->render() !!}
    </div>
@endsection