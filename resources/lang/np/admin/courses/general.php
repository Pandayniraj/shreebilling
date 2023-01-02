<?php

return [

    'audit-log'           => [
        'category'              => 'Products',
        'msg-index'             => 'Accessed list of products.',
        'msg-show'              => 'Accessed details of product: :name.',
        'msg-store'             => 'Created new product: :name.',
        'msg-edit'              => 'Initiated edit of product: :name.',
        'msg-update'            => 'Submitted edit of product: :name.',
        'msg-destroy'           => 'Deleted product: :name.',
        'msg-enable'            => 'Enabled product: :name.',
        'msg-disabled'          => 'Disabled product: :name.',
        'msg-enabled-selected'  => 'Enabled multiple products.',
        'msg-disabled-selected' => 'Disabled multiple products.',
    ],

    'status'              => [
        'created'                   => 'Product successfully created',
        'updated'                   => 'Product successfully updated',
        'deleted'                   => 'Product successfully deleted',
        'global-enabled'            => 'Selected products enabled.',
        'global-disabled'           => 'Selected products disabled.',
        'enabled'                   => 'Product enabled.',
        'disabled'                  => 'Product disabled.',
        'no-product-selected'          => 'No product selected.',
    ],

    'error'               => [
        'cant-delete-this-product' => 'This product cannot be deleted',
        'cant-edit-this-product'   => 'This product cannot be edited',
    ],

    'page'              => [
        'index'              => [
            'title'             => 'Admin | Products',
            'description'       => 'List of products',
            'table-title'       => 'Product list',
        ],
        'show'              => [
            'title'             => 'Admin | Product | Show',
            'description'       => 'Displaying product: :name',
            'section-title'     => 'Product details',
        ],
        'create'            => [
            'title'            => 'Admin | Product | Create',
            'description'      => 'Creating a new product',
            'section-title'    => 'New product',
        ],
        'edit'              => [
            'title'            => 'Admin | Product | Edit',
            'description'      => 'Editing product: :name',
            'section-title'    => 'Edit product',
        ],
    ],

    'columns'           => [
        'id'                        =>  'ID',
        'name'                      =>  'Name',
        'actions'                   =>  'Actions',
        'enabled'                   =>  'Enabled',
        'resync_on_login'			=>	'Resync On Login',
    ],

    'button'               => [
        'create'    =>  'Create new product',
    ],

];
