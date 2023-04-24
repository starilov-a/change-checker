@extends('layouts.main')
@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Панель</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <button type="button" class="btn btn-sm btn-outline-secondary">Экспорт</button>
        </div>
        <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-calendar" aria-hidden="true"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
            Эта неделя
        </button>
    </div>
</div>
<h2>Отслеживаемые сайты - {{ $countSites }}</h2>
<div class="table-responsive">
    {{ $sites->links()}}
    <table class="table table-striped table-sm">
        <thead>
        <tr>
            <th scope="col">id</th>
            <th scope="col">Название</th>
            <th scope="col">url</th>
            <th scope="col">Кол-во страниц</th>
            <th scope="col">дата добавления</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($sites as $site)
            <tr>
                <td>{{ $site->id }}</td>
                <td title="{{$site->name}}">
                    @php
                        echo (mb_strlen($site->name) > 60 ? mb_substr($site->name, 0, 60)."..." : $site->name);
                    @endphp
                </td>
                <td><a href="{{ $site->url }}">{{ $site->url }}</a></td>
                <td>{{ $site->page_count }}</td>
                <td>{{ $site->created_at }}</td>
            </tr>
        @endforeach
        </tbody>

    </table>
</div>
@endsection
