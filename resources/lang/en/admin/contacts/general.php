<?php

return [

    'audit-log'           => [
        'category'              => 'Contacts',
        'msg-index'             => 'Accessed list of contacts.',
        'msg-show'              => 'Accessed details of contact: :name.',
        'msg-store'             => 'Created new contact: :name.',
        'msg-edit'              => 'Initiated edit of contact: :name.',
        'msg-update'            => 'Submitted edit of contact: :name.',
        'msg-destroy'           => 'Deleted contact: :name.',
        'msg-enable'            => 'Enabled contact: :name.',
        'msg-disabled'          => 'Disabled contact: :name.',
        'msg-enabled-selected'  => 'Enabled multiple contacts.',
        'msg-disabled-selected' => 'Disabled multiple contacts.',
    ],

    'status'              => [
        'created'                   => 'Contact successfully created',
        'updated'                   => 'Contact successfully updated',
        'deleted'                   => 'Contact successfully deleted',
        'global-enabled'            => 'Selected contacts enabled.',
        'global-disabled'           => 'Selected contacts disabled.',
        'enabled'                   => 'Contact enabled.',
        'disabled'                  => 'Contact disabled.',
        'no-contact-selected'          => 'No contact selected.',
    ],

    'error'               => [
        'cant-delete-this-contact' => 'This contact cannot be deleted',
        'cant-edit-this-contact'   => 'This contact cannot be edited',
    ],

    'page'              => [
        'index'              => [
            'title'             => 'Admin | Contacts',
            'description'       => 'List of contacts',
            'table-title'       => 'Contact list',
        ],
        'show'              => [
            'title'             => 'Admin | Contact | Show',
            'description'       => 'Displaying contact: :name',
            'section-title'     => 'Contact details',
        ],
        'create'            => [
            'title'            => 'Admin | Contact | Create',
            'description'      => 'Creating a new contact',
            'section-title'    => 'New contact',
        ],
        'edit'              => [
            'title'            => 'Admin | Contact | Edit',
            'description'      => 'Editing contact: :name',
            'section-title'    => 'Edit contact',
        ],
    ],

    'columns'           => [
        'id'                        => 'ID',
        'client'                    => 'Client',
        'client_id'                 => 'Client ID',
        'position'                  => 'Position',
        'department'                => 'Department',
        'salutation'                => 'Salutation',
        'full_name'                 => 'Full Name',
        'email_1'                   => 'Primary Email',
        'email_2'                   => 'Secondary Email',
        'phone'                     => 'Phone',
        'landline'                  => 'Land Line',
        'address'                   => 'Address',
        'city'                      => 'City',
        'postcode'                  => 'Post Code',
        'country'                   => 'Country',
        'website'                   => 'Website',
        'facebook'                  => 'Facebook',

        'actions'                   => 'Actions',
        'enabled'                   => 'Enabled',
        'resync_on_login'			=> 'Resync On Login',
    ],

    'button'               => [
        'create'    =>  'Create new contact',
    ],

];
