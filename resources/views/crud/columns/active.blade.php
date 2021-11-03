<span>
    <span class="{{ $entry->{data_get($column, "name")} ? "badge badge-success" : "badge badge-danger" }}">
        {{ $entry->{data_get($column, "name")} ? "Áno" : "Nie" }}
    </span>
</span>
