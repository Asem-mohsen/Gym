<div class="col-lg-4">
    <div class="ts-item set-bg" data-setbg="{{ $trainer->getFirstMediaUrl('user_images') }}">
        <div class="ts_text">
            <h4>{{ $trainer->name }}</h4>
            <span>{{ 'Gym ' . $trainer->role->name }}</span>
        </div>
    </div>
</div>