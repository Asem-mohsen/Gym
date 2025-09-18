<select name="{{isset($noArray) && $noArray ? $name : $name . '[]'}}" class="form-select form-select-solid {{ isset($additionalClass) ? $additionalClass : '' }}"  data-control="select2" data-placeholder="Select a value" data-allow-clear="true" multiple="multiple" @if(isset($id)) id="{{ $id }}" @endif @if(!isset($notRequired)) required @endif @if(isset($isDisabled) && $isDisabled) disabled @endif @if (isset($changeFuncion)) onchange="{{ $changeFuncion }}" @endif @if(isset($dataKtRepeater)) data-kt-repeater="{{ $dataKtRepeater }}" @endif>
    @foreach($options as $option)
        @php
            $is_selected = '';
            $oldValues = old($name);
            
            if($oldValues !== null && is_array($oldValues)){
                if (in_array($option['value'], $oldValues)) {
                    $is_selected = 'selected';
                }
            }
            elseif(isset($values)){
                if (!isset($valuesAreArray)) {
                    if (is_object($values) && method_exists($values, 'pluck')) {
                        $values = $values->pluck('id')->toArray();
                    } elseif (is_array($values)) {
                        $values = $values;
                    } else {
                        $values = [];
                    }
                }
    
                if(in_array($option['value'], $values)) $is_selected = 'selected';
            }
        @endphp
        <option value="{{ $option['value'] }}" {{ $is_selected }}>{{ $option['label'] }}</option>
    @endforeach
</select>

