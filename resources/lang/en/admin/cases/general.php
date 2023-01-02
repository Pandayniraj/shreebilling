<?php

return [

    'audit-log'           => [
        'category'              => 'Cases',
        'msg-index'             => 'Accessed list of cases.',
        'msg-show'              => 'Accessed details of case: :name.',
        'msg-store'             => 'Created new case: :name.',
        'msg-edit'              => 'Initiated edit of case: :name.',
        'msg-update'            => 'Submitted edit of case: :name.',
        'msg-destroy'           => 'Deleted case: :name.',
        'msg-enable'            => 'Enabled case: :name.',
        'msg-disabled'          => 'Disabled case: :name.',
        'msg-enabled-selected'  => 'Enabled multiple cases.',
        'msg-disabled-selected' => 'Disabled multiple cases.',
    ],

    'status'              => [
        'created'                   => 'Case successfully created',
        'updated'                   => 'Case successfully updated',
        'deleted'                   => 'Case successfully deleted',
        'global-enabled'            => 'Selected cases enabled.',
        'global-disabled'           => 'Selected cases disabled.',
        'enabled'                   => 'Case enabled.',
        'disabled'                  => 'Case disabled.',
        'no-case-selected'          => 'No case selected.',
    ],

    'error'               => [
        'cant-delete-this-case' => 'This case cannot be deleted',
        'cant-edit-this-case'   => 'This case cannot be edited',
    ],

    'page'              => [
        'index'              => [
            'title'             => 'Admin | Cases',
            'description'       => 'List of cases',
            'table-title'       => 'Case list',
        ],
        'show'              => [
            'title'             => 'Admin | Case | Show',
            'description'       => 'Displaying case: :name',
            'section-title'     => 'Case details',
        ],
        'create'            => [
            'title'            => 'Admin | Case | Create',
            'description'      => 'Creating a new case',
            'section-title'    => 'New case',
        ],
        'edit'              => [
            'title'            => 'Admin | Case | Edit',
            'description'      => 'Editing case: :name',
            'section-title'    => 'Edit case',
        ],
    ],

    'columns'           => [
        'id'                        =>  'ID',
        'user'                      =>  'User',
        'priority'                  =>  'Priority',
        'status'                    =>  'Status',
        'type'                      =>  'Type',
        'contact_id'                =>  'Contact',
        'client_id'                 =>  'Client',
        'subject'                   =>  'Subject',
        'description'               =>  'Description',
        'resolution'                =>  'Resolution',
        'assigned_to'               =>  'Assigned To',
        'ticket_name'               =>  'Ticket Name ',
        'ticket_email'               => 'Ticket Email',
        'actions'                   =>  'Actions',
        'enabled'                   =>  'Enabled',
        'resync_on_login'			=>	'Resync On Login',
    ],

    'button'               => [
        'create'    =>  'Create new case',
    ],

];
