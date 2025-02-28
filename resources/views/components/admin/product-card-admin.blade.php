<div class="item product-one__single" style="height:390px" id="Product-{{ $variable->id }}">
    <div class="product-one__image">
        <img src=" {{ $variable->getFirstMediaUrl('product_featured_image') }}" height="270px" alt="{{$variable->translations->name}}" />
    </div>
    <div class="product-one__content">
        <div class="product-one__content-left">
            <h3 class="product-one__title">
                <a href="{{ config('app.frontend_url') }}/product/{{ $variable->id }}" target="_blank" >{{ ucfirst(strtolower($variable->translations->name)) }}</a>
            </h3>
            <p class="description-product">
                {{ 'Brand: ' . $variable->brand->name }} <br>
                <div class="d-flex gap-1">
                    @foreach ($variable->platforms as $platform)
                        <a href="{{ route('Platform.edit' , $platform->id ) }}" title="{{ $platform->name . " Platform" }}">
                            <img src="{{ $platform->getFirstMediaUrl('platform-image') }}" class="Platform-flex-img">
                        </a>
                    @endforeach
                </div>
            </p>
            <div class="product-one__content-right d-flex align-items-center justify-content-between">
                @if($variable->isOnSale())
                    <div class="d-grid gap-1">
                        <p class="product-one__text">{{ $priceSale->sale_price . ' EGP' }}</p>
                        <p class="product-one__text" style="text-decoration: line-through; font-size:14px">{{ $variable->price . ' EGP' }}</p>
                    </div>
                @else
                    <p class="product-one__text">{{ $variable->price . ' EGP' }}</p>
                @endif
                @if($variable->quantity <= 0)
                    <p class="OutStock">{{ __('OutofStock') }}</p>
                @else
                    <a href="{{ config('app.frontend_url') }}/product/{{ $variable->id }}" target="_blank"  data-placement="top" class="product-one__cart-btn"><i class="fa-solid fa-search text-white"></i></a>
                @endif
            </div>
        </div>
    </div>
</div>
