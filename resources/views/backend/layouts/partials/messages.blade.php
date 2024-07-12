@if(Session::has('success'))
    @if (is_array(Session::get('success')))
        @foreach (Session::get('success') as $msg)
            <script>
                toastr.success('{{ $msg }}');
                setTimeout(() => {
                }, 3000);
            </script>
        @endforeach
    @else
        <script>
            toastr.success('{{ Session::get('success') }}');
            setTimeout(() => {
            }, 3000);
        </script>
    @endif
@endif

@if ($errors->any())
    <script>
        @foreach ($errors->all() as $error)
            toastr.error('{{ $error }}');
        @endforeach
    </script>
@endif