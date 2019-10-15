<?php

namespace Softworx\RocXolid\Composers\Contracts;

use Illuminate\Contracts\View\View;

interface Composer
{
    /**
     * Bind data to the view.
     *
     * @param \Illuminate\Contracts\View\View $view
     * @return \Softworx\RocXolid\Composers\Contracts\Composer
     */
    public function compose(View $view): Composer;
}
