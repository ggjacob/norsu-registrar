@extends('master')

@section('js')
	
	<script type="text/javascript">

		$(document).ready(function(){
			$("a[href$='#finish']").click(function () {
				$("form").submit();
			});

			$("form").submit(function(){
				var count = $("form input:checked").length;

				if(count) return true;

				$(".error-box").html("<h2>You havent Selected Any Documents.</h2>").removeClass("hide").hide().fadeIn(500);
				return false;
			});

			$(".doc-sel").click(function(){
				var doc_id = $(this).data('check');
				var table = $(this).data('table');

				$("#"+doc_id).click();


				if($("#"+doc_id).prop('checked'))
					$("#table_"+table+" tbody").append('<tr id="t_'+doc_id+'"><td>'+$(this).html()+'</td></tr>');
				else
					$("#t_"+doc_id).remove();

				var count = $("#table_requested tbody tr").length;

				if(count > 1) $(".t-warning").hide(); 
				else $(".t-warning").show();
				
			});


			$(".show-others").click(function(){
				$(".others").parent().toggle(400);
				$(this).html($(this).html() == 'Show Others' ? 'Hide Others' : 'Show Others');
			})


		});

	</script>
	
@stop


@section('c')
	
<div class="jumbotron">
 	<h1> {{ $stud->fullName() }} <span class="text-primary"> ({{ $stud->studentID }})</span></h1>
 	<p> {{ $stud->course }} | Last year attended: {{ $stud->lya }}  </p>
</div>

<div class="alert alert-warning hide error-box"></div>

<div class="wizard col-md-12">
    <h1>Select Document</h1>
    <div>
    	

	
	@if(count(Document::others()->get()))
		<div class=" pull-right" style="margin-top:5px;">
			<button class="btn btn-default btn-sm show-others" data-toggle="button" >Show Others</button>
		</div>
	@endif

	<h3> <i class="glyphicon glyphicon-book"></i> Select a Document</h3>

	<div class="row">

	@foreach($docs as $d)

		<div class="col-md-4" style="margin-top:5px; @if($d->others) display:none; @endif">
			<button class="btn {{ $d->others ? 'btn-warning others' : 'btn-primary' }} btn-block btn-lg doc-sel"  data-check="{{ 'doc'.$d->id }}" data-table="requested" data-toggle="button">{{ $d->name }}</button>
		</div>

	@endforeach
	
	</div>

    </div>
 

    <h1>Select Reason</h1>
    <div>
    	
		<h3> <i class="glyphicon glyphicon-book"></i> Select a Reason</h3>
		<div class="row">

			@foreach($reasons as $r)

				<div class="col-md-4" style="margin-top:5px;">
					<button class="btn btn-primary btn-block btn-lg doc-sel"  data-check="{{ 're'.$r->id }}" data-table="reason" data-toggle="button">{{ $r->name }}</button>
				</div>

			@endforeach
			
		</div>

    </div>

    <h1>Wrapping Up</h1>
    <div>
    	
    	<h2>Document Requested</h2>
    	<table class="table" id="table_requested">
    		<tbody>
    			<tr class="t-warning">
    				<td> <div class="alert alert-warning">You havent Selected any Documents</div> </td>
    			</tr>
    		</tbody>
    	</table>

    	<h2>Reasons</h2>
    	<table class="table" id="table_reason">
    		<tbody>
    			<tr class="t-warning">
    				<td> <div class="alert alert-warning">You havent Selected Any</div> </td>
    			</tr>
    		</tbody>
    	</table>

    </div>
</div>

	
{{ Form::open(['url'=>'app/send-request/'.$stud->id]) }}

@foreach($docs as $d)
	
	{{ Form::checkbox('docs[]', $d->id, null, ['id'=>'doc'.$d->id]) }}
	
@endforeach

@foreach($reasons as $r)
	
	{{ Form::checkbox('reasons[]', $r->id, null, ['id'=>'re'.$r->id]) }}
	
@endforeach

{{ Form::close() }}

@stop