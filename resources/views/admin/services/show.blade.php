@extends('layout.admin.master')

@section('title' , $feature->name)

@section('content')

    @section('Css')
        <link rel="stylesheet" href="{{asset('assets/plugins/owl-carousel/css/custom.css')}}">
        <link rel="stylesheet" href="{{asset('assets/plugins/owl-carousel/css/owl.transitions.css')}}">
        <link rel="stylesheet" href="{{asset('assets/plugins/owl-carousel/css/owl.carousel.css')}}">
    @endsection

        <section>
            <div class="container-fluid py-4 Collection">
                <div class="card">
                    <div class="d-flex justify-content-center m-2 gap-1">

                        <a href="{{route('services.edit' , $feature->id)}}" class="btn btn-success">Edit</a>

                        <form action="{{route('services.delete' , $feature->id)}}" method="post">
                            @csrf
                            @method('DELETE')

                            <button class="btn btn-danger">Delete</button>
                        </form>

                    </div>
                    <div class="ml-2">
                        <img src="{{ $feature->getFirstMediaUrl('feature_image')}}" height="250px" class="w-100 object-contain mt-5" alt="{{$feature->name}}">
                        <div class="information-collection mt-5">
                            <h1 class="text-center">{{$feature->name . " Feature"}}</span></h1>
                            <p class="text-center">{{$feature->description}}</span>
                        </div>
                        <h3 class="mt-5">Products having this feature</h3>
                        <div class="mt-4 ml-2">
                            <div id="owl-demo" class="related-product__carousel owl-carousel owl-theme">
                                @forelse($feature->products as $product)

                                    <x-admin.product-card-admin :variable="$product" :productID="$product->ProductID" />

                                @empty

                                    <p> No Prooducts associated to this Feature yet </p>
                                    
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

@endsection


@section('Js')
    <script src="{{asset('assets/plugins/owl-carousel/js/owl.carousel.js')}}"></script>
    <script>
        $(document).ready(function() {
    
            $("#owl-demo").owlCarousel({

                items : 10, //10 items above 1000px browser width
                itemsDesktop : [1000,5], //5 items between 1000px and 901px
                itemsDesktopSmall : [900,3], // betweem 900px and 601px
                itemsTablet: [600,2], //2 items between 600 and 0;
                itemsMobile : false,

                //Basic Speeds
                slideSpeed : 200,
                paginationSpeed : 800,
            
                //Autoplay
                autoPlay : true,
                goToFirst : true,
                goToFirstSpeed : 1000,
            
                // Navigation
                navigation : false,
                navigationText : ["prev","next"],
                pagination : true,
                paginationNumbers: true,
            
                // Responsive
                responsive: true,
                items : 5,
                itemsDesktop : [1199,4],
                itemsDesktopSmall : [980,3],
                itemsTablet: [768,2],
                itemsMobile : [479,1]
            
            });
        
        });
    </script>
@stop