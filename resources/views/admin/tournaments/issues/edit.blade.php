@extends('laramanager::layouts.default')

@section('title')
    Edit Issue
@endsection

@section('content')

    <div class="uk-width-1-3">
        <form action="{{ route('issues.update', $issue->id) }}" method="POST" class="uk-form uk-form-stacked uk-margin-bottom">
            {{ csrf_field() }}
            {{ method_field('PUT') }}

            @include('laraform::elements.form.textarea', ['field' => ['name' => 'issue', 'value' => $issue->issue]])

            <div class="uk-form-row">
                @include('laraform::elements.form.submit', ['value' => 'Update'])
            </div>
        </form>

        <form action="{{ route('issues.destroy', $issue->id) }}" method="POST" class="uk-form uk-form-stacked">
            {{ csrf_field() }}
            {{ method_field('DELETE') }}

            @include('laraform::elements.form.submit', ['value' => 'Delete', 'class' => 'uk-button-danger uk-text-contrast'])
        </form>
    </div>

@endsection