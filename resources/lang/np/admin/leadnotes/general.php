<?php

return [

    'audit-log'           => [
        'category'              => 'Lead notes',
        'msg-index'             => 'Accessed list of lead notes.',
        'msg-show'              => 'Accessed details of lead note: :name.',
        'msg-store'             => 'Created new lead note: :name.',
        'msg-edit'              => 'Initiated edit of lead note: :name.',
        'msg-update'            => 'Submitted edit of lead note: :name.',
        'msg-destroy'           => 'Deleted lead note: :name.',
        'msg-enable'            => 'Enabled lead note: :name.',
        'msg-disabled'          => 'Disabled lead note: :name.',
        'msg-enabled-selected'  => 'Enabled multiple lead notes.',
        'msg-disabled-selected' => 'Disabled multiple lead notes.',
    ],

    'status'              => [
        'created'                   => 'Lead Note successfully created',
        'updated'                   => 'Lead Note successfully updated',
        'deleted'                   => 'Lead Note successfully deleted',
        'global-enabled'            => 'Selected lead notes enabled.',
        'global-disabled'           => 'Selected lead notes disabled.',
        'enabled'                   => 'Lead Note enabled.',
        'disabled'                  => 'Lead Note disabled.',
        'no-lead note-selected'     => 'No lead note selected.',
    ],

    'error'               => [
        'cant-delete-this-lead note' => 'This lead note cannot be deleted',
        'cant-edit-this-lead note'   => 'This lead note cannot be edited',
    ],

    'page'              => [
        'index'              => [
            'title'             => 'Admin | Lead notes',
            'description'       => 'List of lead notes',
            'table-title'       => 'Lead Note list',
        ],
        'show'              => [
            'title'             => 'Admin | Lead Note | Show',
            'description'       => 'Displaying lead note: :name',
            'section-title'     => 'Lead Note details',
        ],
        'create'            => [
            'title'            => 'Admin | Lead Note | Create',
            'description'      => 'Creating a new lead note',
            'section-title'    => 'New lead note',
        ],
        'edit'              => [
            'title'            => 'Admin | Lead Note | Edit',
            'description'      => 'Editing lead note: :name',
            'section-title'    => 'Edit lead note',
        ],
    ],

    'button'               => [
        'create'    =>  'Create new lead note',
    ],

];
