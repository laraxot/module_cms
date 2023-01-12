<div class="table-responsive">
    <h3>{{$rows->count()}}</h3>
    <table class="table table-hover table-nowrap">
      @foreach($rows as $row)
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
          </td>
          @endforeach
          <td class="text-end">
            <a href="#" class="btn btn-sm btn-square btn-neutral">
              <i class="bi bi-pencil"></i>
            </a>
            <button type="button" class="btn btn-sm btn-square btn-neutral text-danger-hover">
              <i class="bi bi-trash"></i>
            </button>
          </td>
        </tr>
      @endforeach  
      </tbody>
    </table>
  </div>