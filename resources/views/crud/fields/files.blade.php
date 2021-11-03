<div class="form-group col-sm-12">
    <div class="vue">
        <files
            @if(old(data_get($field, "name"))) :old-value="{{ old(data_get($field, "name")) }}" @endif
            name="{{ data_get($field, "name") }}"
            label="{{ data_get($field, "label") }}"
            @if(isset($entry)) class-name="{{ get_class($entry) }}" @endif
            @if(isset($entry)) :class-id="{{ $entry->id }}" @endif
            :crop="{{ json_encode(data_get($field, "options.crop", false)) }}"
            :preview="{{ json_encode(data_get($field, "options.preview", false)) }}"
            :aspect-ratio="{{ json_encode(data_get($field, "options.aspect-ratio")) }}"
            :translatable="{{ json_encode(data_get($field, "options.translatable", false)) }}"
            :filesize="{{ json_encode(data_get($field, "options.filesize", 10)) }}"
            lang="{{ request()->input('locale', app()->getLocale()) }}"
            accept="{{ data_get($field, "options.accept") }}"
            @if(data_get($field, "options.label.singular")) singular="{{ data_get($field, "options.label.singular") }}" @endif
            @if(data_get($field, "options.label.plural")) plural="{{ data_get($field, "options.label.plural") }}" @endif
            :multiple="{{ json_encode(data_get($field, "options.multiple", false)) }}">
        </files>
    </div>
</div>
