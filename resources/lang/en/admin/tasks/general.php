<?php

return [

    'audit-log'           => [
        'category'              => 'Activities',
        'msg-index'             => 'Accessed list of activities.',
        'msg-show'              => 'Accessed details of activity: :name.',
        'msg-store'             => 'Created new task: :name.',
        'msg-edit'              => 'Initiated edit of activity: :name.',
        'msg-update'            => 'Submitted edit of activity: :name.',
        'msg-destroy'           => 'Deleted activity: :name.',
        'msg-enable'            => 'Enabled activity: :name.',
        'msg-disabled'          => 'Disabled activity: :name.',
        'msg-enabled-selected'  => 'Enabled multiple activity.',
        'msg-disabled-selected' => 'Disabled multiple activity.',
    ],

    'status'              => [
        'created'                   => 'Activities successfully created',
        'updated'                   => 'Activities successfully updated',
        'deleted'                   => 'Activities successfully deleted',
        'global-enabled'            => 'Selected tasks enabled.',
        'global-disabled'           => 'Selected tasks disabled.',
        'enabled'                   => 'Task enabled.',
        'disabled'                  => 'Task disabled.',
        'no-task-selected'          => 'No task selected.',
    ],

    'error'               => [
        'cant-delete-this-task' => 'This activity cannot be deleted',
        'cant-edit-this-task'   => 'This activity cannot be edited',
    ],

    'page'              => [
        'index'              => [
            'title'             => 'Admin | Activities',
            'description'       => 'List of activities',
            'table-title'       => 'Activities list',
        ],
        'show'              => [
            'title'             => 'Admin | Activities | Show',
            'description'       => 'Displaying activity: :name',
            'section-title'     => 'Activities details',
        ],
        'create'            => [
            'title'            => 'Admin | Activities | Create',
            'description'      => 'Creating a new activity',
            'section-title'    => 'New activity',
        ],
        'edit'              => [
            'title'            => 'Admin | Activities | Edit',
            'description'      => 'Editing activity: :name',
            'section-title'    => 'Edit activity',
        ],
    ],

    'columns'           => [
        'id'                        =>  'ID',
        'lead'                      =>  'Related Lead',
        'task_type'                 =>  'Type',
        'contact'                   =>  'Contact',
        'location'                   => 'Location',
        'duration'                   =>  'Duration',
        'user'                      =>  'User',
        'lead_id'                   =>  'Lead ID',
        'task_subject'              =>  'Subject',
        'task_detail'               =>  'Detail',
        'task_status'            	=>  'Status',
        'task_owner'            	=>  'Owner',
        'task_assign_to'            =>  'Assigned To',
        'task_priority'           	=>  'Priority',
        'task_start_date'           =>  'Start Date',
        'task_due_date'             =>  'Due Date',
        'task_complete_percent'     =>  'Complete Percentage',
        'task_alert'             	=>  'Task Alert',
        'task_enable'               =>  'Enable',
        'assigned_to'               =>  'Work For',
        'date'               		=>  'Date',
        'actions'               	=>  'Action',
    ],

    'button'               => [
        'create'    =>  'Create new Activity',
    ],

];
