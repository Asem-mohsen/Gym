{!! getScripts() !!}

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@vite(['resources/js/app.js'])

@include('components.toastr')

@yield('js')