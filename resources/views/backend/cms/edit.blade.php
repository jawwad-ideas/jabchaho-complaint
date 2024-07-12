@extends('backend.layouts.app-master')

@section('content')
    <div class="bg-light p-4 rounded">
        <h2>Update CMS Page</h2>
        <div class="lead">
            Edit CMS Page.
        </div>

        <div class="container mt-4">

            <form method="POST" action="{{ route('cms.update', $cmsPage->id) }}">
                @method('patch')
                @csrf
         
                
                <div class="mb-3">
                    <label for="page" class="form-label">Page</label>
                    <input value="@if(old('page')){{old('page')}}@elseif(empty(old('page')) && old('_token')) {{''}}@else{{Arr::get($cmsPage,'page')}}@endif" 
                        type="text" 
                        class="form-control" 
                        name="page" 
                        maxlength="100"
                        placeholder="Page">
                </div>
                <div class="mb-3">
                    <label for="title" class="form-label">Title</label>
                    <input value="@if(old('title')){{old('title')}}@elseif(empty(old('title')) && old('_token')) {{''}}@else{{Arr::get($cmsPage,'title')}}@endif" 
                        type="text" 
                        class="form-control" 
                        name="title" 
                        maxlength="200"
                        placeholder="Title">
                </div>

                <div class="mb-3">
                    <label for="body" class="form-label">Content</label>
                    <textarea class="form-control content" 
                        id="content"
                        name="content" 
                        placeholder="Content" >@if(old('content')){{old('content')}}@elseif(empty(old('content')) && old('_token')) {{''}}@else{{Arr::get($cmsPage,'content')}}@endif</textarea>
                </div>

                <div class="mb-3">
                    <label for="meta_keywords" class="form-label">Meta keywords</label>
                    <textarea class="form-control" 
                        id="meta_keywords"
                        name="meta_keywords"  >@if(old('meta_keywords')){{old('meta_keywords')}}@elseif(empty(old('meta_keywords')) && old('_token')) {{''}}@else{{Arr::get($cmsPage,'meta_keywords')}}@endif</textarea>
                </div>

                <div class="mb-3">
                    <label for="body" class="form-label">Meta description</label>
                    <textarea class="form-control" 
                        id="meta_description"
                        name="meta_description" >@if(old('meta_description')){{old('meta_description')}}@elseif(empty(old('meta_description')) && old('_token')) {{''}}@else{{Arr::get($cmsPage,'meta_description')}}@endif</textarea>
                </div>

                <div class="mb-3">
                    <label for="Email" class="form-label">Enable</label>
                        @if(!empty($booleanOptions))
                            @foreach($booleanOptions as $key=>$value)
                                <input class="form-check-input" type="radio" name="is_enabled" id="is_enabled-{{$key}}" value="{{$key}}" 
                                        
                                @if((old('_token') && old('is_enabled') == $key)) 
                                    {{'checked'}}
                                @elseif(old('_token') == null && Arr::get($cmsPage, 'is_enabled'))  
                                    {{ Arr::get($cmsPage, 'is_enabled') == $key ? 'checked' : '' }}     
                                @else 
                                    @if(old('_token') == null && $key==0)
                                        {{'checked'}}
                                    @endif
                                @endif
                                
                                >
                                <label class="form-check-label" for="position-{{$key}}">{{$value}}</label>
                            @endforeach
                        @endif
                </div>
 
                <button type="submit" class="btn btn-primary">Save</button>
                <a href="{{ route('cms.index') }}" class="btn btn-default">Back</a>
            </form>
        </div>

    </div>

<link href="{!! url('assets/bootstrap/css/bootstrap_3.4.1.min.css') !!}" rel="stylesheet">
<link href="{!! url('assets/css/summernote.min.css') !!}" rel="stylesheet">
<script src="{!! url('assets/js/summernote.min.js') !!}"></script>
<script>
$( document ).ready(function() {
    
    $('.content').summernote({

    height:300,

    });

});

</script> 

@endsection