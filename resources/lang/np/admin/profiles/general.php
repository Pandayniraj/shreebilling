<?php

return [

    'audit-log'           => [
        'category'              => 'Profiles',
        'msg-index'             => 'Accessed list of profiles.',
        'msg-show'              => 'Accessed details of user: :username.',
        'msg-store'             => 'Created new user: :username.',
        'msg-edit'              => 'Initiated edit of user: :username.',
        'msg-replay-edit'       => 'Initiated replay edit of user: :username.',
        'msg-update'            => 'Submitted edit of user: :username.',
        'msg-destroy'           => 'Deleted user: :username.',
        'msg-enable'            => 'Enabled user: :username.',
        'msg-disabled'          => 'Disabled user: :username.',
        'msg-enabled-selected'  => 'Enabled multiple user.',
        'msg-disabled-selected' => 'Disabled multiple user.',
        'imapcategory'			=> 'Imap',
        'msg-imapstore'             => 'Created new imap for user: :username.',
        'msg-imapshow'              => 'Accessed imap details of user: :username.',
        'msg-imapedit'              => 'Initiated imap edit of user: :username.',
        'msg-imapupdate'            => 'Submitted imap edit of user: :username.',
    ],

    'status'              => [
        'created'                   => 'Profile successfully created',
        'updated'                   => 'Profile successfully updated',
        'deleted'                   => 'Profile successfully deleted',
        'global-enabled'            => 'Selected profiles enabled.',
        'global-disabled'           => 'Selected profiles disabled.',
        'enabled'                   => 'Profile enabled.',
        'disabled'                  => 'Profile disabled.',
        'no-user-selected'          => 'No user selected.',
        'imapstore'					=> 'Imap successfully created',
        'imapupdated'               => 'Imap successfully updated',
    ],

    'error'               => [
        'cant-be-edited'                => 'Profile cannot be edited',
        'cant-be-deleted'               => 'Profile cannot be deleted',
        'cant-be-disabled'              => 'Profile cannot be disabled',
        'login-failed-user-disabled'    => 'That account has been disabled.',
        'perm_not_found'                => 'Could not find permission #:id.',
        'user_not_found'                => 'Could not find user #:id.',
    ],

    'page'              => [
        'index'              => [
            'title'             => 'Admin | Profiles',
            'description'       => 'List of profiles',
            'table-title'       => 'Profile list',
        ],
        'show'              => [
            'title'             => 'Admin | Profile | Show',
            'description'       => 'Displaying user: :full_name',
            'section-title'     => 'Profile details',
        ],
        'create'            => [
            'title'            => 'Admin | Profile | Create',
            'description'      => 'Creating a new user',
            'section-title'    => 'New user',
        ],
        'edit'              => [
            'title'            => 'Admin | Profile | Edit',
            'description'      => 'Editing user: :full_name',
            'section-title'    => 'Edit user',
        ],
        'imapshow'              => [
            'title'             => 'Admin | Imap | Show',
            'description'       => 'Displaying user: :full_name',
            'section-title'     => 'Imap details',
        ],
        'imapedit'              => [
            'title'            => 'Admin | Imap | Edit',
            'description'      => 'Imap Editing user: :full_name',
            'section-title'    => 'Edit imap',
        ],
    ],

    'columns'           => [
        'id'                        =>  'ID',
        'username'                  =>  'Profile name',
        'first_name'                =>  'First name',
        'last_name'                 =>  'Last name',
        'name'                      =>  'Name',
        'assigned'                  =>  'Assigned',
        'roles'                     =>  'Roles',
        'roles-not-found'           =>  'Roles not found',
        'email'                     =>  'Email',
        'type'                      =>  'Type',
        'permissions'               =>  'Permissions',
        'permissions-not-found'     =>  'Permissions not found',
        'password'                  =>  'Password',
        'password_confirmation'     =>  'Password confirmation',
        'created'                   =>  'Created',
        'updated'                   =>  'Updated',
        'actions'                   =>  'Actions',
        'effective'                 =>  'Effective',
        'enabled'                   =>  'Enabled',
        'imap_email'                =>  'Imap Email',
        'imap_password'             =>  'Imap Password',
    ],

    'button'               => [
        'create'    =>  'Create new user',
    ],

];
