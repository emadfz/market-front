<?php

/*
  |--------------------------------------------------------------------------
  | Helper Functions
  |--------------------------------------------------------------------------
  | General helper functions
  |
 */

use Carbon\Carbon;
use App\Models\Country;
use App\Models\Hobby;
use App\Models\State;
use App\Models\City;
use App\Models\Forum;
// users, admin_users
use App\Models\User;
use App\Models\AdminUser;

/* Event for notification email, alert, sms */
use App\Events\SendMail;
use App\Models\EmailTemplate;

//use Auth;
if (!function_exists('generateToken')) {

    /**
     * To generate token
     *
     * @param  string  $string (i.e email,username)
     * @return token string
     */
    function generateToken($string = '') {
        return sha1(uniqid("imf", true) . $string . str_random(60));
    }

}

if (!function_exists('convertToDateFormat')) {

    function convertToDateFormat($input, $format = 'Y-m-d') {
        $date = Carbon::parse($input);
        return $date->format($format);
    }

}

if (!function_exists('convertToDatetimeFormat')) {

    function convertToDatetimeFormat($input, $format = 'Y-m-d H:i:s') {
        $datetime = Carbon::parse($input);
        return $datetime->format($format);
    }

}

if (!function_exists('getCurrentDatetime')) {

    function getCurrentDatetime($format = 'Y-m-d H:i:s') {
        return Carbon::now()->format($format);
    }

}


if (!function_exists('isActivated')) {

    function isActivated() {
        return Auth::check() && Auth::user()->is_email_verified;
    }

}

if (!function_exists('isPhoneVerified')) {

    function isPhoneVerified() {
        return Auth::check() && Auth::user()->is_phone_verified;
    }

}

if (!function_exists('isMingleSync')) {

    function isMingleSync() {
        return Auth::check() && Auth::user()->is_mingle_sync;
    }

}

if (!function_exists('isLoggedin')) {

    function isLoggedin() {
        return Auth::check();
    }

}

if (!function_exists('loggedinUserId')) {

    function loggedinUserId() {
        return isLoggedin() ? Auth::user()->id : 0;
    }

}

if (!function_exists('loggedinUserType')) {

    function loggedinUserType() {
        return isLoggedin() ? Auth::user()->user_type : 0;
    }

}

if (!function_exists('getAllCountries')) {

    function getAllCountries($dropdown = FALSE) {
        $result = Country::select('id', 'country_name', 'country_code')->get(); //->where(['is_deleted' => 0])
        if ($dropdown) {
            $result = $result->pluck('country_name', 'id')->toArray();
        }
        return $result;
    }

}

if (!function_exists('getAllHobbies')) {

    function getAllHobbies($dropdown = FALSE) {
        $result = Hobby::select('id', 'name')->get(); //->where(['is_deleted' => 0])
        if ($dropdown) {
            $result = $result->pluck('name', 'id')->toArray();
        }
        return $result;
    }
}

if (!function_exists('getAllStates')) {

    /* function getAllStates($countryId) {
      return State::select('id', 'state_name', 'state_code')->where(['country_id' => $countryId])->get();
      } */

    function getAllStates($countryId, $dropdown = FALSE) {
        $result = State::select('id', 'state_name', 'state_code')->where(['country_id' => $countryId])->get();
        if ($dropdown) {
            $result = $result->pluck('state_name', 'id')->toArray();
        }
        return $result;
    }

}

if (!function_exists('getAllCities')) {

    /* function getAllCities($stateId) {
      return City::select('id', 'city_name', 'city_code')->where(['state_id' => $stateId])->get();
      } */

    function getAllCities($stateId, $dropdown = FALSE) {
        $result = City::select('id', 'city_name', 'city_code')->where(['state_id' => $stateId])->get();
        if ($dropdown) {
            $result = $result->pluck('city_name', 'id')->toArray();
        }
        return $result;
    }

}


/*
  |--------------------------------------------------------------------------
  | Common master entities
  |--------------------------------------------------------------------------
 */

if (!function_exists('getMasterEntityOptions')) {

    // Used for dropdown
    function getMasterEntityOptions($type = FALSE) {
        if (!$type)
            return FALSE;
        switch ($type) {
            case 'name_title':
                $options = ['' => trans('form.common.select_title'), 'Mr' => 'Mr.', 'Mrs' => 'Mrs.', 'Miss' => 'Miss.'];
                break;
            case 'gender':
                $options = ['' => trans('form.common.select_gender'), 'Male' => 'Male', 'Female' => 'Female', 'Prefer not to say' => 'Prefer not to say'];
                break;
            case 'payment_card_type':
                $options = ['' => trans('form.common.select_card_type'), 'Master' => 'Master', 'Visa' => 'Visa'];
                break;
            case 'position':
                $options = ['' => trans('form.common.select_position'), '1' => 'Position 1', '2' => 'Position 2', '3' => 'Position 3', '4' => 'Position 4', '5' => 'Other'];
                break;
            case 'warranty_type':
                $options = ['' => trans('form.common.select_warranty_type'), 'Seller' => 'Seller', 'Manufacturer' => 'Manufacturer'];
                break;
            case 'warranty_duration_type':
                $options = ['Days' => 'Days', 'Months' => 'Months', 'Years' => 'Years'];
                break;
            case 'return_acceptance_days':
                $options = [15 => 15, 20 => 20, 25 => 25, 30 => 30];
                break;
            case 'product_type':
                $options = ['Simple' => 'Simple', 'Combo' => 'Combo', 'Configurable' => 'Configurable'];
                break;
            case 'product_shipping_length_class':
                $options = ['cm' => 'Centimeters', 'mm' => 'Milimeters', 'in' => 'Inch'];
                break;
            case 'product_shipping_weight_class':
                $options = ['kg' => 'Kilogram', 'gm' => 'Gram', 'lb' => 'Lb'];
                break;
            case 'product_status':
                $options = ['All' => 'All', 'Active' => 'Active', 'Inactive' => 'Inactive', 'Draft' => 'Draft', 'Blocked' => 'Blocked'];
                break;
            case 'product_mode_of_selling':
                $options = ['All' => 'All', 'Buy it now' => 'Buy it now', 'Make an offer' => 'Make an offer', 'Auction' => 'Auction', 'Buy it now and Make an offer' => 'Buy it now and Make an offer', 'Buy it now and Auction' => 'Buy it now and Auction'];
                break;
            default :
                $options = FALSE;
        }
        return $options;
    }

}

function _getMonthName($n) {
    return date('F', mktime(0, 0, 0, $n, 1));
}

if (!function_exists('getMonthOptions')) {

    function getMonthOptions($start = 1, $end = 12) {
        $monthsArray = array_map('_getMonthName', range($start, $end));
        $months = [];
        foreach ($monthsArray AS $month) {
            $months[$month] = $month;
        }
        return $months;
    }
}

if (!function_exists('getYearOptions')) {

    function getYearOptions($start = '', $end = '') {
        $start = $start == '' ? date("Y") : $start;
        if ($end == '') {
            $dt = Carbon::now();
            $endYear = $dt->addYears(25);
            $end = $endYear->year;
        }
        $years = [];

        for ($i = $start; $i <= $end; $i++) {
            $years[$i] = $i;
        }
        return $years;
    }
}

function uploadImage($files, $is_thumbnail = false, $old_image = '', $crop = array(), $moduleName = '') {
    try {
        $moduleName = ($moduleName != "") ? $moduleName : getCurrentModuleName();
        $path = public_path() . '/images/' . $moduleName . '/';
        
        if (isset($old_image) && !empty($old_image)) {
            $main = $path . '/main/' . $old_image;
            $small = $path . '/small/' . $old_image;
            $thumbnail = $path . '/thumbnail/' . $old_image;
            $documents = $path . '/documents/' . $old_image;
            $video = $path . '/video/' . $old_image;
            if (file_exists($main)) {
                unlink($main);
            }
            if (file_exists($small)) {
                unlink($small);
            }
            if (file_exists($thumbnail)) {
                unlink($thumbnail);
            }
            if (file_exists($documents)) {
                unlink($documents);
            }
            if (file_exists($video)) {
                unlink($video);
            }
        }

        foreach ($files as $file) {
            //getting timestamp
            $name = str_replace([' ', ':'], '-', \Carbon\Carbon::now()->toDateTimeString() . '-' . $file->getClientOriginalName());
            $imagetype = explode('/', $file->getClientMimeType());
           
            //dd($name);

            if (in_array($imagetype[count($imagetype) - 1], array('jpeg', 'bmp', 'png','gif'))) {
                $type = 'images';
            } else if (in_array($imagetype[count($imagetype) - 1], array('pdf'))) {
                $type = 'documents';
            } else if (in_array($imagetype[count($imagetype) - 1], array('mp4'))) {
                $type = 'video';
            } else {
                return 'there is some error while uploading file';
            }
            //$name = $timestamp . '.' . $imagetype[count($imagetype) - 1];
            //$name = $timestamp . '.' . $imagetype[count($imagetype) - 1];
            //dd($type);
            if (!is_dir($path)) {
                mkdir($path);
                chmod($path, 0777);
            }

            

            if ($type == 'images') {
                $mainPath = $path . 'main/';
                if (!is_dir($mainPath)) {
                    mkdir($mainPath);
                    chmod($mainPath, 0777);
                }
                $file->move($path . '/main', $name);

                if ($is_thumbnail == true) {
                    $smallPath = $path . 'small/';
                    if (!is_dir($smallPath)) {
                        mkdir($smallPath);
                        chmod($smallPath, 0777);
                    }

                    $thumbnailPath = $path . 'thumbnail/';
                    if (!is_dir($thumbnailPath)) {
                        mkdir($thumbnailPath);
                        chmod($thumbnailPath, 0777);
                    }

                    //small image
                    $img = \Image::make($path . '/main/' . $name);



                    //IMAGE CROPPING FEATURE
                    //$img->resize(640, 480);
                    //if(isset($crop) && count($crop)>0){                             
//                        $crop['w']=round($crop['w']*640/304);                                
//                        $crop['h']=round($crop['h']*480/304);                                
                    //$crop['w']=round($crop['w']*$img->getWidth()/304);
                    //$crop['w']=round($crop['w']*$img->getWidth()/304);
//                        $crop['w']=round($crop['w']*$img->getWidth()/304);
//                        $crop['h']=round($crop['h']*$img->getHeight()/304);                                
//                        $crop['x1']=round($crop['x1']*$img->getWidth()/304);                                
//                        $crop['y1']=round($crop['y1']*$img->getHeight()/304);              
//                        $img->crop($crop['w'], $crop['h'],$crop['x1'],$crop['y1']);
                    //}
                    // resize image instance

                    $img->resize(600, 450);
                    // save image in desired format
                    $img->save($path . '/small/' . $name);

                    //small image
                    //thumbnail image
                    $img = \Image::make($path . '/main/' . $name);
                    // resize image instance
                    $img->resize(80, 60);
                    // save image in desired format
                    /* if (isset($crop) && count($crop) > 0) {
                      $img->crop($crop['w'], $crop['h'], $crop['x1'], $crop['y1']);
                      } */
                    $img->save($path . '/thumbnail/' . $name);
                    //thumbnail image
                }

                $names[] = array('path' => $name, 'file_type' => 'image');
            }
            if ($type == 'documents') {
                $thumbnailPath = $path . 'documents/';
                if (!is_dir($thumbnailPath)) {
                    mkdir($thumbnailPath);
                    chmod($thumbnailPath, 0777);
                }
                
                $file->move($path . '/documents', $name);
                $names[] = array('path' => $name, 'file_type' => 'documents');
            }

            if ($type == 'video') {
                $thumbnailPath = $path . 'documents/';
                if (!is_dir($thumbnailPath)) {
                    mkdir($thumbnailPath);
                    chmod($thumbnailPath, 0777);
                }

                if (!is_dir($path . '/video')) {
                    mkdir($path . '/video');
                    chmod($path . '/video', 0777);
                }

                chmod($path . '/video', 0777);
                
                $file->move($path . '/video', $name);
                
                $names[] = array('path' => $name, 'file_type' => 'video');
            }
        }
        if (count($names) > 1) {
            return $names;
        }

        return $names[0];
    } catch (\Exception $e) {
        //   echo $e->getMessage();
        //   die;
    }
}

function getImageByPath($imgname, $imageSubFolder = '') {
    $moduleName = getCurrentModuleName();
    return "<img  src='" . URL("/images/" . $moduleName . "/" . $imageSubFolder . "/" . $imgname) . "'/>";
}

function getDocumentPath($path) {
    $moduleName = getCurrentModuleName();
    return URL("/images/" . $moduleName . "/documents/" . $path);
}

function getImageFullPath($imgname, $moduleName, $imageSubFolder = '') {
    return URL("/images/" . $moduleName . "/" . $imageSubFolder . "/" . $imgname);
}

function generateDocumentAnchor($path) {
    return "<a target='_blank' href=" . getDocumentPath($path) . ">" . getDocumentPath($path) . "</a>";
}

function generateDocumentAnchorpreview($path) {
    return "<a target='_blank' href=" . getDocumentPath($path) . " class='fa fa-file-pdf-o'></a>";
}

function getCurrentModuleName() {
    GLOBAL $moduleName;
    if (!isset($moduleName)) {
        $routeSegments = explode('.', \Route::currentRouteName());
        if (count($routeSegments) > 1) {
            $moduleName = $routeSegments[1];
        } else {
            $moduleName = '';
        }
    }
    return $moduleName;
}

if (!function_exists('sendNotification')) {

    /*
     * Send notification
     * @param $to : id
     * @param $data : replaceable variables array
     * @param $table : users or admin_users
     */

    function sendNotification($template, $toIds = [], $tags = [], $table = 'users') {

        // get template
        $templateData = EmailTemplate::getTemplate(['template_key' => $template]);

        if (!empty($templateData) && !empty($toIds)) {

            $toIds = array_unique($toIds);

            foreach ($toIds AS $id) {
                if ($table == 'users') {
                    $user = User::select(['username', 'first_name', 'last_name', 'email'])->where(['id' => $id])->first(); // where condition
                    $toEmail = $user->email;
                } else if ($table == 'admin_users') {
                    $user = AdminUser::select(['first_name', 'last_name', 'personal_email', 'professional_email'])->where(['id' => $id])->first(); // where condition
                    $toEmail = $user->personal_email;
                }

                //echo "<pre>";print_r($templateData);echo "<pre>";print_r($user);die;

                $toUserFullname = $tags['TO_NAME'] = $user->first_name . ' ' . $user->last_name;
                $fromName = $tags['FROM_NAME'] = config('mail.from.name');
                $fromEmail = config('mail.from.address');
                $search = $replace = [];

                foreach ($tags AS $k => $v) {
                    array_push($search, "{#{$k}#}");
                    array_push($replace, $v);
                }

                $mailData['toName'] = $toUserFullname;
                $mailData['toEmail'] = $toEmail;
                $mailData['fromName'] = $fromName;
                $mailData['fromEmail'] = $fromEmail;
                $mailData['emailSubject'] = nl2br(str_replace($search, $replace, $templateData['email_subject']));
                $mailData['emailContent'] = nl2br(str_replace($search, $replace, $templateData['email_content']));
                //$template_notification = nl2br(str_replace($search, $replace, $templateData['notification_content']));
                //$template_sms = str_replace($search, $replace, $templateData['sms_content']);
                //attachment

                \Event::fire(new SendMail($mailData));
            }

            return TRUE;
        }
        return FALSE;
    }

}

function formatPrice($price) {
    return number_format((float) $price, 2);
}

if (!function_exists('checkImageExists')) {

    function checkImageExists($imgUrl = "") {
        try {
            getimagesize(env('APP_ADMIN_URL') . $imgUrl);
            return $imgUrl;
        } catch (Exception $e) {
            return env('APP_ADMIN_URL') . '/assets/admin/global/img/noimg.jpg';
        }
    }

}

if (!function_exists('zodiac')) {

    function zodiac($birthdate = "") {

        $zodiac = "";

        list ($year, $month, $day) = explode("-", $birthdate);

        if (( $month == 3 && $day > 20 ) || ( $month == 4 && $day < 20 )) {
            $zodiac = "Aries";
        } elseif (( $month == 4 && $day > 19 ) || ( $month == 5 && $day < 21 )) {
            $zodiac = "Taurus";
        } elseif (( $month == 5 && $day > 20 ) || ( $month == 6 && $day < 21 )) {
            $zodiac = "Gemini";
        } elseif (( $month == 6 && $day > 20 ) || ( $month == 7 && $day < 23 )) {
            $zodiac = "Cancer";
        } elseif (( $month == 7 && $day > 22 ) || ( $month == 8 && $day < 23 )) {
            $zodiac = "Leo";
        } elseif (( $month == 8 && $day > 22 ) || ( $month == 9 && $day < 23 )) {
            $zodiac = "Virgo";
        } elseif (( $month == 9 && $day > 22 ) || ( $month == 10 && $day < 23 )) {
            $zodiac = "Libra";
        } elseif (( $month == 10 && $day > 22 ) || ( $month == 11 && $day < 22 )) {
            $zodiac = "Scorpio";
        } elseif (( $month == 11 && $day > 21 ) || ( $month == 12 && $day < 22 )) {
            $zodiac = "Sagittarius";
        } elseif (( $month == 12 && $day > 21 ) || ( $month == 1 && $day < 20 )) {
            $zodiac = "Capricorn";
        } elseif (( $month == 1 && $day > 19 ) || ( $month == 2 && $day < 19 )) {
            $zodiac = "Aquarius";
        } elseif (( $month == 2 && $day > 18 ) || ( $month == 3 && $day < 21 )) {
            $zodiac = "Pisces";
        }

        return $zodiac;
    }

}

if (!function_exists('getUserProfileImage')) {
    function getUserProfileImage($user){                  
                if(isset($user) && !empty($user)){                    
                    if(empty($user->image)){
                        if ($user->gender == 'Male'){
                            return  URL("/assets/front/img/upload/user-male.png" );
                        }
                        elseif ($user->gender == 'Female') {
                            return URL("/assets/front/img/upload/user-female.png" );
                        }                    
                    }
                    else{                        
                        return URL("/assets/front/img/upload/".$user->id.'/'.$user->image );
                    }   
                }                       
                return '';
    }
}

if (!function_exists('sendOfferNotification')) {
    
    /*
     * Send offer
     * @param $to : id
     * @param $data : offer text
     */

    function sendOfferNotification($toEmail,$toName,$fromName,$fromEmail,$subject,$body) {

        $mailData = array();

        $mailData['toName']         = $toName;
        $mailData['toEmail']        = $toEmail;
        $mailData['fromName']       = $fromName;
        $mailData['fromEmail']      = $fromEmail;
        $mailData['emailSubject']   = $subject;
        $mailData['emailContent']   = $body;
        
        \Event::fire(new SendMail($mailData));
        return TRUE;
    }
}
if (!function_exists('latlog_distance')) {
    function latlog_distance($lat1, $lon1, $lat2, $lon2, $unit) {

      $theta = $lon1 - $lon2;
      $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
      $dist = acos($dist);
      $dist = rad2deg($dist);
      $miles = $dist * 60 * 1.1515;
      $unit = strtoupper($unit);

      if ($unit == "K") {
        return ($miles * 1.609344);
      } else if ($unit == "N") {
          return ($miles * 0.8684);
        } else {
            return $miles;
          }
    }
}

// function to change prices from USD to new currency due to the rate stored in the database 
function convert_currency($amount_in_usd , $change_rate_to_usd) {    
    $currencySymbols=array('INR'=>'₹' ,'HKD'=>'HK$', 'CAD'=>'CAD$','USD'=>'$');
    $currency = session()->get('currency');
    $new_rate = $amount_in_usd * $change_rate_to_usd; 
    if(isset($currency) && !empty($currency) && isset($change_rate_to_usd) && !empty($change_rate_to_usd)){        
        return $currencySymbols[$currency].$new_rate;    
    }
    else{                
        return '$'.$amount_in_usd;
    }
    
}
?>