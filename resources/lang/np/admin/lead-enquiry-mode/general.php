<?php

return [

    'audit-log'           => [
        'category'              => 'Lead Enquiry Mode',
        'msg-index'             => 'Accessed list of Lead enquiry mode.',
        'msg-show'              => 'Accessed details of Lead enquiry mode: :name.',
        'msg-store'             => 'Created new Lead enquiry mode: :name.',
        'msg-edit'              => 'Initiated edit of Lead enquiry mode: :name.',
        'msg-update'            => 'Submitted edit of Lead enquiry mode: :name.',
        'msg-destroy'           => 'Deleted Lead enquiry mode: :name.',
        'msg-enable'            => 'Enabled Lead enquiry mode: :name.',
        'msg-disabled'          => 'Disabled Lead enquiry mode: :name.',
        'msg-enabled-selected'  => 'Enabled multiple Lead enquiry mode.',
        'msg-disabled-selected' => 'Disabled multiple Lead enquiry mode.',
    ],

    'status'              => [
        'created'                   => 'Lead Enquiry Mode successfully created',
        'updated'                   => 'Lead Enquiry Mode successfully updated',
        'deleted'                   => 'Lead Enquiry Mode successfully deleted',
        'global-enabled'            => 'Selected Lead enquiry mode enabled.',
        'global-disabled'           => 'Selected Lead enquiry mode disabled.',
        'enabled'                   => 'Lead Enquiry Mode enabled.',
        'disabled'                  => 'Lead Enquiry Mode disabled.',
        'no-Lead enquiry mode-selected'          => 'No Lead enquiry mode selected.',
    ],

    'error'               => [
        'cant-delete-this-leadenquirymode' => 'This Lead enquiry mode cannot be deleted',
        'cant-edit-this-leadenquirymode'   => 'This Lead enquiry mode cannot be edited',
    ],

    'page'              => [
        'index'              => [
            'title'             => 'Admin | Lead Enquiry Mode',
            'description'       => 'List of Lead enquiry mode',
            'table-title'       => 'Lead Enquiry Mode list',
        ],
        'show'              => [
            'title'             => 'Admin | Lead Enquiry Mode | Show',
            'description'       => 'Displaying Lead enquiry mode: :name',
            'section-title'     => 'Lead Enquiry Mode details',
        ],
        'create'            => [
            'title'            => 'Admin | Lead Enquiry Mode | Create',
            'description'      => 'Creating a new Lead enquiry mode',
            'section-title'    => 'New Lead enquiry mode',
        ],
        'edit'              => [
            'title'            => 'Admin | Lead Enquiry Mode | Edit',
            'description'      => 'Editing Lead enquiry mode: :name',
            'section-title'    => 'Edit Lead enquiry mode',
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
        'create'    =>  'Create new Lead enquiry mode',
    ],

];
