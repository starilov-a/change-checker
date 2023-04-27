@extends('layouts.main')
@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Добавление нового сайта</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
{{--            <div class="btn-group me-2">--}}
{{--                <a href="/sites/add"><button type="button" class="btn btn-sm btn-outline-secondary">Добавить сайт</button></a>--}}
{{--            </div>--}}
{{--            <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle">--}}
{{--                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-calendar" aria-hidden="true"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>--}}
{{--                Эта неделя--}}
{{--            </button>--}}
        </div>
    </div>
    <div class="table-responsive">
        <form action="/sites" method="post">
            @csrf
            <div class="mb-3">
                <label for="siteUrl" class="form-label" >URL сайта</label>
                <input type="text" class="form-control" id="siteUrl" name="urls">
                <div id="siteUrl" class="form-text">Можете указать несколько сайтов через запятую</div>
            </div>
            <button type="submit" class="btn btn-primary">Добавить</button>
        </form>
    </div>
@endsection
