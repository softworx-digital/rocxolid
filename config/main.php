<?php

return [
    /**
     * Paths configuration.
     */
    'path' => [
        /**
         * Base directories where to publish package file groups.
         */
        'publish' => [
            'config' => config_path('vendor/softworx'),
            'lang' => 'lang/vendor/softworx',
            'views' => 'views/vendor/softworx',
            'migrations' => 'vendor/softworx',
        ],
    ],
    /**
     * Key-classname definition used for polymorphic relations.
     *
     * 'polymorphism' => [
     *   '<model-short-classname>' => '<model-full-classname>',
     *   ...
     * ],
     */
    'polymorphism' => [
    ],
    /**
     * List of Models that should be forceDeleted even if they use SoftDeletes trait.
     */
    'force_delete' => [
    ],
];
