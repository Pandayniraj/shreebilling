<?php

return [

    'audit-log'           => [
        'category'              => 'Email Campaign',
        'msg-index'             => 'Accessed list of email campaigns.',
        'msg-show'              => 'Accessed details of email campaign: :name.',
        'msg-store'             => 'Created new email campaign: :name.',
        'msg-edit'              => 'Initiated edit of email campaign: :name.',
        'msg-update'            => 'Submitted edit of email campaign: :name.',
        'msg-destroy'           => 'Deleted email campaign: :name.',
        'msg-enable'            => 'Enabled email campaign: :name.',
        'msg-disabled'          => 'Disabled email campaign: :name.',
        'msg-enabled-selected'  => 'Enabled multiple email campaigns.',
        'msg-disabled-selected' => 'Disabled multiple email campaigns.',
    ],

    'status'              => [
        'created'                   => 'Email Campaign successfully created',
        'updated'                   => 'Email Campaign successfully updated',
        'deleted'                   => 'Email Campaign successfully deleted',
        'global-enabled'            => 'Selected email campaigns enabled.',
        'global-disabled'           => 'Selected email campaigns disabled.',
        'enabled'                   => 'Email Campaign enabled.',
        'disabled'                  => 'Email Campaign disabled.',
        'no-email campaign-selected'          => 'No email campaign selected.',
    ],

    'error'               => [
        'cant-delete-this-email campaign' => 'This email campaign cannot be deleted',
        'cant-edit-this-email campaign'   => 'This email campaign cannot be edited',
    ],

    'page'              => [
        'index'              => [
            'title'             => 'Admin | Email Campaign',
            'description'       => 'List of email campaigns',
            'table-title'       => 'Email Campaign list',
        ],
        'show'              => [
            'title'             => 'Admin | Email Campaign | Show',
            'description'       => 'Displaying email campaign: :name',
            'section-title'     => 'Email Campaign details',
        ],
        'create'            => [
            'title'            => 'Admin | Email Campaign | Create',
            'description'      => 'Creating a new email campaign',
            'section-title'    => 'New email campaign',
        ],
        'edit'              => [
            'title'            => 'Admin | Email Campaign | Edit',
            'description'      => 'Editing email campaign: :name',
            'section-title'    => 'Edit email campaign',
        ],
    ],

    'columns'           => [
        'id'                        =>  'ID',
        'title'                     =>  'Title',
        'subject'                   =>  'Subject',
        'message'                   =>  'Message',
        'attachement'               =>  'Attachement',
        'email_queue'               =>  'Email in Queue',
        'condition_detail'          =>  'Condition Detail',
        'db_query'                  =>  'DB Query',
        'total_email'               =>  'Total Emails',
        'campaign_start_date'       =>  'Campaign Start Date',
        'total_email_sent'          =>  'Total Email Sent',
        'last_lead_id'              =>  'Last Lead ID',
        'last_sent_date'            =>  'Last Sent Date',
        'resync_on_login'			=>	'Resync On Login',
        'actions'                   =>  'Actions',
        'enabled'                   =>  'Enabled',
    ],

    'button'               => [
        'create'    =>  'Create new email campaign',
    ],

];
