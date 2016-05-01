@extends('layouts.master_admin')

@section('content')
	<div class="btn-group btn-group-justified btn-group-raised">
  		<a href="javascript:void(0)" class="btn active">Текућа гласања</a>
  		<a href="javascript:void(0)" class="btn">Прошла гласања</a>
	</div>
	<br/>
	<div class="panel panel-primary">
  		<div class="panel-heading">ГЛАСАЊЕ 1</div>
  		<div class="panel-body">
  		    <div class="bs-component">
          		<div class="progress progress-striped active">
            		<div class="progress-bar" style="width: 10%"></div>
          		</div>
        	</div>
  		</div>
	</div>
	<div class="panel panel-default">
  		<div class="panel-heading">ГЛАСАЊЕ 2</div>
  		<div class="panel-body">
  		    <div class="bs-component">
          		<div class="progress progress-striped active">
            		<div class="progress-bar" style="width: 45%"></div>
          		</div>
        	</div>
  		</div>
	</div>
	<div class="panel panel-primary">
  		<div class="panel-heading">ГЛАСАЊЕ 3</div>
  		<div class="panel-body">
  		    <div class="bs-component">
          		<div class="progress progress-striped active">
            		<div class="progress-bar" style="width: 20%"></div>
          		</div>
        	</div>
  		</div>
	</div>
	<div class="panel panel-default">
  		<div class="panel-heading">ГЛАСАЊЕ 4</div>
  		<div class="panel-body">
  		    <div class="bs-component">
          		<div class="progress progress-striped active">
            		<div class="progress-bar" style="width: 95%"></div>
          		</div>
        	</div>
  		</div>
	</div>
	<div class="panel panel-primary">
  		<div class="panel-heading">ГЛАСАЊЕ 5</div>
  		<div class="panel-body">
  		    <div class="bs-component">
          		<div class="progress progress-striped active">
            		<div class="progress-bar" style="width: 50%"></div>
          		</div>
        	</div>
  		</div>
	</div>
@endsection