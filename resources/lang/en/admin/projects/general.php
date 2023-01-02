<?php

return [

    'audit-log'           => [
        'category'              => 'Projects',
        'msg-index'             => 'Accessed list of projects.',
        'msg-show'              => 'Accessed details of project: :name.',
        'msg-store'             => 'Created new project: :name.',
        'msg-edit'              => 'Initiated edit of project: :name.',
        'msg-update'            => 'Submitted edit of project: :name.',
        'msg-destroy'           => 'Deleted project: :name.',
        'msg-enable'            => 'Enabled project: :name.',
        'msg-disabled'          => 'Disabled project: :name.',
        'msg-enabled-selected'  => 'Enabled multiple projects.',
        'msg-disabled-selected' => 'Disabled multiple projects.',
    ],

    'status'              => [
        'created'                   => 'Project successfully created',
        'updated'                   => 'Project successfully updated',
        'deleted'                   => 'Project successfully deleted',
        'global-enabled'            => 'Selected projects enabled.',
        'global-disabled'           => 'Selected projects disabled.',
        'enabled'                   => 'Project enabled.',
        'disabled'                  => 'Project disabled.',
        'no-project-selected'          => 'No project selected.',
    ],

    'error'               => [
        'cant-delete-this-project' => 'This project cannot be deleted',
        'cant-edit-this-project'   => 'This project cannot be edited',
    ],

    'page'              => [
        'index'              => [
            'title'             => 'Admin | Projects',
            'description'       => 'List of projects',
            'table-title'       => 'Project list',
        ],
        'show'              => [
            'title'             => 'Admin | Project | Show',
            'description'       => 'Displaying project: :name',
            'section-title'     => 'Project details',
        ],
        'create'            => [
            'title'            => 'Admin | Project | Create',
            'description'      => 'Creating a new project',
            'section-title'    => 'New project',
        ],
        'edit'              => [
            'title'            => 'Admin | Project | Edit',
            'description'      => 'Editing project: :name',
            'section-title'    => 'Edit project',
        ],
    ],

    'columns'           => [
        'id'                        =>  'ID',
        'name'                      =>  'Name',
        'assign_to'                 =>  'Project Manager',
        'description'               =>  'Description',
        'start_date'                =>  'Start Date',
        'end_date'                  =>  'End Date',
        'status'                    =>  'Status',
        'actions'                   =>  'Actions',
        'enabled'                   =>  'Enabled',
        'resync_on_login'			=>	'Resync On Login',

        'user'=>'User',

        'subject'=>'Subject',
        'class'=>'Class',
        'manager'=>'Manager',
        'staffs'=>'Staffs',
        'category'=>'Categories',
        'tagline'=>'Tagline',
        'summary'=>'Summary',
        'task'=>'Task',
        'due'=>'Due',
        'priority'=>'Priority',
        'owner'=>'Owner',
        'stage'=>'Stage',

        'project'=>'Project',
        'project_task'=>'Project Task',
        'people'=>'Peoples',
          'start_date'=>'Start Date',
        'end_date'=>'End Date',
        'progress'=>'Progress',

        'title'=>'Title',
        'select_project'=>'Select Project',
        'description'=>'Description',
        'add_task'=>'Add Task',

        'staffs_involved'=>'Staff Involved',

        'select_project'=>'Select Project',
        'estimated_duration'=>'Estimated Duration',
        'schedule'=>'Schedule',
        'attachment'=>'Attachment',

        'estimated'=>'Estimated',
        'deadlines'=>'Deadlines',

        'activity'=>'Activity',
        'date'=>'Date',

    ],

    'button'               => [
        'create_project'    =>  'Create new project',
        'quick_task'=>'Quick Task',
        'edit_project'=>'Edit Project',
        'create_task'=>'Create task',
        'backlogs'=>'Backlogs',
        'activity'=>'Activity',
        'filter'=>'Filter',
        'reset'=>'Reset',
        'send'=>'Send',
        'save_close'=>'Save & Close',
        'edit'=>'Edit',
        'delete'=>'Delete',
    ],

    'placeholder'=>[
        'search_project'=>'Search Project',
        'start_date'=>'Start Date',
        'end_date'=>'End Date',
        'comment_post'=>'Please Enter Post To Comment',

    ],

    'titles'=>[

        'activity_logs' =>'Task Activities logs of',
    ],

];
