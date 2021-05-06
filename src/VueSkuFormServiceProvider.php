<?php

namespace FsgHerbie\VueSkuForm;

use Encore\Admin\Admin;
use Encore\Admin\Form;
use Illuminate\Support\ServiceProvider;

class VueSkuFormServiceProvider extends ServiceProvider{
    /**
     * {@inheritdoc}
     */
    public function boot (VueSkuForm $extension ) {
        if (!VueSkuForm::boot()){
            return;
        }

        if ($views = $extension->views()){
            $this->loadViewsFrom($views , 'vue-sku-from.blade' );
        }

        Admin::booting (function () {
            Form::extend('vue_sku_form', VueSkuFormField::class);
            Admin::js('https://unpkg.com/vue@next');
            Admin::js('https://unpkg.com/element-plus/lib/index.full.js');
            Admin::css('https://unpkg.com/element-plus/lib/theme-chalk/index.css' );
        });
    }

}