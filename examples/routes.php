<?php
/*
 * An example route definition that automatically creates routes for all defined wordpress templates
 */
use Kethatril\ACFComponent\Template;

foreach(Template::$templates as $key => $template) {
    if($template->wordpressTemplate) {

        Route::get('template', [$key, 'uses' => 'TemplateController@'.$template->handler]);
    }
}