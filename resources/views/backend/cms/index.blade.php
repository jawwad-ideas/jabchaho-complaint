@extends('backend.layouts.app-master')

@section('content')
    <div
        class="page-title-section border-bottom mb-1 d-lg-flex justify-content-between align-items-center d-block bg-theme-green">
        <div class="p-title">
            <h3 class="fw-bold text-white m-0">CMS Pages</h3>
            <small class="text-white">Manage your CMS Pages here.</small>

        </div>
        <div class="text-xl-start text-md-center text-center mt-xl-0 mt-3">
            <div class="btn-group" role="group">
                @if (Auth::user()->can('cms.create'))
                    <a href="{{ route('cms.create') }}" class="text-decoration-none">
                        <small type="button"
                            class="btn btn-sm rounded bg-theme-green-light me-2 border-0 text-theme-green fw-bold d-flex align-items-center p-2 gap-2"><i
                                class="fa fa-solid fa-file-circle-plus"></i><span>Add CMS Page</span></small>
                    </a>
                @endif
            </div>
        </div>

    </div>
    <div class="page-content bg-white p-lg-5 px-2">
        <div class="bg-light p-4 rounded">
            <table class="table table-bordered table-striped table-compact">
                <thead>
                    <th width="1%">No</th>
                    <th>Page</th>
                    <th>Url</th>
                    <th>Title</th>
                    <th width="3%" colspan="3">Action</th>
                </thead>
                @foreach ($cmsPages as $key => $cmsPage)
                    <tr>
                        <td>{{ Arr::get($cmsPage, 'id') }}</td>
                        <td>{{ Arr::get($cmsPage, 'page') }}</td>
                        <td>{{ Arr::get($cmsPage, 'url') }}</td>
                        <td>{{ Arr::get($cmsPage, 'title') }}</td>

                        @if (Auth::user()->can('cms.show'))
                            <td>
                                <a class="btn btn-info btn-sm" href="{{ route('cms.show', $cmsPage->id) }}">Show</a>
                            </td>
                        @endif
                        @if (Auth::user()->can('cms.edit'))
                            <td>
                                <a class="btn btn-primary btn-sm" href="{{ route('cms.edit', $cmsPage->id) }}">Edit</a>
                            </td>
                        @endif
                        @if (Auth::user()->can('cms.destroy'))
                            <td>
                                {!! Form::open([
                                    'method' => 'DELETE',
                                    'route' => ['cms.destroy', $cmsPage->id],
                                    'style' => 'display:inline',
                                    'onsubmit' => 'return ConfirmDelete()',
                                ]) !!}
                                {!! Form::submit('Delete', ['class' => 'btn btn-danger btn-sm']) !!}
                                {!! Form::close() !!}
                            </td>
                        @endif
                    </tr>
                @endforeach
            </table>

            <div class="d-flex">
                {!! $cmsPages->links() !!}
            </div>

        </div>
    @endsection
