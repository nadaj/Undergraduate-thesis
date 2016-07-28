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
                  <p><b>Vreme početka:</b> <?php $m = new \Moment\Moment($voting->from); echo $m->format('d-m-Y H:i:s'); ?></p>
                  <p><b>Vreme završetka:</b> <?php $m = new \Moment\Moment($voting->to); echo $m->format('d-m-Y H:i:s'); ?></p>
                  <p><b>Procenat onih koji su glasali: </b>{{ $proc[$i] }}%</p>
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
                  <p><b>Vreme početka:</b> <?php $m = new \Moment\Moment($voting->from); echo $m->format('d-m-Y H:i:s'); ?></p>
                  <p><b>Vreme završetka:</b> <?php $m = new \Moment\Moment($voting->to); echo $m->format('d-m-Y H:i:s'); ?></p>
                  <p><b>Procenat onih koji su glasali: </b>{{ $proc[$i] }}%</p>
                  <br/> 
                    <div class="progress progress-striped active">
                        <div class="progress-bar" style="width: {{ $progresses[$i] }}%"></div>
                    </div>
                </div>
            </div>
        </div>
        @endif
        <?php $i++; ?>
        
    <div class="modal fade" id="deletevotingmodal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Brisanje glasanja</h4>
                </div>

                <div class="modal-body">
                    <p>Da li ste sigurni da želite da obrišete glasanje?</p>
                    <input type="hidden" name="voting_id" value="{{ $voting->id }}" />
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Odustanite</button>
                    <button type="button" id="nastavi_sa_brisanjem" class="btn btn-primary">Obrišite</button>
                </div>
            </div>
        </div>
    </div>
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
                  <p><b>Vreme početka:</b> <?php $m = new \Moment\Moment($voting->from); echo $m->format('d-m-Y H:i:s'); ?></p>
                  <p><b>Vreme završetka:</b> <?php $m = new \Moment\Moment($voting->to); echo $m->format('d-m-Y H:i:s'); ?></p>
                  <p><b>Uspešno: </b><?php if ($voting->status == 1) echo "Da"; else echo "Ne"; ?></p>
                  <p><b>Odgovori: </b><?php echo $past_answers[$j] ?></p>
                  <button data-toggle="modal" data-target="#deletevotingmodal" class="btn btn-danger btn-lg btn-block btn-raised"><i class="material-icons">delete</i></button>
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
                  <p><b>Uspešno: </b><?php if ($voting->status == 1) echo "Da"; else echo "Ne"; ?></p>
                  <p><b>Odgovori: </b><?php echo $past_answers[$j] ?></p>
                  <button data-toggle="modal" data-target="#deletevotingmodal" class="btn btn-danger btn-lg btn-block btn-raised"><i class="material-icons">delete</i></button>
                </div>
            </div>
        </div>
        @endif
        <?php $j++; ?>
        @endforeach
        {!! $my_votings_past->render() !!}
    </div>

    <input type="hidden" name="_token" value="{{ Session::token() }}" />
@endsection