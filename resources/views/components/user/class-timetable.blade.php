@props(['timetableData', 'classTypes', 'siteSetting'])

<div class="class-timetable-section spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="section-title">
                    <span>Find Your Time</span>
                    <h2>Class Timetable</h2>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="table-controls">
                    <ul>
                        <li class="active" data-tsfilter="all">All Classes</li>
                        @foreach($classTypes as $type)
                            <li data-tsfilter="{{ strtolower(str_replace(' ', '-', $type)) }}">{{ $type }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="class-timetable">
                    <table>
                        <thead>
                            <tr>
                                <th></th>
                                <th>Monday</th>
                                <th>Tuesday</th>
                                <th>Wednesday</th>
                                <th>Thursday</th>
                                <th>Friday</th>
                                <th>Saturday</th>
                                <th>Sunday</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($timetableData as $timeSlot => $days)
                                <tr>
                                    <td class="class-time">{{ $timeSlot }}</td>
                                    @foreach(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'] as $day)
                                        @if(isset($days[$day]) && $days[$day])
                                            @php $class = $days[$day]; @endphp
                                            <td class="hover-bg ts-meta {{ $loop->iteration % 2 == 0 ? 'dark-bg' : '' }}" data-tsmeta="{{ strtolower(str_replace(' ', '-', $class->type)) }}">
                                                <a href="{{ route('user.classes.show', ['siteSetting' => $siteSetting->slug, 'class' => $class->id]) }}" class="class-link">
                                                    <h5>{{ $class->name }}</h5>
                                                    <span>
                                                        @foreach($class->trainers as $trainer)
                                                            {{ $trainer->name }}{{ !$loop->last ? ', ' : '' }}
                                                        @endforeach
                                                    </span>
                                                </a>
                                            </td>
                                        @else
                                            <td class="{{ $loop->iteration % 2 == 0 ? 'dark-bg' : '' }} blank-td"></td>
                                        @endif
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .class-link {
        display: block;
        text-decoration: none;
        color: inherit;
        transition: all 0.3s ease;
    }
    
    .class-link:hover {
        color: #f36100;
        text-decoration: none;
    }
    .class-timetable table tbody tr td.hover-bg:hover .class-link span
    {
        color:white
    }

    
    .ts-meta:hover {
        background-color: #f36100 !important;
        color: white;
    }
    
    .ts-meta:hover .class-link {
        color: white;
    }
</style>
