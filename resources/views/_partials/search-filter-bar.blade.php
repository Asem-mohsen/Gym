{{-- 
    Reusable Search and Filter Bar Component
    
    Usage:
    @include('_partials.search-filter-bar', [
        'searchPlaceholder' => 'Search users...',
        'filters' => [
            [
                'name' => 'branch_id',
                'label' => 'Branch',
                'options' => $branches,
                'valueKey' => 'id',
                'labelKey' => 'name',
                'defaultLabel' => 'All Branches'
            ],
            [
                'name' => 'status',
                'label' => 'Status',
                'options' => [
                    ['id' => 'active', 'name' => 'Active'],
                    ['id' => 'inactive', 'name' => 'Inactive']
                ],
                'valueKey' => 'id',
                'labelKey' => 'name',
                'defaultLabel' => 'All Status'
            ]
        ],
        'additionalFilters' => 'custom-filter-content-here'
    ])
--}}

<div class="card-title">
    <div class="d-flex align-items-center gap-3">
        {{-- Search Input --}}
        <div class="d-flex align-items-center position-relative my-1">
            <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5">
                <span class="path1"></span>
                <span class="path2"></span>
            </i>
            <input type="text" data-kt-table-filter="search" class="form-control form-control-solid w-250px ps-12" placeholder="{{ $searchPlaceholder ?? 'Search' }}" />
        </div>
        
        {{-- Filters Form --}}
        @if(!empty($filters))
        <form method="GET" action="{{ request()->url() }}" class="d-flex align-items-center gap-2" id="filter-form">

            <input type="hidden" name="search" id="search-hidden" value="{{ request('search') }}">
            
            @foreach($filters as $filter)
                <select name="{{ $filter['name'] }}" 
                        class="form-control form-control-solid w-200px" 
                        onchange="this.form.submit()">
                    <option value="">{{ $filter['defaultLabel'] ?? 'All ' . ucfirst($filter['label']) }}</option>
                    @foreach($filter['options'] as $option)
                        <option value="{{ $option[$filter['valueKey']] }}" 
                                {{ request($filter['name']) == $option[$filter['valueKey']] ? 'selected' : '' }}>
                            {{ $option[$filter['labelKey']] }}
                        </option>
                    @endforeach
                </select>
            @endforeach
            
            @if(isset($additionalFilters))
                {!! $additionalFilters !!}
            @endif
        </form>
        @endif
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.querySelector('[data-kt-table-filter="search"]');
        const searchHidden = document.getElementById('search-hidden');
        const filterForm = document.getElementById('filter-form');
        
        if (searchInput && searchHidden && filterForm) {
            // Sync search input with hidden field
            searchInput.addEventListener('input', function() {
                searchHidden.value = this.value;
            });
            
            // Set initial value
            searchHidden.value = searchInput.value;
        }
    });
</script>
