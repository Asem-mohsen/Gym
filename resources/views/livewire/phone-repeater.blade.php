<div class="row d-flex flex-wrap mb-5 gap-2">
    <label class="form-control-label w-100">Phone</label>
    
    <div class="d-flex flex-wrap w-100">
        @foreach($phones as $index => $phone)
            <div class="col-md-4 d-flex align-items-center">
                <input type="text" class="form-control" name="phones" wire:model="phones.{{ $index }}.phone_number" required>
                @if($index > 0)
                    <button class="btn btn-danger remove-phones" wire:click.prevent="removePhone({{ $index }})">X</button>
                @endif
            </div>
        @endforeach
    </div>

    @if(count($phones) < 3)
        <button class="btn btn-primary mt-2" wire:click.prevent="addPhone">+ Add</button>
    @endif
</div>

