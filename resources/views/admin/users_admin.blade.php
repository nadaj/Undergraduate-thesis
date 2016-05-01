@extends('layouts.master_admin')

@section('content')
	<div class="btn-group btn-group-justified btn-group-raised">
  		<a href="javascript:void(0)" class="btn active">Постојећи корисници</a>
  		<a href="javascript:void(0)" class="btn">Неодобрени корисници</a>
	</div>
	<br/>
	<table class="table table-striped table-hover">
  		<thead>
  			<!-- SMISLI DA BUDE HIDDEN KAD SE SMANJI -->
  			<tr>
			    <th></th>
			    <th>Име и презиме</th>
			    <th>Е-mail адреса</th>
			    <th>Звање</th>
			    <th>Катедра</th>
			</tr>
  		</thead>
  		<tbody>
		  	<tr class="active">
			    <td>1</td>
			    <td>Петар Петровић</td>
			    <td>pera@gmail.com</td>
			    <td>Асистент</td>
			    <td>ИР</td>
			    <td>
			    	<button type="button" class="btn btn-fab btn-fab-mini">
          				<i class="material-icons">close</i>
        			</button>
    			</td>
		  	</tr>
		  	<tr class="active">
			    <td>2</td>
			    <td>Александра Алексић</td>
			    <td>alex@gmail.com</td>
			    <td>Сарадник у настави</td>
			    <td>OС</td>
		    	<td>
			    	<button type="button" class="btn btn-fab btn-fab-mini">
          				<i class="material-icons">close</i>
        			</button>
    			</td>
		  	</tr>
		  	<tr class="danger">
			    <td>3</td>
			    <td>Марко Марковић</td>
			    <td>marko@gmail.com</td>
			    <td>Редовни професор</td>
			    <td>СИ</td>
		    	<td>
			    	<button type="button" class="btn btn-fab btn-fab-mini">
          				<i class="material-icons">check</i>
        			</button>
    			</td>
		  	</tr>
		  	<tr class="active">
			    <td>4</td>
			    <td>Лазар Лазић</td>
			    <td>laza@gmail.com</td>
			    <td>Асистент</td>
			    <td>ОГ</td>
		    	<td>
			    	<button type="button" class="btn btn-fab btn-fab-mini">
          				<i class="material-icons">close</i>
        			</button>
    			</td>
		  	</tr>
		  	<tr class="active">
			    <td>5</td>
			    <td>Јанко Јанковић</td>
			    <td>janko@gmail.com</td>
			    <td>Асистент</td>
			    <td>СИ</td>
		    	<td>
			    	<button type="button" class="btn btn-fab btn-fab-mini">
          				<i class="material-icons">close</i>
        			</button>
    			</td>
		  	</tr>
		  	<tr class="danger">
			    <td>6</td>
			    <td>Вук Вуковић</td>
			    <td>vuk@gmail.com</td>
			    <td>Сарадник у настави</td>
			    <td>ОФ</td>
		    	<td>
			    	<button type="button" class="btn btn-fab btn-fab-mini">
          				<i class="material-icons">check</i>
        			</button>
    			</td>
		  	</tr>
		</tbody>
	</table>
	<br/>
	<h2>Неодобрени корисници</h2><br/>
	<table class="table table-striped table-hover">
  		<thead>
  			<!-- SMISLI DA BUDE HIDDEN KAD SE SMANJI -->
  			<tr>
			    <th></th>
			    <th>Име и презиме</th>
			    <th>Е-mail адреса</th>
			    <th>Звање</th>
			    <th>Катедра</th>
			    <th>Иницијатор</th>
			</tr>
  		</thead>
  		<tbody>
		  	<tr class="active">
			    <td>1</td>
			    <td>Петар Петровић</td>
			    <td>pera@gmail.com</td>
			    <td>Асистент</td>
			    <td>ИР</td>
			    <td>
				    <div class="togglebutton">
		              	<label>
		               		<input type="checkbox">
		              	</label>
	            	</div>
	            </td>
			    <td>
			    	<button type="button" class="btn btn-fab btn-fab-mini">
          				<i class="material-icons">close</i>
        			</button>
    			</td>
		  	</tr>
		  	<tr class="active">
			    <td>2</td>
			    <td>Александра Алексић</td>
			    <td>alex@gmail.com</td>
			    <td>Сарадник у настави</td>
			    <td>OС</td>
			    <td>
				    <div class="togglebutton">
		              	<label>
		               		<input type="checkbox">
		              	</label>
	            	</div>
	            </td>
		    	<td>
			    	<button type="button" class="btn btn-fab btn-fab-mini">
          				<i class="material-icons">close</i>
        			</button>
    			</td>
		  	</tr>
		  	<tr class="danger">
			    <td>3</td>
			    <td>Марко Марковић</td>
			    <td>marko@gmail.com</td>
			    <td>Редовни професор</td>
			    <td>СИ</td>
			    <td>
				    <div class="togglebutton">
		              	<label>
		               		<input type="checkbox">
		              	</label>
	            	</div>
	            </td>
		    	<td>
			    	<button type="button" class="btn btn-fab btn-fab-mini">
          				<i class="material-icons">check</i>
        			</button>
    			</td>
		  	</tr>
		  	<tr class="active">
			    <td>4</td>
			    <td>Лазар Лазић</td>
			    <td>laza@gmail.com</td>
			    <td>Асистент</td>
			    <td>ОГ</td>
			    <td>
				    <div class="togglebutton">
		              	<label>
		               		<input type="checkbox">
		              	</label>
	            	</div>
	            </td>
		    	<td>
			    	<button type="button" class="btn btn-fab btn-fab-mini">
          				<i class="material-icons">close</i>
        			</button>
    			</td>
		  	</tr>
		  	<tr class="active">
			    <td>5</td>
			    <td>Јанко Јанковић</td>
			    <td>janko@gmail.com</td>
			    <td>Асистент</td>
			    <td>СИ</td>
			    <td>
				    <div class="togglebutton">
		              	<label>
		               		<input type="checkbox">
		              	</label>
	            	</div>
	            </td>
		    	<td>
			    	<button type="button" class="btn btn-fab btn-fab-mini">
          				<i class="material-icons">close</i>
        			</button>
    			</td>
		  	</tr>
		  	<tr class="danger">
			    <td>6</td>
			    <td>Вук Вуковић</td>
			    <td>vuk@gmail.com</td>
			    <td>Сарадник у настави</td>
			    <td>ОФ</td>
			    <td>
				    <div class="togglebutton">
		              	<label>
		               		<input type="checkbox">
		              	</label>
	            	</div>
	            </td>
		    	<td>
			    	<button type="button" class="btn btn-fab btn-fab-mini">
          				<i class="material-icons">check</i>
        			</button>
    			</td>
		  	</tr>
		</tbody>
	</table>
@endsection