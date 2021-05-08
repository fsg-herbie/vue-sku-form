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
            $this->loadViewsFrom($views,'vue-sku-from' );
        }

        $source = realpath ( __DIR__ . '/vue_sku_form.php' );
        if ($this->app->runningInConsole() && $assets = $extension->assets()) {
            $this->publishes(
                [
                    $assets => public_path('vendor/fsg-herbie/vue-sku-from'),
                    $source => config_path('vue_sku_form.php')
                ],
                'vue-sku-from'
            );
        }

        $this->app->booted ( function () {
            VueSkuForm::routes( __DIR__ . '/../routes/web.php' );
        } );

        Admin::booting(function () {
            Form::extend('vue_sku_from',VueSkuFormField::class);
            Admin::js('https://unpkg.com/vue/dist/vue.js');
            Admin::js('https://unpkg.com/element-ui/lib/index.js');
            Admin::css('https://unpkg.com/element-ui/lib/theme-chalk/index.css');
            Admin::css('vendor/fsg-herbie/vue-sku-from/css/sku.css');
        });
    }

}