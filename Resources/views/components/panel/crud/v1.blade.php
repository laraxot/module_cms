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
<<<<<<< HEAD
                        @if ($_panel->checkActions()->count() > 0)
                            {{-- {{ dddx($_panel->checkActions()) }} --}}
                            @foreach ($panel->checkActions() as $act)
                                <x-button.action :action="$act"></x-button.action>
                            @endforeach
=======
                        @if ($_panel->getActions('check')->count() > 0)
>>>>>>> 72ff2be (up)
                            <th>select models</th>
                        @endif
                        @foreach ($fields as $field)
                            <th scope="col">{{ $field->name }}</th>
                        @endforeach
                        <th></th>
                    </tr>
                </thead>
                <tbody>
            @endif
            <tr>
                @if ($_panel->getActions('check')->count() > 0)
                    <td>
                        {{ Form::checkbox('checkbox_model_id[]', $row->id, false) }}
                        {{-- <x-input type="checkbox" name="checkbox_model_id[]" :options="[$row->id]" /> --}}
                    </td>
                @endif
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
<div class="card-footer border-0 py-5">
    <span class="text-muted text-sm">Showing {{ $rows->count() }} items out of {{ $rows->total() }} results
        found</span>
</div>
<x-pagination :rows="$rows" />
