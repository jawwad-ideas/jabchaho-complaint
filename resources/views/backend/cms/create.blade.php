@extends('backend.layouts.app-master')

@section('content')
    <div class="bg-light p-4 rounded">
        <h2>Add new CMS Page</h2>
        <div class="lead">
            Add new CMS Page
        </div>

        <div class="container mt-4">

            <form method="POST" action="{{ route('cms.store') }}">
                @csrf
                
                <div class="mb-3">
                    <label for="page" class="form-label">Page</label>
                    <input value="{{ old('page') }}" 
                        type="text" 
                        class="form-control" 
                        name="page" 
                        maxlength="100"
                        placeholder="page">
                </div>
                
                <div class="mb-3">
                    <label for="title" class="form-label">Title</label>
                    <input value="{{ old('title') }}" 
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
                        placeholder="Content" >{{ old('content') }}</textarea>
                </div>

                <div class="mb-3">
                    <label for="meta_keywords" class="form-label">Meta keywords</label>
                    <textarea class="form-control" 
                        id="meta_keywords"
                        name="meta_keywords"  >{{ old('meta_keywords') }}</textarea>
                </div>

                <div class="mb-3">
                    <label for="body" class="form-label">Meta description</label>
                    <textarea class="form-control" 
                        id="meta_description"
                        name="meta_description" >{{ old('meta_description') }}</textarea>
                </div>


                <div class="mb-3">
                    <label for="Email" class="form-label">Enable</label>
                        @if(!empty($booleanOptions))
                            @foreach($booleanOptions as $key=>$value)
                                <input class="form-check-input" type="radio" name="is_enabled" id="is_enabled-{{$key}}" value="{{$key}}" 
                                        
                                @if((old('_token') && old('is_enabled') == $key)) 
                                    {{'checked'}}
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