<div class="cd-trainer-text">
    <div class="trainer-title">
        <h4>{{ $trainer->name }}</h4>
        <span>Gym Trainer</span>
    </div>
    <div class="trainer-social">
        @if ($trainer->trainerInformation?->facebook_url) <a href="{{ $trainer->trainerInformation?->facebook_url }}"><i class="fa fa-facebook"></i></a> @endif
        @if ($trainer->trainerInformation?->twitter_url) <a href="{{ $trainer->trainerInformation?->twitter_url }}"><i class="fa fa-twitter"></i></a> @endif
        @if ($trainer->trainerInformation?->youtube_url) <a href="{{ $trainer->trainerInformation?->youtube_url }}"><i class="fa fa-youtube-play"></i></a> @endif
        @if ($trainer->trainerInformation?->instagram_url) <a href="{{ $trainer->trainerInformation?->instagram_url }}"><i class="fa fa-instagram"></i></a> @endif
        @if ($trainer->email) <a href="mailto:{{ $trainer->email }}"><i class="fa fa-envelope-o"></i></a> @endif
    </div>
    <p>{{ $trainer->brief_description }}</p>
    @if ($trainer->trainerInformation)
        <ul class="trainer-info">
            <li><span>Age</span>{{ $trainer->trainerInformation?->date_of_birth?->age }}</li>
            <li><span>Weight</span>{{ $trainer->trainerInformation?->weight }}</li>
            <li><span>Height</span>{{ $trainer->trainerInformation?->height }}</li>
        </ul>
        <p>{{ $trainer->trainerInformation?->brief_description }}</p>
    @endif
</div>
