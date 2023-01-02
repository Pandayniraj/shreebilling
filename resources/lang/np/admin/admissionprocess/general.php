<?php

return [

    'audit-log'           => [
        'category'              => 'Admission Process',
        'msg-index'             => 'Accessed list of Admission Process.',
        'msg-show'              => 'Accessed details of Admission Process: :name.',
        'msg-store'             => 'Created new Admission Process: :name.',
        'msg-edit'              => 'Initiated edit of Admission Process: :name.',
        'msg-update'            => 'Submitted edit of Admission Process: :name.',
        'msg-destroy'           => 'Deleted Admission Process: :name.',
        'msg-enable'            => 'Enabled Admission Process: :name.',
        'msg-disabled'          => 'Disabled Admission Process: :name.',
        'msg-enabled-selected'  => 'Enabled multiple Admission Process.',
        'msg-disabled-selected' => 'Disabled multiple Admission Process.',
    ],

    'status'              => [
        'created'                   => 'AdmissionProcess successfully created',
        'updated'                   => 'AdmissionProcess successfully updated',
        'deleted'                   => 'AdmissionProcess successfully deleted',
        'global-enabled'            => 'Selected Admission Process enabled.',
        'global-disabled'           => 'Selected Admission Process disabled.',
        'enabled'                   => 'AdmissionProcess enabled.',
        'disabled'                  => 'AdmissionProcess disabled.',
        'no-Admission Process-selected'          => 'No Admission Process selected.',
    ],

    'error'               => [
        'cant-delete-this-Admission Process' => 'This Admission Process cannot be deleted',
        'cant-edit-this-Admission Process'   => 'This Admission Process cannot be edited',
    ],

    'page'              => [
        'index'              => [
            'title'             => 'Admin | Admission Process',
            'description'       => 'List of Admission Process',
            'table-title'       => 'AdmissionProcess list',
        ],
        'show'              => [
            'title'             => 'Admin | AdmissionProcess | Show',
            'description'       => 'Displaying Admission Process: :name',
            'section-title'     => 'AdmissionProcess details',
        ],
        'create'            => [
            'title'            => 'Admin | AdmissionProcess | Create',
            'description'      => 'Creating a new Admission Process',
            'section-title'    => 'New Admission Process',
        ],
        'edit'              => [
            'title'            => 'Admin | AdmissionProcess | Edit',
            'description'      => 'Editing Admission Process: :name',
            'section-title'    => 'Edit Admission Process',
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
        'create'    =>  'Create new Admission Process',
    ],

];
