<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cms;
use Illuminate\Support\Arr;
use App\Helpers\Helper;
use App\Http\Requests\Backend\CmsRequest;

class CMSController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cmsPages = Cms::latest()->paginate(config('constants.per_page'));

        $data['cmsPages']  = $cmsPages; 
        return view('backend.cms.index')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = array();
        $data['booleanOptions']     = config('constants.boolean_options'); 
        return view('backend.cms.create')->with($data);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CmsRequest $request)
    {
        Cms::create([
            'page'              => $request->page,
            'url'               => Helper::sluggify($request->page),
            'title'             => $request->title,
            'content'           => $request->content,
            'meta_keywords'     => $request->meta_keywords,
            'meta_description'  => $request->meta_description,
            'is_enabled'        => $request->is_enabled,
            'created_by'        => auth()->id(),
       ]);

        return redirect()->route('cms.index')
            ->withSuccess(__('CMS Page created successfully.'));
    }


     /**
     * Display the specified resource.
     *
     * @param  \App\Models\Cms  $cms
     * @return \Illuminate\Http\Response
     */
    public function show(Cms $cms)
    {
        $data['cmsPage']            = $cms;
        $data['booleanOptions']     = config('constants.boolean_options'); 
        return view('backend.cms.show')->with($data);
    }


     /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Cms  $cms
     * @return \Illuminate\Http\Response
     */
    public function edit(Cms $cms)
    {
        $data['cmsPage']            = $cms;
        $data['booleanOptions']     = config('constants.boolean_options'); 
        return view('backend.cms.edit')->with($data);
    }


     /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\CmsRequest  $request
     * @param  \App\Models\Cms  $cms
     * @return \Illuminate\Http\Response
     */
    public function update(CmsRequest $request, Cms $cms)
    {
        $cms->update(
            [
                'page'              => $request->page,
                'url'               => Helper::sluggify($request->page),
                'title'             => $request->title,
                'content'           => $request->content,
                'meta_keywords'     => $request->meta_keywords,
                'meta_description'  => $request->meta_description,
                'is_enabled'        => $request->is_enabled,
                'updated_by'        => auth()->id(),
           ]
        );

        return redirect()->route('cms.index')
            ->withSuccess(__('CMS Page updated successfully.'));
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cms  $cms
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cms $cms)
    {
        $cms->delete();

        return redirect()->route('cms.index')
            ->withSuccess(__('CMS Page deleted successfully.'));
    }

}
