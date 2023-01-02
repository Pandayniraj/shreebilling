<?php

return [

    'audit-log'           => [
        'category'              => 'Project Tasks',
        'msg-index'             => 'Accessed list of project task.',
        'msg-show'              => 'Accessed details of project task: :name.',
        'msg-store'             => 'Created new project task: :name.',
        'msg-edit'              => 'Initiated edit of project task: :name.',
        'msg-update'            => 'Submitted edit of project task: :name.',
        'msg-destroy'           => 'Deleted project task: :name.',
        'msg-enable'            => 'Enabled project task: :name.',
        'msg-disabled'          => 'Disabled project task: :name.',
        'msg-enabled-selected'  => 'Enabled multiple project task.',
        'msg-disabled-selected' => 'Disabled multiple project task.',
    ],

    'status'              => [
        'created'                   => 'Project Task successfully created',
        'updated'                   => 'Project Task successfully updated',
        'deleted'                   => 'Project Task successfully deleted',
        'global-enabled'            => 'Selected project task enabled.',
        'global-disabled'           => 'Selected project task disabled.',
        'enabled'                   => 'Project Task enabled.',
        'disabled'                  => 'Project Task disabled.',
        'no-project task-selected'          => 'No project task selected.',
    ],

    'error'               => [
        'cant-delete-this-project task' => 'This project task cannot be deleted',
        'cant-edit-this-project task'   => 'This project task cannot be edited',
    ],

    'page'              => [
        'index'              => [
            'title'             => 'Admin | Project Tasks',
            'description'       => 'List of project task',
            'table-title'       => 'Project Task list',
        ],
        'show'              => [
            'title'             => 'Admin | Project Task | Show',
            'description'       => 'Displaying project task: :name',
            'section-title'     => 'Project Task details',
        ],
        'create'            => [
            'title'            => 'Admin | Project Task | Create',
            'description'      => 'Creating a new project task',
            'section-title'    => 'New project task',
        ],
        'edit'              => [
            'title'            => 'Admin | Project Task | Edit',
            'description'      => 'Editing project task: :name',
            'section-title'    => 'Edit project task',
        ],
    ],

    'columns'           => [
        'id'                        =>  'ID',
        'project'                   =>  'Project',
        'subject'                   =>  'Subject',
        'description'               =>  'Description',
        'percent_complete'          =>  'Percent Complete',
        'actual_duration'           =>  'Actual Duration',
        'duration'                  =>  'Estimated Duration',
        'estimated_effort'          =>  'Estimated Effort',
        'milestone'                 =>  'Milestone',
        'order'                     =>  'Order',
        'precede_tasks'             =>  'Precede Tasks',
        'priority'                  =>  'Priority',
        'peoples'                   =>  'Peoples',
        'end_date'                  =>  'End Date',
        'status'                    =>  'Status',
        'enabled'                   =>  'Enabled',
        'resync_on_login'			=>	'Resync On Login',
    ],

    'button'               => [
        'create'    =>  'Create new project task',
    ],

];
