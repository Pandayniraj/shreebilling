<?php

return [

    'audit-log'           => [
        'category'              => 'Projects',
        'msg-index'             => 'Accessed list of projects.',
        'msg-show'              => 'Accessed details of project: :name.',
        'msg-store'             => 'Created new project: :name.',
        'msg-edit'              => 'Initiated edit of project: :name.',
        'msg-update'            => 'Submitted edit of project: :name.',
        'msg-destroy'           => 'Deleted project: :name.',
        'msg-enable'            => 'Enabled project: :name.',
        'msg-disabled'          => 'Disabled project: :name.',
        'msg-enabled-selected'  => 'Enabled multiple projects.',
        'msg-disabled-selected' => 'Disabled multiple projects.',
    ],

    'status'              => [
        'created'                   => 'Project successfully created',
        'updated'                   => 'Project successfully updated',
        'deleted'                   => 'Project successfully deleted',
        'global-enabled'            => 'Selected projects enabled.',
        'global-disabled'           => 'Selected projects disabled.',
        'enabled'                   => 'Project enabled.',
        'disabled'                  => 'Project disabled.',
        'no-project-selected'          => 'No project selected.',
    ],

    'error'               => [
        'cant-delete-this-project' => 'This project cannot be deleted',
        'cant-edit-this-project'   => 'This project cannot be edited',
    ],

    'page'              => [
        'index'              => [
            'title'             => 'Admin | Projects',
            'description'       => 'List of projects',
            'table-title'       => 'Project list',
        ],
        'show'              => [
            'title'             => 'Admin | Project | Show',
            'description'       => 'Displaying project: :name',
            'section-title'     => 'Project details',
        ],
        'create'            => [
            'title'            => 'Admin | Project | Create',
            'description'      => 'Creating a new project',
            'section-title'    => 'New project',
        ],
        'edit'              => [
            'title'            => 'Admin | Project | Edit',
            'description'      => 'Editing project: :name',
            'section-title'    => 'Edit project',
        ],
    ],

    'columns'           => [
        'id'                        =>  'ID',
        'name'                      =>  'नाम',
        'assign_to'                 =>  'परियोजना प्रबन्ध',
        'description'               =>  'वर्णन',
        'start_date'                =>  'सुरू मिति',
        'end_date'                  =>  'अन्त्य मिति',
        'status'                    =>  'स्थिति',
        'actions'                   =>  'कार्यहरू',
        'enabled'                   =>  'सक्षम गरिएको',
        'resync_on_login'           =>  'लगइनमा पुनःसिy्क गर्नुहोस्',

        'user'=>'प्रयोगकर्ता',

        'subject'=>'बिषय',
        'class'=>'कक्षा',
        'manager'=>'प्रबन्धक',
        'staffs'=>'कर्मचारीहरु',
        'category'=>'वर्ग',
        'tagline'=>'ट्यागलाइन',
        'summary'=>'सारांश',
        'task'=>'कार्य',
        'due'=>'अन्त्य',
        'priority'=>'प्राथमिकता',
        'owner'=>'मालिक',
        'stage'=>'स्तर',

        'project'=>'प्रोजेक्ट',
        'project_task'=>'प्रोजेक्ट कार्य',
        'people'=>'मान्चेहरु',
        'start_date'=>'सुरू मिति',
        'end_date'=>'अन्त्य मिति',
        'progress'=>'प्रगति',

        'title'=>'शीर्षक',
        'select_project'=>'प्रोजेक्ट छनौट गर्नुहोस्',
        'description'=>'वर्णन',
        'add_task'=>'काम थप्नुहोस्',

        'staffs_involved'=>'कर्मचारी संलग्न',

        'estimated_duration'=>'अनुमानित अवधि',
        'schedule'=>'तालिका',
        'attachment'=>'संलग्नक',

        'estimated'=>'अनुमानित',
        'deadlines'=>'समयसीमा',

        'activity'=>'गतिविधि',
        'date'=>'मिति',

    ],

    'button'               => [
        'create_project'    =>  'नयाँ प्रोजेक्ट सिर्जना गर्नुहोस्',
        'quick_task'=>'द्रुत कार्य',
        'edit_project'=>'परियोजना सम्पादन गर्नुहोस्',
        'create_task'=>'कार्य सिर्जना गर्नुहोस्',
        'backlogs'=>'ब्याकलोगहरू',
        'activity'=>'गतिविधि',
        'filter'=>'छनौट गर्नुहोस्',
        'reset'=>'रिसेट गर्नुहोस्',
        'send'=>'पठाउनुहोस्',
        'save_close'=>'बचत गर्नुहोस् र बन्द गर्नुहोस्',
        'edit'=>'सम्पादन गर्नुहोस्',
        'delete'=>'मेटाउन',
    ],

    'placeholder'=>[
        'search_project'=>'प्रोजेक्ट खोज्नुहोस्',
        'start_date'=>'सुरू मिति',
        'end_date'=>'अन्त्य मिति',
        'comment_post'=>'टिप्पणी गर्न पोस्ट प्रविष्ट गर्नुहोस्',

    ],

    'titles'=>[

        'activity_logs' =>'कार्य गतिविधि लग',
    ],

];
