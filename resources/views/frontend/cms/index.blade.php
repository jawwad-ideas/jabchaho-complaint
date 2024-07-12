@extends('frontend.layouts.app-cms')
@section('title', Arr::get($cmsDetail,'title'))
@section('meta_keywords', Arr::get($cmsDetail,'meta_keywords'))
@section('meta_description', Arr::get($cmsDetail,'meta_description'))
@section('content')

<!-- <h3>{{Arr::get($cmsDetail,'title')}}<h3> -->

<div class="content">
    {!! Arr::get($cmsDetail,'content') !!}
</div>

<script>
$(document).ready(function() {
    $('.rec-flow .step').each(function() {
        // select the img element inside the .img div
        const $img = $(this).find('img');
        // check if the viewport width is less than 768 pixels
        if ($(window).width() < 768) {
            // add '-mb' to the end of the image filename
            const src = $img.attr('src').replace(/(\.\w+)$/, '-mb$1');
            // update the image source
            $img.attr('src', src);
        }
    });
});
</script>
@endsection