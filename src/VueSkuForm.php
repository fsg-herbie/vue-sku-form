<?php

namespace FsgHerbie\VueSkuForm;

use Encore\Admin\Extension;

class VueSkuForm extends Extension{
    public $name = 'vue-sku-from';
    public $views = __DIR__.'/../resources/views';
    public $assets = __DIR__.'/../resources/assets';
}