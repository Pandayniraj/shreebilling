<?php

return [

    'audit-log'           => [
        'category'              => 'Knowledge Base',
        'msg-index'             => 'Accessed list of Knowledge Base.',
        'msg-show'              => 'Accessed details of Knowledge Base: :name.',
        'msg-store'             => 'Created new Knowledge Base: :name.',
        'msg-edit'              => 'Initiated edit of Knowledge Base: :name.',
        'msg-update'            => 'Submitted edit of Knowledge Base: :name.',
        'msg-destroy'           => 'Deleted Knowledge Base: :name.',
        'msg-enable'            => 'Enabled Knowledge Base: :name.',
        'msg-disabled'          => 'Disabled Knowledge Base: :name.',
        'msg-enabled-selected'  => 'Enabled multiple Knowledge Base.',
        'msg-disabled-selected' => 'Disabled multiple Knowledge Base.',
    ],

    'status'              => [
        'created'                   => 'Knowledge Base successfully created',
        'updated'                   => 'Knowledge Base successfully updated',
        'deleted'                   => 'Knowledge Base successfully deleted',
        'global-enabled'            => 'Selected Knowledge Base enabled.',
        'global-disabled'           => 'Selected Knowledge Base disabled.',
        'enabled'                   => 'Knowledge Base enabled.',
        'disabled'                  => 'Knowledge Base disabled.',
        'no-Knowledge Base-selected'          => 'No Knowledge Base selected.',
    ],

    'error'               => [
        'cant-delete-this-Knowledge Base' => 'This Knowledge Base cannot be deleted',
        'cant-edit-this-Knowledge Base'   => 'This Knowledge Base cannot be edited',
    ],

    'page'              => [
        'index'              => [
            'title'             => 'Admin | Knowledge Base',
            'description'       => 'List of Knowledge Base',
            'table-title'       => 'Knowledge Base list',
        ],
        'show'              => [
            'title'             => 'Admin | Knowledge Base | Show',
            'description'       => 'Displaying Knowledge Base: :name',
            'section-title'     => 'Knowledge Base details',
        ],
        'create'            => [
            'title'            => 'Admin | Knowledge Base | Create',
            'description'      => 'Creating a new Knowledge Base',
            'section-title'    => 'New Knowledge Base',
        ],
        'edit'              => [
            'title'            => 'Admin | Knowledge Base | Edit',
            'description'      => 'Editing Knowledge Base: :name',
            'section-title'    => 'Edit Knowledge Base',
        ],
    ],

    'columns'           => [
        'id'                        =>  'ID',
        'author_id'                 =>  'Author',
        'title'                     =>  'Title',
        'description'               =>  'Description',
        'body'                      =>  'Body Content',
        'assigned_to'               =>  'Assigned To',
        'actions'                   =>  'Actions',
        'enabled'                   =>  'Enabled',
        'created_at'                =>  'Created Date',
    ],

    'button'               => [
        'create'    =>  'Create new Knowledge Base',
    ],

];
