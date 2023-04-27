@extends('layouts.main')
@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Панель</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
                <a href="/sites/create"><button type="button" class="btn btn-sm btn-outline-secondary">Добавить сайт</button></a>
            </div>
{{--            <div class="btn-group me-2">--}}
{{--                <button type="button" class="btn btn-sm btn-outline-secondary">Экспорт</button>--}}
{{--            </div>--}}
{{--            <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle">--}}
{{--                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-calendar" aria-hidden="true"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>--}}
{{--                Эта неделя--}}
{{--            </button>--}}
        </div>
    </div>
    <h2>Зафиксированные изменения - {{ $countChanges }}</h2>
    <div class="table-responsive">
        {{ $changes->links()}}
        <table class="table table-striped table-sm">
            <thead>
            <tr>
                <th scope="col">Сайт</th>
                <th scope="col">Url</th>
                <th scope="col">Проверено</th>
                <th scope="col">Дата добавления</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($changes as $change)
                <tr>
                    <td title="{{$change->sites->name}}">
                        <a href="{{ $change->sites->url }}" target="_blank">
                            {{ $change->sites->url }}
                        </a>
                    </td>
                    <td><a href="{{ $change->sites->url }}{{ $change->url }}" target="_blank">{{ $change->url }}</a></td>
                    <td>{{ $change->checked }}</td>
                    <td>{{ $change->created_at }}</td>
                </tr>
            @endforeach
            </tbody>

        </table>
    </div>
@endsection
