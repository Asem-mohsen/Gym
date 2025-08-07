<a href="{{ $route }}" class="btn btn-icon-{{ $colorClass ?? 'success' }} btn-sm" @isset($title) title="{{ $title }}" @endisset>
    <i class="{{ $iconClasses }}"></i>
</a>