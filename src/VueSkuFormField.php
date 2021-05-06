<?php
/**
 * Created by PhpStorm.
 * User: 猫巷
 * Email:catlane@foxmail.com
 * Date: 2019/5/28
 * Time: 2:40 PM
 */

namespace FsgHerbie\VueSkuForm;

use Encore\Admin\Form\Field;

class VueSkuFormField extends Field {
    public $view = 'vue-sku-from::vue-sku-from';
    public function __construct ( $column , array $arguments = [] ) {
        parent::__construct ( $column , $arguments );
    }
    public function render () {
        return parent::render ();
    }
}

