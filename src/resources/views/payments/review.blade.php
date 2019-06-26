@extends ('layouts.default')

@section ('page_title', 'Review Terms & Conditions')

@section ('content')

<div class="container">
	<div class="page-header">
		<h1>
			Review Terms & Conditions of Purchase
		</h1> 
	</div>
	<div class="row">
		<div class="col-xs-12 col-md-8">
			{!! Settings::getPurchaseTermsAndConditions() !!}
			<hr>
			@if (!$nextEventFlag)
				<div class="alert alert-warning">
					<h5>Please be aware you are not purchasing tickets for our next event but instead a future event after that.</h5>
				</div>
			@endif
			<div class="alert alert-warning">
				<h5>By Clicking on Continue to Payment you are agreeing to the Terms and Conditions as set by {!! Settings::getOrgName() !!}</h5>
			</div>
			{{ Form::open(array('url'=>'/payment/post')) }}
				{{ Form::hidden('gateway', $paymentGateway) }}
				<button class="btn btn-default">Continue to Payment</button>
			{{ Form::close() }}
		</div>
		<div class="col-xs-12 col-md-4">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">Order Details</h3>
				</div>
				<div class="panel-body">
					<div class="table-responsive">
						<table class="table table-striped">
							<tbody>
								@foreach ($basket as $item)
									<tr>
										<td>
											<strong>{{ $item->name }}</strong>
										</td>
										<td class="text-right">
											x {{ $item->quantity }}
										</td>
										<td>
											@if ($item->price != null)
												£{{ $item->price }}
												@if ($item->price_credit != null)
													/
												@endif
											@endif
											@if ($item->price_credit != null)
												{{ $item->price_credit }} Credits
											@endif
										</td>
									</tr>
								@endforeach
								<tr>
									<td></td>
									<td class="text-right">
										<strong>Total:</strong>
									</td>
									<td>
										@if ($basket->total != null)
											£{{ $basket->total }}
											@if ($basket->total_credit != null)
												/
											@endif
										@endif
										@if ($basket->total_credit != null)
											{{ $basket->total_credit }} Credits
										@endif
									</td>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

@endsection