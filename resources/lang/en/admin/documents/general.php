<?php

return [

    'audit-log'           => [
        'category'              => 'Documents',
        'msg-index'             => 'Accessed list of documents.',
        'msg-show'              => 'Accessed details of document: :name.',
        'msg-store'             => 'Created new document: :name.',
        'msg-edit'              => 'Initiated edit of document: :name.',
        'msg-update'            => 'Submitted edit of document: :name.',
        'msg-destroy'           => 'Deleted document: :name.',
        'msg-enable'            => 'Enabled document: :name.',
        'msg-disabled'          => 'Disabled document: :name.',
        'msg-enabled-selected'  => 'Enabled multiple documents.',
        'msg-disabled-selected' => 'Disabled multiple documents.',
    ],

    'status'              => [
        'created'                   => 'Document successfully created',
        'updated'                   => 'Document successfully updated',
        'deleted'                   => 'Document successfully deleted',
        'global-enabled'            => 'Selected documents enabled.',
        'global-disabled'           => 'Selected documents disabled.',
        'enabled'                   => 'Document enabled.',
        'disabled'                  => 'Document disabled.',
        'no-document-selected'          => 'No document selected.',
    ],

    'error'               => [
        'cant-delete-this-document' => 'This document cannot be deleted',
        'cant-edit-this-document'   => 'This document cannot be edited',
    ],

    'page'              => [
        'index'              => [
            'title'             => 'Admin | Documents',
            'description'       => 'List of documents',
            'table-title'       => 'Document list',
        ],
        'show'              => [
            'title'             => 'Admin | Document | Show',
            'description'       => 'Displaying document: :name',
            'section-title'     => 'Document details',
        ],
        'create'            => [
            'title'            => 'Admin | Document | Create',
            'description'      => 'Creating a new document',
            'section-title'    => 'New document',
        ],
        'edit'              => [
            'title'            => 'Admin | Document | Edit',
            'description'      => 'Editing document: :name',
            'section-title'    => 'Edit document',
        ],
        'share'=>[
            'title'=>'Share Document',
        ],
    ],

    'columns'           => [
        'id'                        =>  'ID',
        'user'                      =>  'User',
        'user_id'                   =>  'User',
        'contact_id'                =>  'Contact',
        'file'                      =>  'File',
        'doc_name'                  =>  'Name',
        'doc_type'                  =>  'Type',
        'doc_cats'                  =>  'Category',
        'doc_desc'                  =>  'Description',
        'show_in_portal'            =>  'Show in Portal',
        'enabled'                   =>  'Enabled',
        'actions'                   =>  'Actions',
        'resync_on_login'			=>	'Resync On Login',
        'home'=>'Home',

        'my_document' =>'My Document',
        'shared_document'=>'Document Shared With Me',
        'folder'=>'Folder',
        'file'=>'File',
        'category'=>'Category',
        'description'=>'Description',
        'name'=>'Name',
        'type'=>'Type',
        'enabled'=>'Enabled',

        'name'=>'Name',
        'designation'=>'Designation',
        'current_file'=>'Current File',
    ],

    'button'               => [
        'create'    =>  'Create new document',
        'upload_file'=>'Upload File',
        'doc_type'=>'Document Types',
        'share'=>'Share',
    ],

    'warning'=>[
        'choose_file'=>'Please Choose A file',
        'enter_doc'=>'Please Enter a doc name',
        'select_folder'=>'Please Selected a folder',
    ],
    'placeholder'=>[
        'search_doc'=>'Search a Document',

    ],

];
