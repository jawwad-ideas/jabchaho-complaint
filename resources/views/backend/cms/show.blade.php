@extends('backend.layouts.app-master')

@section('content')
    <div class="bg-light p-4 rounded">
        <h2>Show CMS Page</h2>
        <div class="lead">
            
        </div>
        
        <div class="container mt-4">
            <div>
                Page: {{  Arr::get($cmsPage, 'page') }}
            </div>
            <div>
                Title: {{ Arr::get($cmsPage, 'title') }}
            </div>
            <div>
                Content: {!! Arr::get($cmsPage, 'content') !!}
            </div>
            <div>
                Meta keywords:   Title: {{ Arr::get($cmsPage, 'meta_keywords') }}
            </div>
            <div>
                Meta description:   Title: {{ Arr::get($cmsPage, 'meta_description') }}
            </div>
            <div>
            Enable: {{ $booleanOptions[Arr::get($cmsPage, 'is_enabled')] }}
            </div>
        </div>

    </div>
    <div class="mt-4">
        <a href="{{ route('cms.edit', $cmsPage->id) }}" class="btn btn-info">Edit</a>
        <a href="{{ route('cms.index') }}" class="btn btn-default">Back</a>
    </div>
@endsection
