<?php

return [

    'audit-log'           => [
        'category'              => 'Leadtypes',
        'msg-index'             => 'Accessed list of leadtypes.',
        'msg-show'              => 'Accessed details of leadtype: :name.',
        'msg-store'             => 'Created new leadtype: :name.',
        'msg-edit'              => 'Initiated edit of leadtype: :name.',
        'msg-update'            => 'Submitted edit of leadtype: :name.',
        'msg-destroy'           => 'Deleted leadtype: :name.',
        'msg-enable'            => 'Enabled leadtype: :name.',
        'msg-disabled'          => 'Disabled leadtype: :name.',
        'msg-enabled-selected'  => 'Enabled multiple leadtypes.',
        'msg-disabled-selected' => 'Disabled multiple leadtypes.',
    ],

    'status'              => [
        'created'                   => 'Leadtype successfully created',
        'updated'                   => 'Leadtype successfully updated',
        'deleted'                   => 'Leadtype successfully deleted',
        'global-enabled'            => 'Selected leadtypes enabled.',
        'global-disabled'           => 'Selected leadtypes disabled.',
        'enabled'                   => 'Leadtype enabled.',
        'disabled'                  => 'Leadtype disabled.',
        'no-leadtype-selected'          => 'No leadtype selected.',
    ],

    'error'               => [
        'cant-delete-this-leadtype' => 'This leadtype cannot be deleted',
        'cant-edit-this-leadtype'   => 'This leadtype cannot be edited',
    ],

    'page'              => [
        'index'              => [
            'title'             => 'Admin | Leadtypes',
            'description'       => 'List of leadtypes',
            'table-title'       => 'Leadtype list',
        ],
        'show'              => [
            'title'             => 'Admin | Leadtype | Show',
            'description'       => 'Displaying leadtype: :name',
            'section-title'     => 'Leadtype details',
        ],
        'create'            => [
            'title'            => 'Admin | Leadtype | Create',
            'description'      => 'Creating a new leadtype',
            'section-title'    => 'New leadtype',
        ],
        'edit'              => [
            'title'            => 'Admin | Leadtype | Edit',
            'description'      => 'Editing leadtype: :name',
            'section-title'    => 'Edit leadtype',
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
        'create'    =>  'Create new leadtype',
    ],

];
