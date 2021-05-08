<?php

namespace FsgHerbie\VueSkuForm;

use Encore\Admin\Layout\Content;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Qiniu\Auth;

class VueSkuFormController extends Controller {

    /**
     * 上传小于规定大小的文件
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function upload ( Request $request ) {

        $dirPath = date('Y_m_d',time());
        try{
            $file = $request->file('vue_sku_form_image');
            if (!in_array($file->extension(),explode(",",config('vue_sku_form.default.extensions')))) {
                return $this->returnResult(["status" => 0,"msg" => "不支持该图片格式！"]);
            }
            
            if (!$file->isValid()){
                return  $this->returnResult(["status" => 0,"msg" => "图片上传失败"]);
            }
            //保存图片
            $path = $file->store($dirPath,config('vue_sku_form.default.disk'));
            return $this->returnResult(["status" => 1,"msg" => "图片上传成功！","url" => Storage::url($path)]);
        }catch (\Exception $exception){
            return $this->returnResult(["status" => 0,"msg" => "图片上传失败" . $exception->getMessage()]);
        }
    }

    public function returnResult($data) {
        return response()->json($data);
    }
}