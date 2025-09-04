<select class="form-select form-select-solid" name="{{ $name }}" data-control="select2" data-placeholder="{{ $placeholder ?? 'Select a value' }}" data-kt-repeater="select2" data-allow-clear="true"
        @if (isset($id)) id="{{ $id }}" @endif 
        @if (!isset($notRequired) || !$notRequired ) required @endif
        @if (isset($isDisabled) && $isDisabled) disabled @endif
        @if (isset($changeFuncion)) onchange="{{ $changeFuncion }}" @endif
>
    <option></option>
    
    @foreach ($options as $option)
        @php
            $is_selected = '';
            if (old($name, null) != null) {
                if (is_array(old($name))) {
                    if (in_array($option['value'], old($name))) {
                        $is_selected = 'selected';
                    }
                } elseif (old($name) == $option['value']) {
                    $is_selected = 'selected';
                }
            } elseif (isset($selectedValue)) {
                if (is_array($selectedValue) && in_array($option['value'], $selectedValue)) {
                    $is_selected = 'selected';
                } elseif (!is_array($selectedValue) && $option['value'] == $selectedValue) {
                    $is_selected = 'selected';
                }
            }
        @endphp
        <option value="{{ $option['value'] }}" {{ $is_selected }}>{{ $option['label'] }}</option>
    @endforeach
</select>