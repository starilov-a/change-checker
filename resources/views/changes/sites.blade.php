@extends('layouts.main')
@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Панель</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
                <form class="form-inline" action="/changes/searchsite" method="post">
                    @csrf
                    <div class="form-group mx-sm-3 mb-2">
                        <input type="text" class="form-control form-control-sm" id="searchsite" name="field" placeholder="Поиск изменений по URL">
                    </div>
                </form>
            </div>
        </div>
    </div>
    <h2>Сайты с изменениями - {{ $countSites }}</h2>
    <div class="table-responsive">
        {{ $sites->links()}}
        <table class="table table-striped table-sm">
            <thead>
            <tr>
                <th scope="col">Название</th>
                <th scope="col">Url</th>
                <th scope="col">Дата добавления</th>
                <th scope="col"></th>
            </tr>
            </thead>
            <tbody>
            @foreach ($sites as $site)
                <tr>
                    <td title="{{$site->name}}">
                        @php
                            echo (mb_strlen($site->name) > 60 ? mb_substr($site->name, 0, 60)."..." : $site->name);
                        @endphp
                    </td>
                    <td title="{{$site->name}}">
                        <a href="{{ $site->url }}" target="_blank">
                            {{ $site->url }}
                        </a>
                    </td>
                    <td>
                        {{ $site->changes[0]->created_at }}
                    </td>
                    <td>
                        <form action="/changes/{{ $site->id }}" method="post">
                            @csrf
                            <input hidden name="id" value="{{ $site->id }}">
                            <input type="submit" class="btn btn-primary btn-sm" value="Подробнее">
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>

        </table>
    </div>
@endsection
