@extends('template/main')

@section('content')
<div class="bg-theme-1 bg-header">
    <div class="container text-center text-white">
        {{-- <h3>{{ $questions->packet->name }}</h3> --}}
    </div>
</div>
<div class="custom-shape-divider-top-1617767620">
    <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
        <path d="M0,0V7.23C0,65.52,268.63,112.77,600,112.77S1200,65.52,1200,7.23V0Z" class="shape-fill"></path>
    </svg>
</div>
<div class="container main-container">
    @if($selection != null)
	    @if(strtotime('now') < strtotime($selection->test_time))
	    <div class="row">
	        <!-- Alert -->
	        <div class="col-12 mb-2">
	            <div class="alert alert-danger fade show text-center" role="alert">
	                Tes akan dilaksanakan pada tanggal <strong>{{ \Ajifatur\Helpers\DateTimeExt::full($selection->test_time) }}</strong> mulai pukul <strong>{{ date('H:i:s', strtotime($selection->test_time)) }}</strong>.
	            </div>
	        </div>
	    </div>
	    @endif
    @endif
    @if($selection == null || ($selection != null && strtotime('now') >= strtotime($selection->test_time)))
	<div id="questmsdt" class="row" style="margin-bottom:100px">
		<div class="col-12 col-md-4 co mb-md-0">
			<div class="card">
				<div class="card-header fw-bold text-center">Navigasi Soal</div>
				<div class="card-body">
					<form id="form" method="post" action="/tes/{{ $path }}/store">
						@csrf
						<input type="hidden" id="path" name="path" value="{{ $path }}">
						<input type="hidden" id="packet_id" name="packet_id" value="{{ $soal->id }}">
						<input type="hidden" id="test_id" name="test_id" value="{{ $test->id }}"> 
						<input type="hidden" id="jumlah_soal" name="jumlah_soal" class="jumlah_soal" value="{{ $soal->amount }}"> 
						@if (request('part') == null && $part == null)
							<input type="hidden" name="part" class="part" id="part" value="1">
						@else
							<input type="hidden" name="part" class="part" id="part" value="{{ $part }}">
						@endif
						
						{{-- //add html button nav and input --}}
						@for ($i = 1; $i <= $soal->amount; $i++)
							<a name="buttonNav" style="font-size:0.75rem;width:3.5rem;border-radius:0.2rem" class="nav_soal btn btn-sm border-warning mt-1" id="button{{ $i }}">{{ $i }}</a>
							<input type="hidden" name="jawaban[{{ $i }}]" class="jawaban{{ $i }}" id="jawaban{{ $i }}{{ $part }}" value="">
							
						@endfor

					</form>
				</div>
			</div>
		</div>
	    <div class="col-12 col-md-8">
			@include('test.komponen.form2')
    	</div>
	</div>
	<nav class="navbar navbar-expand-lg fixed-bottom navbar-light bg-white shadow">
		<div class="container">
            @include('test.komponen.soalSubmitPart')
		</div>
	</nav>
	{{-- <div class="modal fade" id="tutorialModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
	    	<div class="modal-content" style="height: 60vh">
	      		<div class="modal-header">
	        		<h5 class="modal-title" id="exampleModalLabel">
	        			<span class="bg-warning rounded-1 text-center px-3 py-2 me-2"><i class="fa fa-lightbulb-o text-dark" aria-hidden="true"></i></span> 
	        			Tutorial Tes
	        		</h5>
	        		<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
	      		</div>
		      	<div class="modal-body">

		      	</div>
	      		<div class="modal-footer">
	        		<button type="button" class="btn btn-primary text-uppercase " data-bs-dismiss="modal">Mengerti</button>
	      		</div>
	    	</div>
	  	</div>
	</div> --}}
    @endif
</div>


@endsection

@section('js-extra')
<script type="text/javascript" src="{{ asset('assets/js/soalGenerate.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/cf3kpk.js') }}"></script>
{{-- <script type="text/javascript" src="{{ asset('assets/js/answered.js') }}"></script> --}}
<script type="text/javascript">
		
</script>
@endsection

@section('css-extra')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/option.css') }}">
<style type="text/css">

</style>
@endsection