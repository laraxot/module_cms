<div role="group" aria-label="Actions" class="btn-group btn-group-sm">
    @foreach ($panel->getItemTabs() as $tab)
<<<<<<< HEAD
        <x-button :attrs="get_object_vars($tab)"></x-button>
=======
        {{-- <x-button :attrs="get_object_vars($tab)"></x-button> --}}
        <x-button.link :link="$tab"></x-button.link>
>>>>>>> 83789965fd9572aa1df56c480bdf14891b374275
    @endforeach
</div>
<br />
<div role="group" aria-label="Actions" class="btn-group btn-group-sm">
    <x-button.panel.edit :panel="$panel" />
    <x-button.panel.delete :panel="$panel" />
    <x-button.panel.detach :panel="$panel" />
    <x-button.panel.show :panel="$panel" />
</div>
