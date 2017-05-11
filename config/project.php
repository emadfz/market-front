<?php

return [

    /*
      |--------------------------------------------------------------------------
      | 
      |--------------------------------------------------------------------------
     */
    'advertisement_main'   =>  'http://php54.indianic.com/marketplace/public/images/advertisements/main/',
    'admin_route'   =>  'http://php54.indianic.com/marketplace/public',
    'department_product_limit'=>'18',
    'category_product_limit'=>'12',
    'display_forum_admin_name'=>'Admin',
    'forum_listing_limit'=>'10',
    'report_flag'=>array('1'=>'Inappropriate Content','2'=>'It\'s spam or a scam','3'=>'It\'s annoying or not interesting'),
    'pricings_array' => [
                            ['from'=>0,'to'=>100],
                            ['from'=>101,'to'=>500],
                            ['from'=>501,'to'=>1000],
                            ['from'=>1001,'to'=>20000],
    ],
    'mingle_listing_limit'=>'12',
    'mingle'=>
        ['message'=>
            ['numberOfUsersPerPage'=>3]
        ],
    'offer_available_limit'=>'2',
    'buyaction_filters' => [
         ''=>'Select',
        '24'=>'Last 24 Hours',
        'week'=>'Last Week',
        'month'=>'Last Month',
        'year'=>'Last Year'
    ],
    'classified_expiration_day'=>'30',
];
