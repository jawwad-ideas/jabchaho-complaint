  <div class="itemForm orderItemSec border-bottom border-2">
                        <div class="item-form-row p-xl-3 p-lg-3 p-md-3 p-sm-0 bg-light rounded border-light">
                            <div class="itemLabel p-3">
                                <label class="d-flex">
                                    <h6 class="d-inline-block fw-bold"> Service Type: </h6>
                                    <span> {{$item->service_type}} </span>
                                </label>
                                <label class="d-flex">
                                    <h6 class="fw-bold d-inline-block"> Product: </h6>
                                    <span> {{$item->item_name}} </span>
                                </label>
                                <label class="d-flex ">
                                    <h6 class="d-inline-block fw-bold"> Barcode: </h6>
                                    <span class="barcode"> {{$item->barcode}} </span>
                                </label>
                            </div>
                            <button type="button" class="btn bg-theme-yellow fw-bold text-dark w-100 d-flex justify-content-between align-items-center mb-3" data-toggle="collapse" data-target="#machine-detail-{{$item->id}}">
                                Washing Detail <i class="toggle-icon fa fa-chevron-down text-right"></i></button>

                            <div id="machine-detail-{{$item->id}}" class="collapse mb-2">

                                <table class="table table-bordered table-striped table-compact mt-3">
                                    <tr>
                                        <th scope="col" width="45%">Machine Type</th>
                                        <th scope="col" width="45%">Process At</th>
                                        <th scope="col" width="10%">Image</th>
                                    </tr>
                                    @if(!Arr::get($item,'machineBarcode')->isEmpty())
                                    @foreach(Arr::get($item,'machineBarcode') as $row)
                                    @if(!empty($row->machineDetail))
                                    <tr>
                                        <td>@if(!empty($row->machineDetail->machine)){{Arr::get($row->machineDetail->machine,'name')}} @endif</td>
                                        <td>{{date('j M, Y, \a\t h:i A', strtotime(Arr::get($row->machineDetail,'created_at')))}}</td>
                                        <td>
                                            @if(!empty($row->machineDetail->machineImages[0]))
                                            @if(file_exists(public_path(asset(config('constants.files.machines')).'/'.Arr::get($row->machineDetail,'id').'/'.Arr::get($row->machineDetail->machineImages[0],'file'))))
                                            <a href="{{asset(config('constants.files.machines'))}}/{{Arr::get($row->machineDetail,'id') }}/{{Arr::get($row->machineDetail->machineImages[0],'file')}}" target="_blank" class="d-block">
                                                <img src="{{asset(config('constants.files.machines'))}}/{{Arr::get($row->machineDetail,'id') }}/thumbnail/{{Arr::get($row->machineDetail->machineImages[0],'file')}}" class="img-fluid rounded-lg shadow-sm">
                                            </a>
                                            @endif
                                            @endif
                                        </td>
                                    </tr>
                                    @endif
                                    @endforeach

                                    @else
                                    <tr>
                                        <td colspan="3" align="center">No record Found</td>
                                    </tr>

                                    @endif
                                </table>
                            </div>

                            <div class="inner-row d-xl-flex d-lg-flex d-md-block justify-content-between pb-2 gap-0">
                                <div class="col-lg-6 pb-1 pt-3 border-light px-3" style="border-bottom: 4px double;border-color: #f7e441 ! IMPORTANT;background: #eee;">
                                    <div class="d-flex align-items-center gap-3">
                                        <label for="pickup_images" class="form-label fw-bold">Before Wash Images</label>
                                    </div>

                                    <div class="upload-img-input-sec" id="image-upload-container-pickup_images-{{ $item->id }}">
                                        <span class="d-inline-flex gap-2">
                                            <input value="" type="file" class="form-control img-upload-input"
                                                name="image[{{$item->id}}][pickup_images][]" placeholder="" data-order-num="{{$order->order_id}}" data-order-id="{{$order->id}}" data-item-type="pickup_images" data-item-id="{{ $item->id }}" id="uploadImage-{{ $item->id }}">
                                            <button type="button" class="btn btn-primary startWebcamBtn d-none d-lg-block" id="startWebcamBtn-{{ $item->id }}" data-order-num="{{$item->order->order_id}}" data-order-id="{{$item->order->id}}" data-item-type="pickup_images" data-item-id="{{ $item->id }}">
                                                <i class="fa fa-camera" aria-hidden="true"></i>
                                            </button>
                                        </span>
                                        <div class="having-fault-radio-btns d-flex align-items-center gap-3 mt-3">
                                            <small>Item having Issue:</small>
                                            <div class="d-flex gap-2">
                                                <div class="form-check form-check-inline d-flex align-items-center gap-1">
                                                    <input class="form-check-input yesfault" type="radio" data-barcode="{{$item->barcode}}" data-item="{{$item->id}}" name="is_issue_identify[{{$item->id}}]" id="yesfault-{{$item->id}}" value="2"
                                                        data-saved-issue-{{$item->id}}=@if(!empty($item->issues)) "{{ implode(',', $item->issues->map(fn($row) => Arr::get($row->toArray(), 'issue'))->sort()->toArray()) }}" @else "" @endif
                                                    @if( $item->is_issue_identify == 2 ) checked @endif >
                                                    <label class="form-check-label" for="yesfault">Yes</label>
                                                </div>
                                                <div class="form-check form-check-inline d-flex align-items-center gap-1">
                                                    <input class="form-check-input nofault" type="radio" data-item="{{$item->id}}" name="is_issue_identify[{{$item->id}}]" id="nofault-{{$item->id}}" value="1" @if( $item->is_issue_identify != 2 ) checked @endif>
                                                    <label class="form-check-label" for="nofault">No</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @if(!empty($item->issues))

                                    <div class="form-check-label text-capitalize d-flex gap-2 flex-wrap mt-2 w-50 issuesPills " for="color fading" id="savedOrderItemIssues-{{$item->id}}">
                                        @foreach($item->issues as $row)
                                        <span class="rounded-pill badge-sm badge p-1 bg-theme-yellow text-dark">{{config('constants.issues.'.Arr::get($row, 'issue'))}}</span>
                                        @endforeach
                                    </div>

                                    @endif
                                    <!-- <div>
                                        <button title="Add More Images" type="button" class="btn bg-theme-yellow text-dark d-inline-flex align-items-center gap-3 btn-primary mt-2"
                                                onclick="addMoreImageUpload({{ $item->id }},'pickup_images')">Add More</button>
                                    </div> -->
                                    <div class="items-images-sec mt-3" id="items-images-sec-pickup_images-{{ $item->id }}">
                                        @if( $item->images->isNotEmpty() )

                                        @foreach ($item->images as $image)
                                        @if( $image->image_type == "Before Wash" )
                                        <?php
                                        $beforeMainImage = asset(config('constants.files.orders')) . '/' . $order->order_id . '/before/' . $image->imagename;
                                        $beforeThumbnail = asset(config('constants.files.orders')) . '/' . $order->order_id . '/thumbnail/before/' . $image->imagename;
                                        $isBeforeThumbnail = public_path(config('constants.files.orders')) . '/' . $order->order_id . '/thumbnail/before/' . $image->imagename;
                                        if (!\Illuminate\Support\Facades\File::exists($isBeforeThumbnail)) {
                                            $beforeThumbnail = $beforeMainImage;
                                        }

                                        $isBeforeMainImage = public_path(config('constants.files.orders')) . '/' . $order->order_id . '/before/' . $image->imagename;
                                        if (!\Illuminate\Support\Facades\File::exists($isBeforeMainImage)) {
                                            $beforeMainImage = $beforeThumbnail;
                                        }
                                        ?>

                                        <div class="img-item">
                                            <a href="{{$beforeMainImage}}" target="_blank"> <img class="img-thumbnail" src="{{$beforeThumbnail}}" alt="{{$image->imagename}}"> </a>
                                            <div class="item-img-action-btn">
                                                <button class="btn btn-danger btn-sm delete-image ms-2" data-image-id="{{ $image->id }}" data-order-number="{{ $order->order_id }}" title="Delete">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                        @endif
                                        @endforeach

                                        @endif


                                    </div>

                                </div>

                                <div class="col-lg-6 pb-1 pt-3 border-light px-3" style="background: #fbee7e4f;">
                                    <label for="delivery_images" class="form-label fw-bold">After Wash Images</label>
                                    <div class="upload-img-input-sec" id="image-upload-container-delivery_images-{{ $item->id }}">
                                        <span class="d-inline-flex gap-2">
                                            <input @if( $disableAfterUploadInput ) disabled @endif value="" type="file" class="form-control img-upload-input-after" name="image[{{$item->id}}][delivery_images][]" placeholder="" data-order-num="{{$order->order_id}}" data-order-id="{{$order->id}}" data-item-type="delivery_images" data-item-id="{{ $item->id }}" id="uploadImage-{{ $item->id }}">
                                            <button type="button" @if( $disableAfterUploadInput ) disabled @endif class="btn btn-primary startWebcamBtn d-none d-lg-block" id="startWebcamBtn-{{ $item->id }}" data-order-num="{{$item->order->order_id}}" data-order-id="{{$item->order->id}}" data-item-type="delivery_images" data-item-id="{{ $item->id }}">
                                                <i class="fa fa-camera" aria-hidden="true"></i>
                                            </button>
                                        </span>
                                        <div class="having-fault-radio-btns d-flex align-items-center gap-3 mt-3">
                                            <small>Issue Fixed:</small>
                                            <div class="d-flex gap-2">
                                                <div class="form-check form-check-inline d-flex align-items-center gap-1">
                                                    <input class="form-check-input yesFixed issueFixed" type="radio" data-item="{{$item->id}}" name="is_issue_fixed[{{$item->id}}]" id="yesfixed-{{$item->id}}" value="2"
                                                        @if( $item->is_issue_fixed == 2 ) checked @endif @if( $item->is_issue_identify == 1 ) disabled @endif >
                                                    <label class="form-check-label" for="yesFixed">Yes</label>
                                                </div>
                                                <div class="form-check form-check-inline d-flex align-items-center gap-1">
                                                    <input class="form-check-input noFixed issueFixed" @if( $item->is_issue_identify == 1 ) disabled @endif type="radio" data-item="{{$item->id}}" name="is_issue_fixed[{{$item->id}}]" id="nofixed-{{$item->id}}" value="1" @if( $item->is_issue_fixed != 2 ) checked @endif>
                                                    <label class="form-check-label" for="noFixed">No</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- <div>
                                        <button title="Add More Images" type="button" class="btn bg-theme-yellow text-dark d-inline-flex align-items-center gap-3 btn-primary mt-2"  onclick="addMoreImageUpload({{ $item->id }},'delivery_images')">Add More</button>
                                    </div> -->

                                    <div class="items-images-sec mt-3" id="items-images-sec-delivery_images-{{ $item->id }}">


                                        @if( $item->images->isNotEmpty() )

                                        @foreach ($item->images as $image)
                                        @if( $image->image_type == "After Wash" )
                                        <?php
                                        $afterMainImage = asset(config('constants.files.orders')) . '/' . $order->order_id . '/after/' . $image->imagename;
                                        $afterThumbnail = asset(config('constants.files.orders')) . '/' . $order->order_id . '/thumbnail/after/' . $image->imagename;
                                        $isAfterThumbnail = public_path(config('constants.files.orders')) . '/' . $order->order_id . '/thumbnail/after/' . $image->imagename;
                                        if (!\Illuminate\Support\Facades\File::exists($isAfterThumbnail)) {
                                            $afterThumbnail = $afterMainImage;
                                        }

                                        $isAfterMainImage = public_path(config('constants.files.orders')) . '/' . $order->order_id . '/after/' . $image->imagename;
                                        if (!\Illuminate\Support\Facades\File::exists($isAfterMainImage)) {
                                            $afterMainImage = $afterThumbnail;
                                        }
                                        ?>
                                        <div class="img-item">
                                            <a href="{{$afterMainImage}}" target="_blank"> <img class="img-thumbnail" src="{{$afterThumbnail}}" alt="{{$image->imagename}}"> </a>
                                            <div class="item-img-action-btn">
                                                <button class="btn btn-danger btn-sm delete-image ms-2" data-image-id="{{ $image->id }}" data-order-number="{{ $order->order_id }}" title="Delete">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                        @endif
                                        @endforeach

                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>