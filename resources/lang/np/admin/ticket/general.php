<?php

return [

    'audit-log'           => [
        'category'              => 'Activities',
        'msg-index'             => 'Accessed list of activities.',
        'msg-show'              => 'Accessed details of activity: :name.',
        'msg-store'             => 'Created new task: :name.',
        'msg-edit'              => 'Initiated edit of activity: :name.',
        'msg-update'            => 'Submitted edit of activity: :name.',
        'msg-destroy'           => 'Deleted activity: :name.',
        'msg-enable'            => 'Enabled activity: :name.',
        'msg-disabled'          => 'Disabled activity: :name.',
        'msg-enabled-selected'  => 'Enabled multiple activity.',
        'msg-disabled-selected' => 'Disabled multiple activity.',
    ],

    'status'              => [
        'created'                   => 'Activities successfully created',
        'updated'                   => 'Activities successfully updated',
        'deleted'                   => 'Activities successfully deleted',
        'global-enabled'            => 'Selected tasks enabled.',
        'global-disabled'           => 'Selected tasks disabled.',
        'enabled'                   => 'Task enabled.',
        'disabled'                  => 'Task disabled.',
        'no-task-selected'          => 'No task selected.',
    ],

    'error'               => [
        'cant-delete-this-task' => 'This activity cannot be deleted',
        'cant-edit-this-task'   => 'This activity cannot be edited',
    ],

    'page'              => [
        'index'              => [
            'title'             => 'Admin | Activities',
            'description'       => 'List of activities',
            'table-title'       => 'Activities list',
        ],
        'show'              => [
            'title'             => 'Admin | Activities | Show',
            'description'       => 'Displaying activity: :name',
            'section-title'     => 'Activities details',
        ],
        'create'            => [
            'title'            => 'Admin | Activities | Create',
            'description'      => 'Creating a new activity',
            'section-title'    => 'New activity',
        ],
        'edit'              => [
            'title'            => 'Admin | Activities | Edit',
            'description'      => 'Editing activity: :name',
            'section-title'    => 'Edit activity',
        ],
    ],

    'columns'           => [
        'id'                        =>  'ID',
        'ticket'                      => 'टिकट',
        'last_updated'                 =>  'अन्तिम अपडेट',
        'subject'                   =>  'विषय',
        'from'                   => 'प्रेषक',
        'status'                   =>  'स्थिति',
        'user'                      =>  'प्रयोगकर्ता',
        'cc'=>'CC',
        'notice'=>'सूचना',
        'source'=>'स्रोत',
        'help_topic'=>'मद्दत शीर्षक',
        'department'=>'विभाग',
        'sla_plan'=>'SLA योजना',
        'due_date'=>'मिति',
        'issue_summary'=>'मुद्दा सारांश',
        'detail_reason'=>'विस्तृत कारण',
        'ticket_status'=>'टिकट स्थिति',
        'internal_notes'=>'आन्तरिक नोटहरू',
        'assigned_to'               =>  'को लागी काम',
        'date'                      =>  'मिति',
        'actions'               	=>  'कार्य',
        'full_name'=>'पुरा नाम',
        'email'=>'ईमेल',
        'phone_number'=>'फोन नम्बर',
    ],

    'button'               => [
        'create_ticket'    =>  'नयाँ टिकट सिर्जना गर्नुहोस्',
        'create'    =>  'सिर्जना गर्नुहोस्',
        'search'    =>  'खोजी गर्नुहोस्',
        'clear'    =>  'स्पष्ट',
         'update'=>'अद्यावधिक गर्नुहोस्',
    ],

    'placeholder'=>[
        'search'=>'खोजी गर्न टाइप गर्नुहोस्..',
        'send_response'=>'Send Response to Client',
        'select'=>'Select',
        'detail_reason'=>'Details Reason for Opening Tickets',

    ],

     'form_header'=>[
        'user_and_callaborations'=>'प्रयोगकर्ता र सहयोगीहरू',
        'ticket_info_option'=>'टिकट जानकारी र विकल्पहरू',
        'ticket_detail'=>'टिकट विवरण',
        'issue_describe'=>'कृपया तपाइँको मुद्दा वर्णन गर्नुहोस्',
        'add_more_file'=>'थप फाईलहरू थप्नुहोस्',
        'ticket_threads'=>'टिकट थ्रेडहरू',
        'response'=>'प्रतिक्रिया',

    ],

];
