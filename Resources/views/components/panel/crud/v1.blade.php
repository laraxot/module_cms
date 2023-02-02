<x-pagination :rows="$rows" />
@php
    if (!isset($_panel) && isset($panel)) {
        $_panel = $panel;
    }
@endphp
<div class="table-responsive">
    {{--  <h3> $rows->count() </h3> --}}
    <table class="table table-hover table-nowrap">
        @foreach ($rows as $row)
            @php
                $row_panel = $_panel->newPanel($row);
            @endphp
            @if ($loop->first)
                <thead class="table-light">
                    <tr>
                        @foreach ($fields as $field)
                            <th scope="col">{{ $field->name }}</th>
                        @endforeach
                        <th></th>
                    </tr>
                </thead>
                <tbody>
            @endif
            <tr>
                @foreach ($fields as $field)
                    <td>
                        <x-input.freeze :field="$field" :row="$row" />
                        @if ($loop->first)
                            <x-panel.buttons.actions.item :panel="$row_panel" />
                        @endif
                    </td>
                @endforeach
                <td class="text-end">
                    <x-panel.buttons.crud :panel="$row_panel" />
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
<x-pagination :rows="$rows" />
