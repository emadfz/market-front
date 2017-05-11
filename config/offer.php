<?php

$hours  = date('Y-m-d H:i:s',strtotime('-1 hour'));
$week   = date('Y-m-d H:i:s',strtotime('-1 week'));
$month  = date('Y-m-d H:i:s',strtotime('-1 month'));
$year   = date('Y-m-d H:i:s',strtotime('-1 year'));

return [

    /*
      |--------------------------------------------------------------------------
      | 
      |--------------------------------------------------------------------------
     */
    'period_array' => [
        $hours      => 'Last 24 Hours',
        $week       => 'Last Week',
        $month      => 'Last Month',
        $year       => 'Last Year',
    ],

    'offer_status_array' => [
        'All'           => 'All',
        'accept'        => 'Accepted',
        'reject'        => 'Rejected',
    ],
];