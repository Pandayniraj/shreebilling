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
            'description'       => 'कागजातहरूको सूची',
            'table-title'       => 'कागजात सूची',
        ],
        'show'              => [
            'title'             => 'Admin | Document | Show',
            'description'       => 'Displaying document: :name',
            'section-title'     => 'Document details',
        ],
        'create'            => [
            'title'            => 'Admin | Document | Create',
            'description'      => 'नयाँ कागजात सिर्जना गर्दै',
            'section-title'    => 'नयाँ कागजात',
        ],
        'edit'              => [
            'title'            => 'Admin | Document | Edit',
            'description'      => 'Editing document: :name',
            'section-title'    => 'Edit document',
        ],
        'share'=>[
            'title'=>'कागजात साझा गर्नुहोस्',
        ],
    ],

    'columns'           => [
        'id'                        =>  'ID',
        'user'                      =>  'प्रयोगकर्ता',
        'user_id'                   =>  'प्रयोगकर्ता',
        'contact_id'                =>  'सम्पर्क',
        'file'                      =>  'फाईल',
        'doc_name'                  =>  'नाम',
        'doc_type'                  =>  'प्रकार',
        'doc_cats'                  =>  'कोटि',
        'doc_desc'                  =>  'वर्णन',
        'show_in_portal'            =>  'पोर्टलमा देखाउनुहोस्',
        'enabled'                   =>  'सक्षम गरिएको',
        'actions'                   =>  'कार्यहरू',
        'resync_on_login'           =>  'लगइनमा पुनःसिy्क गर्नुहोस्',
        'home'=>'होम',

        'my_document' =>'मेरो कागजात',
        'shared_document'=>'कागजात मसँग साझेदारी गरियो',
        'folder'=>'फोल्डर',

        'category'=>'वर्ग',
        'description'=>'वर्णन',
        'name'=>'नाम',
        'type'=>'प्रकार',
        'enabled'=>'सक्षम गरिएको',

        'designation'=>'पदनाम',

        'current_file'=>'वर्तमान फाईल',
    ],

    'button'               => [
        'create'    =>  'नयाँ कागजातवालाहरू सिर्जना गर्नुहोस्',
        'upload_file'=>'फाईल अपलोड गर्नुहोस्',
        'doc_type'=>'कागजात प्रकारहरू',
        'share'=>'सेयर गर्नुहोस्',
    ],

    'warning'=>[
        'choose_file'=>'कृपया फाईल छनौट गर्नुहोस्',
        'enter_doc'=>'कृपया कागजात नाम प्रविष्ट गर्नुहोस्',
        'select_folder'=>'कृपया एक फोल्डर चयन गर्नुहोस्',
    ],
    'placeholder'=>[
        'search_doc'=>'कागजात खोज्नुहोस्',

    ],

];
