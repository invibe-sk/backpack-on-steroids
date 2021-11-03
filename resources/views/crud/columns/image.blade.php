{{-- regular object attribute --}}
<span>
    @includeWhen(!empty($column['wrapper']), 'crud::columns.inc.wrapper_start')

        @php $media = $entry->getMedia(data_get($column, "name"), ['lang' => app()->getLocale()])->first() ?? $entry->getFirstMedia(data_get($column, "name")); @endphp

        {{ $media?->img()?->lazy()?->attributes(['style' => 'display: inline-block; max-width: 64px; max-height: 32px; width: auto; height: auto;']) }}

    @includeWhen(!empty($column['wrapper']), 'crud::columns.inc.wrapper_end')
</span>
