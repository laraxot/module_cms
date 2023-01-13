<div class="table-responsive">
    <h3>{{$rows->count()}}</h3>
    <table class="table table-hover table-nowrap">
      @foreach($rows as $row)
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
            <a href="#" class="btn btn-sm btn-square btn-neutral">
              <i class="bi bi-pencil"></i>
            </a>
            <button type="button" class="btn btn-sm btn-square btn-neutral text-danger-hover">
              <i class="bi bi-trash"></i>
            </button>
            <x-panel.buttons.crud :panel="$row_panel" />
          </td>
        </tr>
      @endforeach  
      </tbody>
    </table>
  </div>