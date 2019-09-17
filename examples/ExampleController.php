<?php
/**
 * An example controller that outputs fields from the given template
 */
namespace App\Http\Controllers;

use Kethatril\ACFComponent\Template;
use Illuminate\Routing\Controller as BaseController;

class ExampleController extends BaseController
{
    public function home($post) {
        $route = app('router')->current();
        $template = $route->action[0];
        return $this->template($post, $template);

    }

    public function handle($post) {
        $route = app('router')->current();
        $template = $route->action[0];
        return $this->template($post, $template);

    }



    private function template($post, $name, $view = false) {

        $template = Template::$templates[$name];
        $fields = $template->getTemplateData($post->ID);
        if($view === false) {
            $view = 'templates.__template__';
        } else {
            $view = 'templates.'.$view;
        }

        return view($view, [
            'fields' => $fields,
            'components' => $template->components,
            'extraComponents' => $template->extraComponents,
            'post' => $post
        ]);
    }
}
