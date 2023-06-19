@extends('layouts.main')
@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Панель</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
        </div>
    </div>
    <h2>История изменений страницы
        {{ $changes[0]->page->url }}  - {{ $countChanges }}
    </h2>
    <div class="table-responsive">
        {{ $changes->links() }}
        <table class="table table-striped table-sm">
            <thead>
            <tr>
                <th scope="col">Дата проверки</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($changes as $change)
                <tr>
                    <td>
                        {{ $change->created_at }}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
