<?php
return [
        'title'                 => 'Notifications',
        'mark-all-as-read'      => 'Mark All As Read',
        'no-notifications-found'=> 'No notifications found.',
        'new'                   => 'New',
        'view-details'          => 'View Details',
        'admin' => [
            'title'                 => 'Message',
            'success' => 'Your message has been sent successfully!' ,
            'error'   => 'An error occurred while sending the message Please try again.',
            'data_fail' => 'The submitted data is invalid. Please check again.',
            'connect_fail' => 'Unable to connect to server. Please check your network connection and try again.',
            'status_options' =>[
                'pending'  => 'Pending' ,
                'received' => 'Received' ,
                'replied' => 'Replied' ,
            ],
            'messages' => [
                'view' => [
                    'title'              => 'View Message',
                    'title_from'         => 'Message from :name',
                    'back'               => 'Back to List',
                    'sender_name'        => 'Sender Name',
                    'email'              => 'Email',
                    'gender'             => 'Gender',
                    'gender_options'     => [
                        'male'   => 'Male',
                        'female' => 'Female',
                        'other'  => 'Other',
                    ],
                    'request_id' => 'Request Id'  ,
                    'subject'            => 'Subject',
                    'message'            => 'Message',
                    'images'             => 'Images',
                    'videos'             => 'Videos',
                    'status'             => 'Status',
                    'yes'                => 'Yes',
                    'no'                 => 'No',
                    'received_at'        => 'Received At',
                    'mark_as_replied'    => 'Send',
                    'marked_success'     => 'Message marked as replied.',
                    'created_at'         => 'Created at'
                ],
            ],
        ],

        'contact' => [
            'title'                     => 'Contact Us',
            'list'                      => 'List Request',
            'contact-person'            => 'Contact Person',
            'contact-person-placeholder'=> 'Enter your name',
            'email'                     => 'Email',
            'email-placeholder'         => 'Enter your email',
            'gender'                    => 'Gender',
            'select-gender'             => 'Select Gender',
            'gender-male'               => 'Male',
            'gender-female'             => 'Female',
            'gender-other'              => 'Other',
            'subject'                   => 'Subject', // New
            'subject-placeholder'       => 'Enter the subject of your message', // New
            'message'                   => 'Message',
            'message-placeholder'       => 'Enter your message here...',
            'upload-images'             => 'Upload Images',
            'upload-images-placeholder' => 'Select multiple image files',
            'upload-videos'             => 'Upload Videos',
            'upload-videos-placeholder' => 'Select multiple video files',
            'submit'                    => 'Send',
        ],
        
];

