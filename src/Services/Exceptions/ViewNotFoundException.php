<?php

namespace Softworx\RocXolid\Services\Exceptions;

use Softworx\RocXolid\Contracts\Renderable;
use Softworx\RocXolid\Exceptions\Exception;

class ViewNotFoundException extends Exception
{
    public function __construct(Renderable $component, string $view_name, array $search_paths)
    {
        //ob_start();
        //var_dump($search_paths);
        //$paths = ob_get_clean();

        $searched = implode("\n", $search_paths);

        $this->message = sprintf("Cannot find view\n[%s]\nfor component\n[%s]\nsearched in folders:\n%s", $view_name, get_class($component), $searched);
    }
}
