<?php

namespace Softworx\RocXolid\Services\Exceptions;

use Softworx\RocXolid\Components\Contracts\Renderable;
use Softworx\RocXolid\Exceptions\Exception;

class ViewNotFoundException extends Exception
{
    public function __construct(Renderable $component, $view_name, $search_paths)
    {
        //ob_start();
        //var_dump($search_paths);
        //$paths = ob_get_clean();

        $this->message = sprintf("Cannot find view\n[%s]\nfor component\n[%s]\nsearched in folders:\n%s", $view_name, get_class($component), print_r($search_paths, true));
    }
}
