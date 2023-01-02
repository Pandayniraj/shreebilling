<?php

return [

    'audit-log'           => [
        'category'              => 'Bugs',
        'msg-index'             => 'Accessed list of bugs.',
        'msg-show'              => 'Accessed details of bug: :name.',
        'msg-store'             => 'Created new bug: :name.',
        'msg-edit'              => 'Initiated edit of bug: :name.',
        'msg-update'            => 'Submitted edit of bug: :name.',
        'msg-destroy'           => 'Deleted bug: :name.',
        'msg-enable'            => 'Enabled bug: :name.',
        'msg-disabled'          => 'Disabled bug: :name.',
        'msg-enabled-selected'  => 'Enabled multiple bugs.',
        'msg-disabled-selected' => 'Disabled multiple bugs.',
    ],

    'status'              => [
        'created'                   => 'bug successfully created',
        'updated'                   => 'bug successfully updated',
        'deleted'                   => 'bug successfully deleted',
        'global-enabled'            => 'Selected bugs enabled.',
        'global-disabled'           => 'Selected bugs disabled.',
        'enabled'                   => 'bug enabled.',
        'disabled'                  => 'bug disabled.',
        'no-bug-selected'          => 'No bug selected.',
    ],

    'error'               => [
        'cant-delete-this-bug' => 'This bug cannot be deleted',
        'cant-edit-this-bug'   => 'This bug cannot be edited',
    ],

    'page'              => [
        'index'              => [
            'title'             => 'Admin | Bugs',
            'description'       => 'List of Bugs',
            'table-title'       => 'bug list',
        ],
        'show'              => [
            'title'             => 'Admin | bug | Show',
            'description'       => 'Displaying bug: :name',
            'section-title'     => 'bug details',
        ],
        'create'            => [
            'title'            => 'Admin | bug | Create',
            'description'      => 'Creating a new bug',
            'section-title'    => 'New bug',
        ],
        'edit'              => [
            'title'            => 'Admin | bug | Edit',
            'description'      => 'Editing bug: :name',
            'section-title'    => 'Edit bug',
        ],
    ],

    'columns'           => [
        'id'                        =>  'ID',
        'category'                  =>  'Category',
        'source'                    =>  'Bug Source',
        'user_id'                   =>  'User',
        'priority'                  =>  'Priority',
        'status'                    =>  'Status',
        'type'                      =>  'Type',
        'contact_id'                =>  'Contact',
        'client_id'                 =>  'Client',
        'subject'                   =>  'Subject',
        'found_in_release'          =>  'Found in Release',
        'fixed_in_release'          =>  'Fixed in Release',
        'description'               =>  'Description',
        'resolution'                =>  'Resolution',
        'assigned_to'               =>  'Assigned To',
        'actions'                   =>  'Actions',
        'enabled'                   =>  'Enabled',
        'resync_on_login'			=>	'Resync On Login',
    ],

    'button'               => [
        'create'    =>  'Create new bug',
    ],

];
