<?php

return [
    'disks' => [
        'local' => [
            'driver' => 'local' ,
            'root' => storage_path ( 'app' ) ,
        ],
        'public' => [
            'driver' => 'local' ,
            'root' => storage_path ( 'app/public' ) ,
        ],
    ],
    'default' => [
        'disk' => 'public' ,//默认磁盘
        'extensions' => 'jpg,png,mp4' ,//后缀
        'mimeTypes' => 'image/*,video/*' ,//类型
        'fileSizeLimit' => 10737418240 ,//上传文件限制总大小，默认10G,默认单位为b
        'fileNumLimit' => '5' ,//文件上传总数量
        'saveType' => 'json', //单文件默认为字符串，多文件上传存储格式，json:['a.jpg','b.jpg']
    ],
    'initDataStr' => '{"types":"single","attribute":[],"structure":[{"name":"image","type":"image","label":"图片"},{"name":"original_price","type":"input","label":"原价"},{"name":"sale_price","type":"input","label":"销售价"},{"name":"proxy_price","type":"input","label":"代理价"}, {"name":"stock_count","type":"input","label":"库存"}],"sku":[]}'
];
