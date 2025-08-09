@extends('layout.admin.master')

@section('title' , 'About')

@section('content')

    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <form action="{{ route('about.update', $site->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card mb-4" style="overflow: hidden; height: auto;">
                        <div class="wrapper">
                            <div class="card">
                                <div class="d-flex justify-content-between align-items-center card-header pb-0">
                                    <h6>Edit About Info</h6>
                                    <div class="mb-1">
                                        <a class="btn btn-info" href="{{route('about.index')}}">Cancel</a>
                                        <button type='submit' class="btn btn-success"><i class="fas fa-pen"></i>Update</button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="example2" class="table table-striped table-bordered">
                                            <thead>
                                                <th scope="col" width="10%">#</th>
                                                <th scope="col" width="20%">Information</th>
                                            </thead>
                                            
                                                <tr>
                                                
                                                    <td>Owner</td>
                                                    <td>
                                                        <select name="user_id" class="form-control" required>
                                                            @foreach($users as $id => $name)
                                                                <option value="{{ $id }}" {{ $id == $site->owner_id ? 'selected' : '' }}>{{ $name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Site name</td>
                                                    <td>
                                                        <input class="form-control" type="text" name="title" value="{{$site->title}}">
                                                        @error('title')
                                                            <div class="alert alert-danger">{{$message}}</div>
                                                        @enderror
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>tagline</td>
                                                    <td>
                                                        <input class="form-control" type="text" name="tagline" value="{{$site->tagline}}">
                                                        @error('tagline')
                                                            <div class="alert alert-danger">{{$message}}</div>
                                                        @enderror
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Meta Title</td>
                                                    <td>
                                                        <input class="form-control" type="text" name="meta_title" value="{{$site->meta_title}}">
                                                        @error('meta_title')
                                                            <div class="alert alert-danger">{{$message}}</div>
                                                        @enderror
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Meta description</td>
                                                    <td>
                                                        <textarea class="form-control" name="meta_description">{{$site->meta_description}}</textarea>
                                                        @error('meta_description')
                                                            <div class="alert alert-danger">{{$message}}</div>
                                                        @enderror
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        Market 
                                                    </td>
                                                    <td>
                                                        <div id="market-container">
                                                            @foreach($site->markets as $index => $market)
                                                                <div class="market-item">
                                                                    <input class="form-control" type="text" name="markets[{{ $index }}][market]" value="{{ $market->market }}" required>
                                                                    <textarea class="form-control" name="markets[{{ $index }}][address]" placeholder="Address">{{ $market->address ?? '' }}</textarea>
                                                                    @if($index > 0)
                                                                        <button type="button" class="btn btn-danger remove-market" onclick="removeMarket(this)">Remove</button>
                                                                    @endif
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                        <button type="button" class="btn btn-primary" onclick="addMarket()">Add Market</button>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Phone</td>
                                                    <td>
                                                        <div id="phone-container">
                                                            @foreach($site->phones as $index => $phone)
                                                                <div class="phone-item">
                                                                    <input class="form-control" type="number" name="phones[{{ $index }}][phone]" value="{{ $phone->phone }}" required>
                                                                    @if($index > 0)
                                                                        <button type="button" class="btn btn-danger remove-phone" onclick="removePhone(this)">Remove</button>
                                                                    @endif
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                        <button type="button" class="btn btn-primary" onclick="addPhone()">Add Phone</button>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Redirecting</td>
                                                    <td>
                                                        <input type="hidden" name="enable_redirecting" value="0">
                                                        <input type="checkbox" name="enable_redirecting" value="{{$site->enable_redirecting}}" @checked($site->enable_redirecting == 1) data-bootstrap-switch data-off-color="danger" data-on-color="success">
                                                        @error('enable_redirecting')
                                                            <div class="alert alert-danger">{{$message}}</div>
                                                        @enderror
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Tracking</td>
                                                    <td>
                                                        <input type="hidden" name="enable_tracking" value="0">
                                                        <input type="checkbox" name="enable_tracking" value="{{$site->enable_tracking}}" @checked($site->enable_tracking == 1) data-bootstrap-switch data-off-color="danger" data-on-color="success">
                                                        @error('enable_tracking')
                                                            <div class="alert alert-danger">{{$message}}</div>
                                                        @enderror
                                                    </td>
                                                </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('Js')

    <!-- Bootstap Switch -->
    <script src="{{asset('assets/plugins/bootstrap-switch/js/bootstrap-switch.min.js')}}"></script>
    
    <script>
        $(document).ready(function () {
            $("input[data-bootstrap-switch]").each(function(){
                $(this).bootstrapSwitch('state', $(this).prop('checked'));
            })

            // Handle form submission
            $('form').on('submit', function () {
                // Manually update the hidden input value based on the checkbox state
                if ($('input[name="Redirecting"]').bootstrapSwitch('state')) {
                    $('input[name="Redirecting"]').val(1);
                } else {
                    $('input[name="Redirecting"]').val(0);
                }
            });
        });
    </script>

    <script>
        let marketIndex = {{ $site->markets->count() }};
        let phoneIndex = {{ $site->phones->count() }};

        function addMarket() {
            const container = document.getElementById('market-container');
            const marketItem = document.createElement('div');
            marketItem.className = 'market-item';
            marketItem.innerHTML = `
                <input class="form-control" type="text" name="markets[${marketIndex}][market]" required>
                <textarea class="form-control" name="markets[${marketIndex}][address]" placeholder="Address"></textarea>
                <button type="button" class="btn btn-danger remove-market" onclick="removeMarket(this)">Remove</button>
            `;
            container.appendChild(marketItem);
            marketIndex++;
            toggleRemoveButtons();
        }

        function addPhone() {
            const container = document.getElementById('phone-container');
            const phoneItem = document.createElement('div');
            phoneItem.className = 'phone-item';
            phoneItem.innerHTML = `
                <input class="form-control" type="number" name="phones[${phoneIndex}][phone]" required>
                <button type="button" class="btn btn-danger remove-phone" onclick="removePhone(this)">Remove</button>
            `;
            container.appendChild(phoneItem);
            phoneIndex++;
            toggleRemoveButtons();
        }

        function removeMarket(button) {
            button.parentElement.remove();
            toggleRemoveButtons();
        }

        function removePhone(button) {
            button.parentElement.remove();
            toggleRemoveButtons();
        }

        function toggleRemoveButtons() {
            const marketItems = document.querySelectorAll('.market-item');
            const phoneItems = document.querySelectorAll('.phone-item');

            // Show or hide the remove button based on the number of items
            marketItems.forEach((item, index) => {
                const removeButton = item.querySelector('.remove-market');
                if (removeButton) removeButton.style.display = marketItems.length > 1 ? 'inline-block' : 'none';
            });

            phoneItems.forEach((item, index) => {
                const removeButton = item.querySelector('.remove-phone');
                if (removeButton) removeButton.style.display = phoneItems.length > 1 ? 'inline-block' : 'none';
            });
        }

        // Initial call to set remove button visibility on page load
        toggleRemoveButtons();
    </script>
@stop