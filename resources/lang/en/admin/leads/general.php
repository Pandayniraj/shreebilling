<?php

return [

    'audit-log'           => [
        'category'              => 'Leads',
        'msg-index'             => 'Accessed list of leads.',
        'msg-show'              => 'Accessed details of lead: :name.',
        'msg-store'             => 'Created new lead: :name.',
        'msg-edit'              => 'Initiated edit of lead: :name.',
        'msg-update'            => 'Submitted edit of lead: :name.',
        'msg-destroy'           => 'Deleted lead: :name.',
        'msg-enable'            => 'Enabled lead: :name.',
        'msg-disabled'          => 'Disabled lead: :name.',
        'msg-enabled-selected'  => 'Enabled multiple leads.',
        'msg-disabled-selected' => 'Disabled multiple leads.',
    ],

    'status'              => [
        'created'                   => 'Lead successfully created',
        'updated'                   => 'Lead successfully updated',
        'deleted'                   => 'Lead successfully deleted',
        'global-enabled'            => 'Selected leads enabled.',
        'global-disabled'           => 'Selected leads disabled.',
        'enabled'                   => 'Lead enabled.',
        'disabled'                  => 'Lead disabled.',
        'no-lead-selected'          => 'No lead selected.',
    ],

    'error'               => [
        'cant-delete-this-lead' => 'This lead cannot be deleted',
        'cant-edit-this-lead'   => 'This lead cannot be edited',
    ],

    'page'              => [
        'index'              => [
            'title'             => 'Admin | Leads',
            'description'       => 'List of leads',
            'table-title'       => 'Lead list',
        ],
        'show'              => [
            'title'             => 'Admin | Lead | Show',
            'description'       => 'Displaying lead: :name',
            'section-title'     => 'Lead details',
        ],
        'create'            => [
            'title'            => 'Admin | Lead | Create',
            'description'      => 'Creating a new lead',
            'section-title'    => 'New lead',
        ],
        'edit'              => [
            'title'            => 'Admin | Lead | Edit',
            'description'      => 'Editing lead: :name',
            'section-title'    => 'Edit lead',
        ],
    ],

    'columns'           => [
        'id'                        =>  'ID',
        'title'                     =>  'Title',
        'name'                      =>  'Lead Name',
        'price_value'               =>  'Price Value',
        'mob_phone'               	=>  'Mobile Phone',
        'home_phone'                =>  'Landline Number',
        'address_line_1'            =>  'Address',
        'address_line_2'            =>  'Address 2',
        'city'                      =>  'City',
        'country'                   =>  'Country',
        'description'               =>  'Description',
        'guardian_name'           	=>  'Guardian Name',
        'guardian_phone'            =>  'Guardian Phone',
        'email'                     =>  'Email',
        'grade'             		=>  'Grade',
        'awarding_body'				=>	'Awarding Body',
        'qualification'             =>  'Qualification',
        'homepage'                  =>  'Homepage',
        'contact_id'                =>  'Contact',
        'position'                  =>  'Messenger',
        'department'                =>  'Organization',
        'course_name'               =>  'Product',
        'intake'       				=>  'Intake',
        'communication_name'        =>  'Communication Source',
        'enquiry_mode_name'         =>  'Enquiry Mode',
        'company_name'              =>  'Company',
        'status_id'                 =>  'Lead Status',
        'user'                   	=>  'Lead Owner',
        'rating'                    =>  'Rating',
        'contact'                   =>  'Contact',
        'lead_type'                 =>  'Lead Type',
        'actions'                   =>  'Actions',
        'enabled'                   =>  'Enabled',
        'resync_on_login'			=>	'Resync On Login',
        'email_opt_out'             =>  'Email Opt Out',
        'date'						=>	'Date',
    ],

    'button'               => [
        'create'    =>  'Create new lead',
    ],

];
