<?php

return [

    'audit-log'           => [
        'category'              => 'Awarding Body',
        'msg-index'             => 'Accessed list of awardingbody.',
        'msg-show'              => 'Accessed details of awardingbody: :name.',
        'msg-store'             => 'Created new awardingbody: :name.',
        'msg-edit'              => 'Initiated edit of awardingbody: :name.',
        'msg-update'            => 'Submitted edit of awardingbody: :name.',
        'msg-destroy'           => 'Deleted awardingbody: :name.',
        'msg-enable'            => 'Enabled awardingbody: :name.',
        'msg-disabled'          => 'Disabled awardingbody: :name.',
        'msg-enabled-selected'  => 'Enabled multiple awardingbody.',
        'msg-disabled-selected' => 'Disabled multiple awardingbody.',
    ],

    'status'              => [
        'created'                   => 'Awarding Body successfully created',
        'updated'                   => 'Awarding Body successfully updated',
        'deleted'                   => 'Awarding Body successfully deleted',
        'global-enabled'            => 'Selected awardingbody enabled.',
        'global-disabled'           => 'Selected awardingbody disabled.',
        'enabled'                   => 'Awarding Body enabled.',
        'disabled'                  => 'Awarding Body disabled.',
        'no-awardingbody-selected'          => 'No awardingbody selected.',
    ],

    'error'               => [
        'cant-delete-this-awardingbody' => 'This awardingbody cannot be deleted',
        'cant-edit-this-awardingbody'   => 'This awardingbody cannot be edited',
    ],

    'page'              => [
        'index'              => [
            'title'             => 'Admin | Awarding Body',
            'description'       => 'List of awardingbody',
            'table-title'       => 'Awarding Body list',
        ],
        'show'              => [
            'title'             => 'Admin | Awarding Body | Show',
            'description'       => 'Displaying awardingbody: :title',
            'section-title'     => 'Awarding Body details',
        ],
        'create'            => [
            'title'            => 'Admin | Awarding Body | Create',
            'description'      => 'Creating a new awardingbody',
            'section-title'    => 'New awardingbody',
        ],
        'edit'              => [
            'title'            => 'Admin | Awarding Body | Edit',
            'description'      => 'Editing awardingbody: :title',
            'section-title'    => 'Edit awardingbody',
        ],
    ],

    'columns'           => [
        'id'                        =>  'ID',
        'title'                      =>  'Title',
        'actions'                   =>  'Actions',
        'enabled'                   =>  'Enabled',
        'resync_on_login'			=>	'Resync On Login',
    ],

    'button'               => [
        'create'    =>  'Create new awardingbody',
    ],

];
