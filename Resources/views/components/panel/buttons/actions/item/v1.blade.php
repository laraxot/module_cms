@if ($panel->itemActions()->count() > 5)
    <div class="dropdown">
        <button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true"
            aria-expanded="false">
            <i class="fas fa-tools"></i>
        </button>
        <div class="dropdown-menu">
            @foreach ($panel->itemActions() as $act)
                {!! $act->btnHtml() !!}
            @endforeach
        </div>
    </div>
@else
    @foreach ($panel->itemActions() as $act)
        {!! $act->btnHtml() !!}
    @endforeach
@endif
