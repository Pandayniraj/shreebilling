<?php

return [

    'audit-log'           => [
        'category'              => 'Communication',
        'msg-index'             => 'Accessed list of Communication.',
        'msg-show'              => 'Accessed details of Communication: :name.',
        'msg-store'             => 'Created new Communication: :name.',
        'msg-edit'              => 'Initiated edit of Communication: :name.',
        'msg-update'            => 'Submitted edit of Communication: :name.',
        'msg-destroy'           => 'Deleted Communication: :name.',
        'msg-enable'            => 'Enabled Communication: :name.',
        'msg-disabled'          => 'Disabled Communication: :name.',
        'msg-enabled-selected'  => 'Enabled multiple Communication.',
        'msg-disabled-selected' => 'Disabled multiple Communication.',
    ],

    'status'              => [
        'created'                   => 'Communication successfully created',
        'updated'                   => 'Communication successfully updated',
        'deleted'                   => 'Communication successfully deleted',
        'global-enabled'            => 'Selected Communication enabled.',
        'global-disabled'           => 'Selected Communication disabled.',
        'enabled'                   => 'Communication enabled.',
        'disabled'                  => 'Communication disabled.',
        'no-communication-selected'          => 'No Communication selected.',
    ],

    'error'               => [
        'cant-delete-this-communication' => 'This Communication cannot be deleted',
        'cant-edit-this-communication'   => 'This Communication cannot be edited',
    ],

    'page'              => [
        'index'              => [
            'title'             => 'Admin | Communication',
            'description'       => 'List of Communication',
            'table-title'       => 'Communication list',
        ],
        'show'              => [
            'title'             => 'Admin | Communication | Show',
            'description'       => 'Displaying Communication: :name',
            'section-title'     => 'Communication details',
        ],
        'create'            => [
            'title'            => 'Admin | Communication | Create',
            'description'      => 'Creating a new Communication',
            'section-title'    => 'New Communication',
        ],
        'edit'              => [
            'title'            => 'Admin | Communication | Edit',
            'description'      => 'Editing Communication: :name',
            'section-title'    => 'Edit Communication',
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
        'create'    =>  'Create new Communication',
    ],

];
