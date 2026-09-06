@props([
    'name',
    'label' => null,
    'value' => null,
    'required' => false,
    'minDate' => null,
    'maxDate' => null,
    'placeholder' => 'انتخاب تاریخ',
])

@php
    $displayId = $name . '_display';

    /*
     * $value must be a Jalali date when passed to this component.
     *
     * Example:
     * 1405/06/15
     */
@endphp

<div class="jalali-date-field">

    @if($label)
        <label for="{{ $displayId }}" class="form-label">
            {{ $label }}

            @if($required)
                <span class="text-danger">*</span>
            @endif
        </label>
    @endif

    {{-- Jalali date displayed to the user --}}
    <input
            type="text"
            name="{{ $name }}"
            id="{{ $displayId }}"
            class="form-control @error($name) is-invalid @enderror"
            data-jdp
            autocomplete="off"
            readonly
            placeholder="{{ $placeholder }}"
            value="{{ old($name, $value) }}"

            @if($required)
                required
            @endif

            @if($minDate)
                data-jdp-min-date="{{ $minDate }}"
            @endif

            @if($maxDate)
                data-jdp-max-date="{{ $maxDate }}"
            @endif
    >

    @error($name)
    <div class="invalid-feedback d-block">
        {{ $message }}
    </div>
    @enderror

</div>
