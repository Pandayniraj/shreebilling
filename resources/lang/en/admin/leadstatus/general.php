<?php

return [

    'audit-log'           => [
        'category'              => 'Leadstatus',
        'msg-index'             => 'Accessed list of leadstatus.',
        'msg-show'              => 'Accessed details of leadstatus: :name.',
        'msg-store'             => 'Created new leadstatus: :name.',
        'msg-edit'              => 'Initiated edit of leadstatus: :name.',
        'msg-update'            => 'Submitted edit of leadstatus: :name.',
        'msg-destroy'           => 'Deleted leadstatus: :name.',
        'msg-enable'            => 'Enabled leadstatus: :name.',
        'msg-disabled'          => 'Disabled leadstatus: :name.',
        'msg-enabled-selected'  => 'Enabled multiple leadstatus.',
        'msg-disabled-selected' => 'Disabled multiple leadstatus.',
    ],

    'status'              => [
        'created'                   => 'LeadStatus successfully created',
        'updated'                   => 'LeadStatus successfully updated',
        'deleted'                   => 'LeadStatus successfully deleted',
        'global-enabled'            => 'Selected leadstatus enabled.',
        'global-disabled'           => 'Selected leadstatus disabled.',
        'enabled'                   => 'LeadStatus enabled.',
        'disabled'                  => 'LeadStatus disabled.',
        'no-leadstatus-selected'          => 'No leadstatus selected.',
    ],

    'error'               => [
        'cant-delete-this-leadstatus' => 'This leadstatus cannot be deleted',
        'cant-edit-this-leadstatus'   => 'This leadstatus cannot be edited',
    ],

    'page'              => [
        'index'              => [
            'title'             => 'Admin | Leadstatus',
            'description'       => 'List of leadstatus',
            'table-title'       => 'LeadStatus list',
        ],
        'show'              => [
            'title'             => 'Admin | LeadStatus | Show',
            'description'       => 'Displaying leadstatus: :name',
            'section-title'     => 'LeadStatus details',
        ],
        'create'            => [
            'title'            => 'Admin | LeadStatus | Create',
            'description'      => 'Creating a new leadstatus',
            'section-title'    => 'New leadstatus',
        ],
        'edit'              => [
            'title'            => 'Admin | LeadStatus | Edit',
            'description'      => 'Editing leadstatus: :name',
            'section-title'    => 'Edit leadstatus',
        ],
    ],

    'columns'           => [
        'id'                        =>  'ID',
        'name'                      =>  'Name',
        'mob_phone'               	=>  'Mobile Phone',
        'home_phone'                =>  'Home Phone',
        'guardian_name'           	=>  'Guardian Name',
        'guardian_phone'            =>  'Guardian Phone',
        'email'                     =>  'Email',
        'qualification'             =>  'Qualification',
        'homepage'                  =>  'Homepage',
        'course_id'                 =>  'Course Id',
        'intake_session_name'       =>  'Intake Session Name',
        'intake_id'         		=>  'Intake Id',
        'communication_id'          =>  'Communication Id',
        'enquiry_mode_id'           =>  'Enquiry Mode Id',
        'status_id'                 =>  'Status Id',
        'user_id'                   =>  'User Id',
        'actions'                   =>  'Actions',
        'enabled'                   =>  'Enabled',
        'resync_on_login'			=>	'Resync On Login',
    ],

    'button'               => [
        'create'    =>  'Create new leadstatus',
    ],

];
