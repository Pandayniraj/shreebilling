<?php

return [

    'audit-log'           => [
        'category'              => 'Lead Files',
        'msg-index'             => 'Accessed list of lead Files.',
        'msg-show'              => 'Accessed details of lead file: :name.',
        'msg-store'             => 'Created new lead file: :name.',
        'msg-edit'              => 'Initiated edit of lead file: :name.',
        'msg-update'            => 'Submitted edit of lead file: :name.',
        'msg-destroy'           => 'Deleted lead file: :name.',
        'msg-enable'            => 'Enabled lead file: :name.',
        'msg-disabled'          => 'Disabled lead file: :name.',
        'msg-enabled-selected'  => 'Enabled multiple lead Files.',
        'msg-disabled-selected' => 'Disabled multiple lead Files.',
    ],

    'status'              => [
        'created'                   => 'lead file successfully created',
        'updated'                   => 'lead file successfully updated',
        'deleted'                   => 'lead file successfully deleted',
        'global-enabled'            => 'Selected lead Files enabled.',
        'global-disabled'           => 'Selected lead Files disabled.',
        'enabled'                   => 'lead file enabled.',
        'disabled'                  => 'lead file disabled.',
        'no-lead file-selected'     => 'No lead file selected.',
    ],

    'error'               => [
        'cant-delete-this-lead file' => 'This lead file cannot be deleted',
        'cant-edit-this-lead file'   => 'This lead file cannot be edited',
    ],

    'page'              => [
        'index'              => [
            'title'             => 'Admin | Lead Files',
            'description'       => 'List of lead Files',
            'table-title'       => 'lead file list',
        ],
        'show'              => [
            'title'             => 'Admin | lead file | Show',
            'description'       => 'Displaying lead file: :name',
            'section-title'     => 'lead file details',
        ],
        'create'            => [
            'title'            => 'Admin | lead file | Create',
            'description'      => 'Creating a new lead file',
            'section-title'    => 'New lead file',
        ],
        'edit'              => [
            'title'            => 'Admin | lead file | Edit',
            'description'      => 'Editing lead file: :name',
            'section-title'    => 'Edit lead file',
        ],
    ],

    'button'               => [
        'create'    =>  'Create new lead file',
    ],

];
