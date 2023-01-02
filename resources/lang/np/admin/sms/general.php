<?php

return [

    'audit-log'           => [
        'category'              => 'Sms',
        'msg-index'             => 'Accessed list of sms.',
        'msg-show'              => 'Accessed details of sms: :name.',
        'msg-store'             => 'Created new sms: :name.',
        'msg-edit'              => 'Initiated edit of sms: :name.',
        'msg-update'            => 'Submitted edit of sms: :name.',
        'msg-destroy'           => 'Deleted sms: :name.',
        'msg-enable'            => 'Enabled sms: :name.',
        'msg-disabled'          => 'Disabled sms: :name.',
        'msg-enabled-selected'  => 'Enabled multiple sms.',
        'msg-disabled-selected' => 'Disabled multiple sms.',
        'send-sms'				=> 'Sent SMS',
    ],

    'status'              => [
        'created'                   => 'Sms successfully created',
        'updated'                   => 'Sms successfully updated',
        'deleted'                   => 'Sms successfully deleted',
        'global-enabled'            => 'Selected sms enabled.',
        'global-disabled'           => 'Selected sms disabled.',
        'enabled'                   => 'Sms enabled.',
        'disabled'                  => 'Sms disabled.',
        'no-sms-selected'           => 'No sms selected.',
        'sms-sent'                  => 'Sms Sent.',
        'no-msg'                  	=> 'Message field is empty.',
        'sms-err'                   => 'Error in sending data to server.',
        'no-lead-selected'			=> 'No Lead is selected.',
    ],

    'error'               => [
        'cant-delete-this-sms' => 'This sms cannot be deleted',
        'cant-edit-this-sms'   => 'This sms cannot be edited',
    ],

    'page'              => [
        'index'              => [
            'title'             => 'Admin | Sms',
            'description'       => 'List of sms',
            'table-title'       => 'Sms list',
        ],
        'show'              => [
            'title'             => 'Admin | Sms | Show',
            'description'       => 'Displaying sms: :name',
            'section-title'     => 'Sms details',
        ],
        'send'            => [
            'title'            => 'Admin | Send | Sms',
            'description'      => 'Send Sms',
            'section-title'    => 'Send Sms',
        ],
        'edit'              => [
            'title'            => 'Admin | Sms | Edit',
            'description'      => 'Editing sms: :name',
            'section-title'    => 'Edit sms',
        ],
    ],

    'columns'           => [
        'id'                        =>  'ID',
        'name'                      =>  'Name',
        'actions'                   =>  'Actions',
        'enabled'                   =>  'Enabled',
        'recipient'                 =>  'Recipient mobile numbers',
        'message'                   =>  'Message',
        'resync_on_login'			=>	'Resync On Login',
    ],

    'button'               => [
        'create'    =>  'Create new sms',
    ],

];
