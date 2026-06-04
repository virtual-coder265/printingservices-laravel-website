@props([
    'method' => 'post',
])

@php
    if ($attributes->has('wire:submit') && ! $attributes->has('wire:submit.prevent')) {
        $attributes = $attributes
            ->except('wire:submit')
            ->merge(['wire:submit.prevent' => $attributes->get('wire:submit')]);
    }
@endphp

<form
    method="{{ $method }}"
    x-data="{ isProcessing: false }"
    x-on:submit.prevent="if (isProcessing) { return }"
    x-on:form-processing-started="isProcessing = true"
    x-on:form-processing-finished="isProcessing = false"
    {{ $attributes->class(['fi-form grid gap-y-6']) }}
>
    {{ $slot }}
</form>
