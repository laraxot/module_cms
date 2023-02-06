@extends('adm_theme::layouts.app')
@section('content')
    CMS
    <br /><br />
    @foreach ($_panel->itemActions() as $act)
        {!! $act->btnHtml() !!}
    @endforeach
    <br /><br />
    {{--
  <livewire:passport.status ></livewire:passport.status>
  --}}
@endsection
