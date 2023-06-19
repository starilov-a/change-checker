@extends('layouts.main')
@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Панель</h1>
    </div>
    <h2>Исключенные страницы - {{ $countExcludedPage }}</h2>
    <div class="table-responsive">
        {{ $excludedPages->links()}}
        <table class="table table-striped table-sm">
            <thead>
            <tr>
                <th scope="col">Url</th>
                <th scope="col">Дата исключения</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($excludedPages as $page)
                <tr>
                    <td>
                        <a href="{{ $page->url }}{{ $page->url }}" target="_blank">
                            @php
                                echo urldecode($page->url);
                            @endphp
                        </a></td>
                    <td>{{ $page->excludedPage->created_at }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
