<?php
/**
 * 引入css或js插件资源
 *
 * Admin::css('/packages/css/styles.css');
 * Admin::js('/packages/js/main.js');
 */

Admin::js('wangEditor-3.1.1/release/wangEditor.min.js');
Admin::css('wangEditor-3.1.1/release/wangEditor.min.css');
app('view')->prependNamespace('admin', admin_path('admin-views'));

function edit_env(array $data)
{
    $envPath = base_path() . DIRECTORY_SEPARATOR . '.env';

    $contentArray = collect(file($envPath, FILE_IGNORE_NEW_LINES));

    $contentArray->transform(function ($item) use ($data){
        foreach ($data as $key => $value){
            if(str_contains($item, $key)){
                return $key . '=' . $value;
            }
        }

        return $item;
    });

    $content = implode($contentArray->toArray(), "\n");

    \File::put($envPath, $content);
}