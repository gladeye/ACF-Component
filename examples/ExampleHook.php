<?php
/**
 * An example Themosis 2 hook that loads in components and templates from a directory in the resources directory
 */
namespace App\Hooks;

use Symfony\Component\Finder\Finder;
use Themosis\Hook\Hookable;

class ExampleHook extends Hookable
{
    /**
     * Extend WordPress.
     */
    public function register()
    {
        $this->loadDirectory(base_path('resources/components'));
        $this->loadDirectory(base_path('resources/templates'));
    }

    private function loadDirectory($dir) {
        $files = [];
        foreach (Finder::create()->files()->name('*.php')->in($dir) as $file) {
            $files[] = $file->getRealPath();
        }

        sort($files, SORT_NATURAL);

        foreach($files as $file) {
            include_once $file;
        }
    }
}
