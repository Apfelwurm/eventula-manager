@extends ('layouts.default')

@section ('page_title', Settings::getOrgName() . ' Shop | Orders')

@section ('content')
			
<div class="container">
	<div class="page-header">
		<h1>
			Shop - Orders
		</h1>
	</div>
	@include ('layouts._partials._shop.navigation')
	<table width="100%" class="table table-striped table-hover">
		<thead>
			<tr>
				<th>#</th>
				<th>User</th>
				<th>Name</th>
				<th>Status</th>
				<th>Payment Type</th>
				<th>Ordered At</th>
				<th>Basket</th>
			</tr>
		</thead>
		<tbody class="table-row odd gradeX">
			@foreach ($orders as $order)
				@php
					$statusColor = '';
					if ($order->status == 'CANCELLED') {
						$statusColor = 'warning';
					}elseif($order->status == 'EVENT') {
						$statusColor = 'warning';
					}elseif($order->status == 'ERROR') {
						$statusColor = 'danger';
					}elseif($order->status == 'COMPLETE') {
						$statusColor = 'success';
					}
				@endphp
				<tr class="{{ $statusColor }}">
					<td>{{ $order->id }}</td>
					<td>{{ $order->purchase->user->username }}</td>
					<td>{{ $order->purchase->user->firstname }} {{ $order->purchase->user->surname }}</td>
					<td>{{ $order->status }}</td>
					<td>{{ $order->purchase->type }}</td>
					<td>{{ $order->created_at }}</td>
					<td>
						@if ($order->purchase->order != null)
							@foreach ($order->purchase->order->items as $item)
								@if ($item->item)
									{{ $item->item->name }}
								@endif 
								 - x {{ $item->quantity }}
								 <br>
							 	@if ($item->price != null)
									{{ Settings::getCurrencySymbol() }}{{ $item->price * $item->quantity }}
									@if ($item->price_credit != null && Settings::isCreditEnabled())
										/
									@endif
								@endif
								@if ($item->price_credit != null && Settings::isCreditEnabled())
									{{ $item->price_credit * $item->quantity }} Credits
								@endif
								@if (!$loop->last)
									<hr>
								@endif
							@endforeach
						@endif
					</td>
				</tr>
			@endforeach
		</tbody>
	</table>
</div>

@endsection
