@extends('layouts.main')
@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Панель</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
        </div>
    </div>
    <h2>История по сайту
        {{ $pages[0]->site->url }} - {{ $countPages }}
    </h2>
    <div class="table-responsive">
        {{ $pages->links()}}
        <table class="table table-striped table-sm">
            <thead>
            <tr>
                <th scope="col">Url</th>
                <th scope="col"></th>
            </tr>
            </thead>
            <tbody>
            @foreach ($pages as $page)
                <tr>
                    <td title="{{$page->url}}">
                        <a href="{{ $page->site->url }}{{ $page->url }}" target="_blank">
                            {{ $page->url }}
                        </a>
                    </td>
                    <td>
                        <a href="/historychanges/page/{{ $page->id }}" class="btn btn-primary btn-sm">Список изменений</a>
                    </td>
                </tr>
            @endforeach
            </tbody>

        </table>
    </div>
@endsection
