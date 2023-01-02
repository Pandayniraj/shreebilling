<?php

return [

    'audit-log'           => [
        'category'              => 'Online Leads',
        'msg-index'             => 'Accessed list of online leads.',
        'msg-show'              => 'Accessed details of online lead: :name.',
        'msg-store'             => 'Created new online lead: :name.',
        'msg-edit'              => 'Initiated edit of online lead: :name.',
        'msg-update'            => 'Submitted edit of online lead: :name.',
        'msg-destroy'           => 'Deleted online lead: :name.',
        'msg-enable'            => 'Enabled online lead: :name.',
        'msg-disabled'          => 'Disabled online lead: :name.',
        'msg-enabled-selected'  => 'Enabled multiple online leads.',
        'msg-disabled-selected' => 'Disabled multiple online leads.',
    ],

    'status'              => [
        'created'                   => 'Online Lead successfully created',
        'updated'                   => 'Online Lead successfully updated',
        'deleted'                   => 'Online Lead successfully deleted',
        'global-enabled'            => 'Selected online leads enabled.',
        'global-disabled'           => 'Selected online leads disabled.',
        'enabled'                   => 'Online Lead enabled.',
        'disabled'                  => 'Online Lead disabled.',
        'no-lead-selected'          => 'No online lead selected.',
    ],

    'error'               => [
        'cant-delete-this-lead' => 'This online lead cannot be deleted',
        'cant-edit-this-lead'   => 'This online lead cannot be edited',
    ],

    'page'              => [
        'index'              => [
            'title'             => 'Admin | Online Leads',
            'description'       => 'List of online leads',
            'table-title'       => 'Online Lead list',
        ],
        'show'              => [
            'title'             => 'Admin | Online Lead | Show',
            'description'       => 'Displaying online lead: :name',
            'section-title'     => 'Online Lead details',
        ],
        'create'            => [
            'title'            => 'Admin | Online Lead | Create',
            'description'      => 'Creating a new online lead',
            'section-title'    => 'New online lead',
        ],
        'edit'              => [
            'title'            => 'Admin | Online Lead | Edit',
            'description'      => 'Editing online lead: :name',
            'section-title'    => 'Edit online lead',
        ],
    ],

    'columns'           => [
        'id'                        =>  'ID',
        'title'                     =>  'Title',
        'name'                      =>  'Full Name',
        'dob'                       =>  'Date of Birth',
        'mob_phone'               	=>  'Mobile Phone',
        'home_phone'                =>  'Landline Number',
        'address_line_1'            =>  'Address',
        'address_line_2'            =>  'Address 2',
        'city'                      =>  'City',
        'guardian_name'           	=>  'Guardian Name',
        'guardian_phone'            =>  'Guardian Phone',
        'email'                     =>  'Email',
        'grade'             		=>  'Grade',
        'awarding_body'				=>	'Awarding Body',
        'qualification'             =>  'Qualification',
        'homepage'                  =>  'Homepage',
        'course_name'               =>  'Course',
        'intake_session_name'       =>  'Intake Session',
        'intake'       				=>  'Intake',
        'communication_name'        =>  'Communication',
        'enquiry_mode_name'         =>  'Enquiry Mode',
        'company_name'              =>  'School / College / University',
        'status_id'                 =>  'Lead Status',
        'form_status'               =>  'Form Status',
        'admission_process_id'		=>	'Admission Process',
        'user'                   	=>  'User',
        'rating'                   	=>  'Rating',
        'actions'                   =>  'Actions',
        'enabled'                   =>  'Enabled',
        'resync_on_login'			=>	'Resync On Login',
        'date'						=>	'Date',
    ],

    'button'               => [
        'create'    =>  'Create new lead',
    ],

];
