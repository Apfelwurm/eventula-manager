@extends ('layouts.default')

@section ('page_title', Settings::getOrgName() . ' Shop | Basket')

@section ('content')
			
<div class="container">
	<div class="page-header">
		<h1>
			Shop - Basket
		</h1>
	</div>
	@include ('layouts._partials._shop.navigation')
	<div class="row">
		<div class="col-xs-12 col-md-12">
			<div class="table-responsive">
				@if (isset($basket) && strtolower($basket) != 'empty')
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
									<td class="text-right">
										@if ($item->price != null)
											£{{ $item->price }}
											@if ($item->price_credit != null)
												/
											@endif
										@endif
										@if ($item->price_credit != null)
											{{ $item->price_credit }} Credits
										@endif
										Each
									</td>
									<td class="text-right">
										@if ($item->price != null)
											£{{ $item->price * $item->quantity }}
											@if ($item->price_credit != null)
												/
											@endif
										@endif
										@if ($item->price_credit != null)
											{{ $item->price_credit * $item->quantity }} Credits
										@endif
									</td>
								</tr>
							@endforeach
							<tr>
								<td></td>
								<td></td>
								<td></td>
								<td class="text-right">
									<strong>Total:</strong>
									@if ($basket->total != 0)
										£{{ $basket->total }}
										@if ($basket->total_credit != 0)
											/
										@endif
									@endif
									@if ($basket->total_credit != 0)
										{{ $basket->total_credit }} Credits
									@endif
								</td>
							</tr>
						</tbody>
					</table>
					<a href="/payment/checkout">
						<button type="button" class="btn btn-sm btn-success">Checkout</button>
					</a>
				@else
					<p>Basket is Empty</p>
				@endif
			</div>
		</div>
	</div>
</div>

@endsection
