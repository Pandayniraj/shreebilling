<?php

return [

    'audit-log'           => [
        'category'              => 'Intakes',
        'msg-index'             => 'Accessed list of intakes.',
        'msg-show'              => 'Accessed details of intake: :name.',
        'msg-store'             => 'Created new intake: :name.',
        'msg-edit'              => 'Initiated edit of intake: :name.',
        'msg-update'            => 'Submitted edit of intake: :name.',
        'msg-destroy'           => 'Deleted intake: :name.',
        'msg-enable'            => 'Enabled intake: :name.',
        'msg-disabled'          => 'Disabled intake: :name.',
        'msg-enabled-selected'  => 'Enabled multiple intakes.',
        'msg-disabled-selected' => 'Disabled multiple intakes.',
    ],

    'status'              => [
        'created'                   => 'Intake successfully created',
        'updated'                   => 'Intake successfully updated',
        'deleted'                   => 'Intake successfully deleted',
        'global-enabled'            => 'Selected intakes enabled.',
        'global-disabled'           => 'Selected intakes disabled.',
        'enabled'                   => 'Intake enabled.',
        'disabled'                  => 'Intake disabled.',
        'no-intake-selected'          => 'No intake selected.',
    ],

    'error'               => [
        'cant-delete-this-intake' => 'This intake cannot be deleted',
        'cant-edit-this-intake'   => 'This intake cannot be edited',
    ],

    'page'              => [
        'index'              => [
            'title'             => 'Admin | Intakes',
            'description'       => 'List of intakes',
            'table-title'       => 'Intake list',
        ],
        'show'              => [
            'title'             => 'Admin | Intake | Show',
            'description'       => 'Displaying intake: :name',
            'section-title'     => 'Intake details',
        ],
        'create'            => [
            'title'            => 'Admin | Intake | Create',
            'description'      => 'Creating a new intake',
            'section-title'    => 'New intake',
        ],
        'edit'              => [
            'title'            => 'Admin | Intake | Edit',
            'description'      => 'Editing intake: :name',
            'section-title'    => 'Edit intake',
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
        'create'    =>  'Create new intake',
    ],

];
