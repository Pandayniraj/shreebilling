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
        'last_updated'                 =>  'पछिल्लो अपडेट',
        'subject'                   =>  'विषय',
        'from'                   => 'पठाउने',
        'status'                   =>  'स्थिति',
        'user'                      =>  'प्रयोगकर्ता',
        'date'=>'मिति',
        'darta_number'=>'दर्त नम्बर',
        'letter_number'=>'पत्र नम्बर',
        'ticket_number'=>'टिकट नम्बर',
        'received_org'=>'रिसीभर संगठन',
         'received_letter_number'=>'पत्र नम्बर प्राप्त भयो',
         'received_date'=>'प्राप्त भएको मिति',
        'sending_office_name'=>'कार्यालयको नाम पठाउँदै',
        'receiving_office_name'=>'अधिकारी नाम प्राप्त गर्दै',
        'remarks'=>'टिप्पणीहरू',
        'action'=>'कार्य',
    ],

    'button'               => [
        'create'    =>  'Create',
        'create_ticket'    =>  'Create new Ticket',
        'search'    =>  'Search',
        'clear'    =>  'Clear',
        'update'=>'Update',

    ],

    'placeholder'=>[
        'search'=>'Type to search..',
        'send_response'=>'Send Response to Client',
        'select'=>'Select',
        'detail_reason'=>'Details Reason for Opening Tickets',

    ],

    'form_header'=>[
        'user_and_callaborations'=>'User and Collaborators',
        'ticket_info_option'=>'Ticket Information and Options',
        'ticket_detail'=>'Ticket Details:',
        'issue_describe'=>'Please Describe Your Issue',
        'add_more_file'=>'Add More Files',
        'ticket_threads'=>'Ticket Threads',
        'response'=>'Response',

    ],

];
