<nav class="navbar navbar-expand-md navbar-dark bg-dark navbar-shop">
	<div class="container-fluid">
		<div id="navbar" class="navbar-collapse collapse">
			<ul class="nav navbar-nav">
				@foreach ($allCategories as $c)
					@if (isset($category) && $c->id == $category->id)
						<li class="nav-item active"><a class="nav-link" href="/shop/{{ $c->slug }}">{{ $c->name }}</a></li>
					@else
						<li class="nav-item"><a class="nav-link" href="/shop/{{ $c->slug }}">{{ $c->name }}</a></li>
					@endif
				@endforeach
			</ul>
			</ul>
			<ul class="nav navbar-nav ml-auto">
			  <li class="nav-item"><a class="nav-link" href="/shop/basket">Basket</a></li>
			  <li class="nav-item"><a class="nav-link" href="/payment/checkout">Checkout</a></li>
			  <li class="nav-item"><a class="nav-link" href="/shop/orders">Orders</a></li>
			</ul>
		</div>
	</div>
</nav>
@if (Settings::getShopStatus() == 'CLOSED')
	<div class="row">
		<div class="col-12">
			<div class="alert alert-danger"><strong>{{ Settings::getShopClosedMessage() }}</strong></div>
		</div>
	</div>
@endif