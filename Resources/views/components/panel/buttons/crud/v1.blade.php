<div role="group" aria-label="Actions" class="btn-group btn-group-sm">
    @foreach ($panel->getItemTabs() as $tab)
        <x-button :attrs="get_object_vars($tab)"></x-button>
    @endforeach
</div>
<br />
<div role="group" aria-label="Actions" class="btn-group btn-group-sm">
    <x-button.panel.edit :panel="$panel" />
    <x-button.panel.delete :panel="$panel" />
    <x-button.panel.detach :panel="$panel" />
    <x-button.panel.show :panel="$panel" />
</div>
