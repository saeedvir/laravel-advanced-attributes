<?php

return [
    /*
     * Table config
     *
     * Here it's a config of migrations.
     */
    'tables' => [
        /*
         * Get table name of migration.
         */
        'attributes' => 'attributes',
        'attributables' => 'attributables',
    ],

    /*
     * Model class name for attributes and attributables table.
     */
    'attributes_model' => \Saeedvir\LaravelAdvancedAttributes\Attribute::class,
    'attributables_model' =>  Saeedvir\LaravelAdvancedAttributes\Attributable::class,
];
