<?php

return [

    'audit-log'           => [
        'category'              => 'Mails',
        'msg-index'             => 'Accessed list of mails.',
        'msg-show'              => 'Accessed details of mail: :mail_from.',
        'msg-store'             => 'Created new mail: :mail_from.',
        'msg-edit'              => 'Initiated edit of mail: :mail_from.',
        'msg-update'            => 'Submitted edit of mail: :mail_from.',
        'msg-destroy'           => 'Deleted mail: :mail_from.',
        'msg-enable'            => 'Enabled mail: :mail_from.',
        'msg-disabled'          => 'Disabled mail: :mail_from.',
        'msg-enabled-selected'  => 'Enabled multiple mails.',
        'msg-disabled-selected' => 'Disabled multiple mails.',
    ],

    'status'              => [
        'sent'                   => 'Mail successfully sent',
        'received'                   => 'Mail Received',
        'created'                   => 'Mail successfully created',
        'updated'                   => 'Mail successfully updated',
        'deleted'                   => 'Mail successfully deleted',
        'global-enabled'            => 'Selected mails enabled.',
        'global-disabled'           => 'Selected mails disabled.',
        'enabled'                   => 'Mail enabled.',
        'disabled'                  => 'Mail disabled.',
        'no-mail-selected'          => 'No mail selected.',
    ],

    'error'               => [
        'cant-delete-this-mail' => 'This mail cannot be deleted',
        'cant-edit-this-mail'   => 'This mail cannot be edited',
    ],

    'page'              => [
        'index'              => [
            'title'             => 'Admin | Mails',
            'description'       => 'List of mails',
            'table-title'       => 'Mail list',
        ],
        'show'              => [
            'title'             => 'Admin | Mail | Show',
            'description'       => 'Displaying mail: :mail_from',
            'section-title'     => 'Mail details',
        ],
        'create'            => [
            'title'            => 'Admin | Mail | Create',
            'description'      => 'Creating a new mail',
            'section-title'    => 'New mail',
        ],
        'edit'              => [
            'title'            => 'Admin | Mail | Edit',
            'description'      => 'Editing mail: :mail_from',
            'section-title'    => 'Edit mail',
        ],
    ],

    'inbox'              => [
        'index'              => [
            'title'             => 'Admin | Mails | Inbox',
            'description'       => 'List of inbox mails',
            'table-title'       => 'Inbox Mail list',
        ],
        'show'              => [
            'title'             => 'Admin | Mail | Show',
            'description'       => 'Displaying mail: :mail_from',
            'section-title'     => 'Mail details',
        ],
    ],

    'sent'              => [
        'index'              => [
            'title'             => 'Admin | Mails | Sent',
            'description'       => 'List of sent mails',
            'table-title'       => 'Sent Mail list',
        ],
        'show'              => [
            'title'             => 'Admin | Mail | Show',
            'description'       => 'Displaying sent mail: :mail_to',
            'section-title'     => 'Mail details',
        ],
    ],

    'columns'           => [
        'id'                        =>  'ID',
        'mail_from'                 =>  'From',
        'mail_to'                   =>  'To',
        'subject'                   =>  'Suject',
        'enabled'                   =>  'Enabled',
        'resync_on_login'			=>	'Resync On Login',
    ],

    'button'               => [
        'create'    =>  'Create new mail',
    ],

];
