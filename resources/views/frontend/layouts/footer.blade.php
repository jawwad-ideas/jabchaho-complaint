<footer class="p-3 bg-theme-green text-white ff-gothambook footer">
    <div class="container ">


        <div class="ff-gothambook t-a-center my-4">
            @if(!empty($cmsPages))
            @php $counter=1; @endphp
            @foreach($cmsPages as $cmsPage)

            <a class="px-2 text-white" href="{{route('cms.pages',Arr::get($cmsPage,'url'))}}"
                target="_blank" />{{Arr::get($cmsPage,'page')}}</a>

            @if(count($cmsPages) != $counter)
            |
            @endif
            @php $counter++; @endphp

            @endforeach
            @endif
        </div>
        <div class="text-white  text-center mt-5 d-block">
            <p class="copyright">Copyright © {{date('Y')}}. All Rights Reserved.</p>
        </div>
    </div>
    </div>



</footer>