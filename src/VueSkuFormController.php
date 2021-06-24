<?php

namespace FsgHerbie\VueSkuForm;

use Encore\Admin\Layout\Content;
use Encore\MediaSelector\RestApi\Helpers\ApiResponse;
use Encore\MediaSelector\RestApi\Helpers\ResourcesMedia;
use Encore\MediaSelector\RestApi\Services\MediaService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Qiniu\Auth;

class VueSkuFormController extends Controller {

    use ApiResponse;

    protected $mediaService;

    public function __construct(MediaService $mediaService){
        $this->mediaService = $mediaService;
    }

    /**
     * 上传小于规定大小的文件
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function upload ( Request $request ) {

        try{
            $media_obj = $this->mediaService->upload(
                0,
                $request->file('vue_sku_form_image'),
                "image",
                json_decode(json_encode(["dir" => "sku","fileNameIsEncrypt" => true]))
            );

            $data = ResourcesMedia::make($media_obj);
            
            return $this->returnResult([
                "status" => 1,
                "msg" => "图片上传成功！",
                "url" => Storage::url($data->path)
            ]);
            
        }catch (\Exception $exception){
            return $this->returnResult(["status" => 0,"msg" => "图片上传失败" . $exception->getMessage()]);
        }
        
    }

    public function returnResult($data) {
        return response()->json($data);
    }
}