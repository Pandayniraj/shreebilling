<?php

return [

    'audit-log'           => [
        'category'              => 'Clients',
        'msg-index'             => 'Accessed list of clients.',
        'msg-show'              => 'Accessed details of client: :name.',
        'msg-store'             => 'Created new client: :name.',
        'msg-edit'              => 'Initiated edit of client: :name.',
        'msg-update'            => 'Submitted edit of client: :name.',
        'msg-destroy'           => 'Deleted client: :name.',
        'msg-enable'            => 'Enabled client: :name.',
        'msg-disabled'          => 'Disabled client: :name.',
        'msg-enabled-selected'  => 'Enabled multiple clients.',
        'msg-disabled-selected' => 'Disabled multiple clients.',
    ],

    'status'              => [
        'created'                   => 'Client successfully created',
        'updated'                   => 'Client successfully updated',
        'deleted'                   => 'Client successfully deleted',
        'global-enabled'            => 'Selected clients enabled.',
        'global-disabled'           => 'Selected clients disabled.',
        'enabled'                   => 'Client enabled.',
        'disabled'                  => 'Client disabled.',
        'no-client-selected'          => 'No client selected.',
    ],

    'error'               => [
        'cant-delete-this-client' => 'This client cannot be deleted',
        'cant-edit-this-client'   => 'This client cannot be edited',
    ],

    'page'              => [
        'index'              => [
            'title'             => 'Admin | Clients',
            'description'       => 'List of clients',
            'table-title'       => 'Client list',
        ],
        'show'              => [
            'title'             => 'Admin | Client | Show',
            'description'       => 'Displaying client: :name',
            'section-title'     => 'Client details',
        ],
        'create'            => [
            'title'            => 'Admin | Client | Create',
            'description'      => 'Creating a new client',
            'section-title'    => 'New client',
        ],
        'edit'              => [
            'title'            => 'Admin | Client | Edit',
            'description'      => 'Editing client: :name',
            'section-title'    => 'Edit client',
        ],
    ],

    'columns'           => [
        'id'                        =>  'ID',
        'name'                      =>  'Company Name',
        'location'                  =>  'Location',
        'phone'                     =>  'Phone',
        'email'                     =>  'Email',
        'website'                   =>  'Website',
        'industry'                  =>  'Industry',
        'type'                      =>  'Type',
        'stock_symbol'              =>  'Stock Symbol',
        'actions'                   =>  'Actions',
        'enabled'                   =>  'Enabled',
        'resync_on_login'			=>	'Resync On Login',
    ],

    'button'               => [
        'create'    =>  'Create new client',
    ],

];
