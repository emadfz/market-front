<?php

namespace App\Http\Controllers\Front\Auth;

use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Http\Request;
use App\Http\Requests;
use DB;
use Mail;
use App\Models\SecretQuestion;
use App\Models\IndustryType;
use App\Models\User;
use App\Models\Country;
use App\Models\UserAddress;
use App\Models\SellerDetail;
use App\Models\UserPaymentCardDetail;
use App\Models\LoginHistoryUser;
use App\Models\UserPasswordReset;
use Carbon\Carbon;
use Laravel\Socialite\Facades\Socialite; //https://github.com/laravel/socialite
//use Illuminate\Cookie\CookieJar;
use Cookie;

//use Illuminate\Support\Facades\Input;

class AuthController extends Controller {

    public $secretQuestion;

    /*
      |--------------------------------------------------------------------------
      | Registration & Login Controller
      |--------------------------------------------------------------------------
      |
      | This controller handles the registration of new users, as well as the
      | authentication of existing users. By default, this controller uses
      | a simple trait to add these behaviors. Why don't you explore it?
      |
     */

use AuthenticatesAndRegistersUsers,
    ThrottlesLogins;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/';
    protected $lockoutTime = 1; //seconds
    protected $maxLoginAttempts = 5;

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware($this->guestMiddleware(), ['except' => 'logout']);
        $this->secretQuestion = new SecretQuestion();
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    /* protected function validator(array $data) {
      return Validator::make($data, [
      'name' => 'required|max:255',
      'email' => 'required|email|max:255|unique:users',
      'password' => 'required|min:6|confirmed',
      ]);
      } */

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    /* protected function create(array $data) {
      return User::create([
      'name' => $data['name'],
      'email' => $data['email'],
      'password' => bcrypt($data['password']),
      ]);
      } */

    /**
     * Login User
     * @param Request $request
     * @return type
     */
    public function postLogin(Request $request) {

        $loginValidateFields = collect([
            'username' => 'required',
            'password' => 'required',
        ]);

        if ($request->has('captcha_hdn') && $request->input('captcha_hdn') === 1) {
            //$loginValidateFields = $loginValidateFields->merge(['g-recaptcha-response' => 'required|captcha']);
        }

        // $loginField will be either email or username
        $loginField = filter_var($request->input('username'), FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $loginFailedLimitExceed = ($this->retriesLeft($request) <= 1) ? TRUE : FALSE;
        //echo "<pre>";        print_r($this->retriesLeft($request));        die;
        //echo "<pre>";print_r($loginValidateFields->all());dd($loginFailedLimitExceed);die;
        $validator = validator($request->all(), $loginValidateFields->all(), ['username.required' => trans('validation_custom.username_email_required')]);
        //dd($validator->errors()->all());

        if ($validator->fails()) {

            $errorMsg = $validator->errors();
            $errorMsg = $errorMsg->merge(['loginFailedLimitExceed' => $loginFailedLimitExceed]);
            //$errorMessages = array_merge()
            return ($request->ajax()) ?
                    response()->json(['status' => 'error', 'messages' => $errorMsg]) :
                    redirect()->route('homepage')->withErrors($validator)->withInput()->with('loginFailedLimitExceed', $loginFailedLimitExceed);
        }

        // If the class is using the ThrottlesLogins trait, we can automatically throttle, the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        $throttles = $this->isUsingThrottlesLoginsTrait();

        $credentials = [$loginField => $request->input('username'), 'password' => $request->input('password'), 'is_email_verified' => 1, 'status' => 'Active'/* 'is_activated' => 1 */];

        /* if (auth()->viaRemember()) {
          print_r(1);die;
          } */

        //login history data
        $loginHistoryData = [
            'email' => $request->input('username'),
            'attempts' => $this->maxLoginAttempts - ($this->retriesLeft($request) - 1),
            'ip_address' => $request->ip(),
            'user_agent' => $request->header('User-Agent') !== null ? $request->header('User-Agent') : '',
            'login_from' => 'Web'
        ];
        
        // Auth Attempt check with credentials
        if (auth()->attempt($credentials, $request->has('stay_sign_in'))) {

            // if remember password is checked
            $rememberPassword = '';
            if(!$request->has('stay_sign_in') && $request->has('remember_password')){
                $rememberPassword = 'rp'.md5($request->input('username').$request->ip());
                //unset($_COOKIE[$rememberPassword]);
                Cookie::forget($rememberPassword);
                setcookie($rememberPassword, encrypt($request->input('password')), time() + (86400 * 365));// 1year
            }
            
            LoginHistoryUser::saveAttemptRecord($loginHistoryData, 'success');

            if ($throttles) {
                $this->clearLoginAttempts($request);
            }

            \Flash::success(trans('message.auth.login_success'));            
            return ($request->ajax()) ?
            response()->json(['status' => 'success', 'redirectUrl' => $_SERVER['HTTP_REFERER']]) :
            redirect()->route('homepage');
//            return ($request->ajax()) ?
//                    response()->json(['status' => 'success', 'redirectUrl' => route('homepage')]) :
//                    redirect()->route('homepage');
        } else {

            // get user data by username/email
            $getUserData = User::checkUserData([$loginField => $request->input('username')]);

            try {
                if ($getUserData) {
                    if($getUserData['status'] == 'Blocked'){
                        throw new \Exception(trans('message.auth.account_blocked'));
                    }else if (\Hash::check($request->input('password'), $getUserData['password']) && ($getUserData['is_email_verified'] == 0 || $getUserData['status'] != 'Active')) {
                        throw new \Exception(trans('message.auth.account_not_activated'));
                    } else {
                        throw new \Exception(trans('message.auth.username_password_combination_wrong'));
                    }
                } else {
                    throw new \Exception(trans('message.auth.no_account_registered'));
                }
            } catch (\Exception $ex) {
                LoginHistoryUser::saveAttemptRecord($loginHistoryData, 'fail');
                // If the login attempt was unsuccessful we will increment the number of attempts, to login and redirect the user back to the login form. Of course, when this
                if ($throttles) {
                    $this->incrementLoginAttempts($request);
                }
                return response()->json(['status' => 'error', 'messages' => ['global_form_message' => $ex->getMessage()], 'loginFailedLimitExceed' => $loginFailedLimitExceed]);
            }

            //return $this->sendFailedLoginResponse($request);
            /* if ($request->ajax()) {
              return response()->json(['status' => 'error', 'messages' => ['global_form_message' => trans("message.login_invalid")]]);
              } else {
              \Flash::error(trans("message.login_invalid"));
              //return redirect()->route('adminLogin');
              return redirect()->back()->withInput($request->only($this->loginUsername()))->with('loginFailedLimitExceed', $loginFailedLimitExceed);
              } */
        }
    }

    /**
     * Display Individual registration form
     *
     * @return view
     */
    public function showIndividualRegistrationForm(Request $request) {
        // Note: if user is redirect here after authorize from social then in blade file socialite date can be accessed as session('socialiteData.email')
        //$sellerDetail = User::where(['id'=>104])->with('sellerDetail','addressDetail', 'paymentCardDetail')->get();echo "<pre>";print_r($sellerDetail->toArray());die;
        $nameTitle = getMasterEntityOptions('name_title');
        $gender = getMasterEntityOptions('gender');
        $secretQuestionList = $this->secretQuestion->getSecretQuestion('', true);

        return view("front.auth.register.individual_registration", compact('nameTitle', 'gender', 'secretQuestionList'));
    }

    /**
     * Create buyer as individual register
     * @param Request $request
     * @return Response
     */
    public function postIndividualRegister(Request $request) {
        //echo "<pre>";print_r($request->all());die;
        $this->validate($request, [
            'username' => 'required|string|min:5|max:25|unique:users,username', //'exists:username'
            'email' => 'required|string|email|max:100|unique:users,email',
            'title' => 'required',
            'first_name' => 'required|string|max:20',
            'last_name' => 'required|string|max:20',
            'gender' => 'required',
            'date_of_birth' => 'required|date|before:-18 years',
            'phone_number' => 'required|numeric', //|regex:/(01)[0-9]{9}/ //
            'secret_question' => 'required',
            'secret_answer' => 'required|max:100',
            'password' => 'required|alpha_num|Between:7,14', //min:7|max:14',alpha_num
            'confirm_password' => 'required|same:password',
            //'is_subscribed' => '',
            'agree_and_accept_terms_condition_and_privacy_policy' => 'accepted',
        ] , ['date_of_birth.before' => trans("validation_custom.age_should_be_more_than_18_years")]);

        //echo "<pre>";print_r($request->all());die;
        // Start transaction!
        DB::beginTransaction();
        try {
            // generate activation token string for email verification
            $activationCode = generateToken($request->input('email'));
            $request->merge(['activation_code' => $activationCode, 'user_type' => 'Buyer']);
            $mailData['email'] = $request->input('email');
            $mailData['full_name'] = $request->input('first_name') . ' ' . $request->input('last_name');
            $requestInput = $request->all();

            //if redirect from socialite
            if ($requestInput['provider'] != "" && $requestInput['social_id'] != "") {
                $requestInput[$requestInput['provider'] . '_id'] = $requestInput['social_id'];
            }
            unset($requestInput['provider'], $requestInput['social_id']);

            $requestInput['phone_number'] = $requestInput['country_code'] != "" ? $requestInput['country_code'] . " " . $requestInput['phone_number'] : $requestInput['phone_number'];
            // Create user
            $userId = User::createUser($requestInput);

            if ($userId->id != '') {
                // send notification
                $tags = ['ACTIVATION_LINK' => route('accountVerify', $activationCode)];
                sendNotification('INDIVIDUAL_BUYER_REGISTER', [$userId->id], $tags, $table = 'users');
                /*Mail::send('front.auth.emails.verification', ['activationCode' => $activationCode, 'fullName' => $mailData['full_name']], function($message) use ($mailData) {
                    $message->to($mailData['email'], $mailData['full_name'])
                            ->subject('Account Verification - inSpree Marketplace');
                });*/
            }
        } catch (\Exception $e) {
            // Rollback transaction
            DB::rollBack();
            return response()->json(['status' => 'error', 'messages' => ['global_form_message' => $e->getMessage()]]);
            //return response()->json(['status' => 'error', 'messages' => ['global_form_message' => trans('message.failure')]]);
        }

        // If we reach here, then data is valid and working. Commit the queries!
        DB::commit();
        \Flash::success(trans('message.auth.individual_registration_success'));
        return ($request->ajax()) ? response()->json(['status' => 'success', 'redirectUrl' => route('homepage')]) : redirect()->route('homepage');
    }

    /**
     * Display Business registration form
     *
     * @return view
     */
    public function showBusinessRegistrationForm() {
        $nameTitle = getMasterEntityOptions('name_title');
        $gender = getMasterEntityOptions('gender');
        $position = getMasterEntityOptions('position');
        $cardTypes = getMasterEntityOptions('payment_card_type');
        $expiryMonths = getMonthOptions();
        $expiryYears = getYearOptions();

        $secretQuestionList = $this->secretQuestion->getSecretQuestion('', true);
        $industryTypes = IndustryType::getIndustryTypes('', true);
        $countries = getAllCountries()->pluck('country_name', 'id')->toArray();
        return view("front.auth.register.business_registration", compact('industryTypes', 'nameTitle', 'gender', 'secretQuestionList', 'countries', 'cardTypes', 'expiryMonths', 'expiryYears', 'position'));
    }

    /**
     * Create business seller
     * @param Request $request
     * @return Response
     */
    public function postBusinessRegister(Request $request) {
        //echo "<pre>";print_r($request->all());die;
        $currentStep = $request->input('current_step');
        switch ($currentStep) {
            case 'step1':
                //Company Information
                $this->validate($request, [
                    'username' => 'required|string|min:5|max:25|unique:users,username', //'exists:username'
                    'password' => 'required|Between:7,14', //min:7|max:14',
                    'confirm_password' => 'required|same:password',
                    'business_name' => 'required|max:50',
                    'industry_type' => 'required',
                    'tax_id_number' => 'required|max:50',
                    'business_details' => 'required|max:500',
                    'phone_number' => 'numeric', //|regex:/(01)[0-9]{9}/ //
                    'website' => 'url|max:100',
                ]);
                return response()->json(['status' => 'success', 'stepMove' => 'step2']);
                break;

            case 'step2':
                //Address Information
                $addressValidation = collect([
                    'billing_address_1' => 'required|max:100',
                    'billing_address_2' => 'required|max:100',
                    'billing_country' => 'required',
                    'billing_postal_code' => 'required|max:10',
                    'billing_state' => 'required',
                    'billing_city' => 'required'
                ]);

                if (!$request->has('same_address_info') || $request->input('same_address_info') == 'no') {
                    $addressValidation = $addressValidation->merge(collect([
                        'shipping_address_1' => 'required|max:100',
                        'shipping_address_2' => 'required|max:100',
                        'shipping_country' => 'required',
                        'shipping_postal_code' => 'required|max:10',
                        'shipping_state' => 'required',
                        'shipping_city' => 'required'
                    ]));
                }

                $this->validate($request, $addressValidation->toArray());
                return response()->json(['status' => 'success', 'stepMove' => 'step3']);
                break;

            case 'step3':
                //Contact Information
                $this->validate($request, [
                    'email' => 'required|string|email|max:100|unique:users,email',
                    'title' => 'required',
                    'first_name' => 'required|string|max:20',
                    'last_name' => 'required|string|max:20',
                    'gender' => 'required',
                    'date_of_birth' => 'required|date|before:-18 years',
                    'phone_number' => 'required|numeric', //|regex:/(01)[0-9]{9}/ //
                    'position' => 'required',
                    'secret_question' => 'required',
                    'secret_answer' => 'required|max:100',
                ],['date_of_birth.before' => trans("validation_custom.age_should_be_more_than_18_years")]);

                return response()->json(['status' => 'success', 'stepMove' => 'step4']);
                break;

            case 'step4':
                //Credit/Debit card and Bank information
                $this->validate($request, [
                    'full_name' => 'required|max:100',
                    'card_type' => 'required',
                    'card_number' => 'required|max:16',
                    'expiry_month' => 'required',
                    'expiry_year' => 'required',
                    'bank_name' => 'max:100',
                    'bank_phone_number' => 'numeric',
                    'bank_routing_number' => 'max:25',
                    'bank_account_number' => 'max:25',
                    //'is_subscribed' => '',
                    'agree_and_accept_terms_condition_and_privacy_policy' => 'accepted',
                ]);

                //echo "<pre>";print_r($request->all());die;
                $registered = $this->businessRegister($request);
                $debugRegister = FALSE;

                if (!$registered) {
                    return response()->json(['status' => 'error', 'messages' => ['global_form_message' => trans('message.failure')]]);
                }

                // to debug - when not working (to see error message generated through exception)
                if ($debugRegister && $registered) {
                    return response()->json(['status' => 'error', 'messages' => ['global_form_message' => $registered->getMessage()]]);
                }
                break;

                default:
                break;
        }

        \Flash::success(trans('message.auth.business_registration_success'));

        return ($request->ajax()) ? response()->json(['status' => 'success', 'redirectUrl' => route('homepage')]) : redirect()->route('homepage');
    }

    /**
     * Internal method to register business seller
     *
     * @param $request
     * @return Boolean TRUE/FALSE
     */
    private function businessRegister($request) {

        // Start transaction!
        DB::beginTransaction();
        try {

            //Create User
            $request->merge(['user_type' => 'Business Seller']);

            $requestUser = $request->only([
                'username', 'email', 'title', 'first_name', 'last_name', 'gender', 'date_of_birth',
                'phone_number', 'secret_question', 'secret_answer', 'password', 'user_type'
            ]);

            /*
              // generate activation token string for email verification
              $activationCode = generateToken($request->input('email'));
              $request->merge(['activation_code' => $activationCode]);
             */
            $mailData['email'] = $request->input('email');
            $mailData['full_name'] = $request->input('first_name') . ' ' . $request->input('last_name');


            $userInfo = User::createUser($requestUser);
            $userId = $userInfo->id;

            //Create Billing Address
            $requestAddress = [
                'address_1' => $request->input('billing_address_1'),
                'address_2' => $request->input('billing_address_2'),
                'country_id' => $request->input('billing_country'),
                'postal_code' => $request->input('billing_postal_code'),
                'state_id' => $request->input('billing_state'),
                'city_id' => $request->input('billing_city'),
                'user_id' => $userId,
                'address_type' => 'Billing'
            ];

            UserAddress::createAddress($requestAddress);

            if ($request->has('same_address_info') && $request->input('same_address_info') == 'yes') {
                //Create Shipping Address - if radio button yes is checked
                $requestAddress['address_type'] = 'Shipping';

                UserAddress::createAddress($requestAddress);
            } else {
                //Create Shipping Address
                $requestAddress = [
                    'address_1' => $request->input('shipping_address_1'),
                    'address_2' => $request->input('shipping_address_2'),
                    'country_id' => $request->input('shipping_country'),
                    'postal_code' => $request->input('shipping_postal_code'),
                    'state_id' => $request->input('shipping_state'),
                    'city_id' => $request->input('shipping_city'),
                    'user_id' => $userId,
                    'address_type' => 'Shipping'
                ];
                UserAddress::createAddress($requestAddress);
            }

            //Create Seller Detail

            $request->merge(['user_id' => $userId]);
            $requestSellerDetail = $request->only([
                'user_id', 'business_name', 'industry_type', 'business_details',
                'tax_id_number', 'business_reg_number', 'business_phone_number',
                'website', 'position', 'position_other',
                'bank_name', 'bank_phone_number', 'bank_routing_number', 'bank_account_number'
            ]);

            SellerDetail::createSellerDetail($requestSellerDetail);

            //Create Payment Card Detail
            $requestPaymentCardDetail = $request->only([
                'user_id', 'full_name', 'card_type', 'card_number', 'expiry_month', 'expiry_year'
            ]); //token

            UserPaymentCardDetail::createPaymentCard($requestPaymentCardDetail);

            /* echo "<pre>";
              print_r($userId->id);
              die; */
            if ($userId != '') {
                /*Mail::send('front.auth.emails.business_register_notification', ['fullName' => $mailData['full_name']], function($message) use ($mailData) {
                    $message->to($mailData['email'], $mailData['full_name'])
                            ->subject('Business Registration - inSpree Marketplace');
                });*/
                // send notification
                sendNotification('BUSINESS_SELLER_REGISTER', [$userId], $tags=[], $table = 'users');
            }
        } catch (\Exception $ex) {
            // Rollback transaction
            DB::rollBack();
            //return $ex;// when not working uncomment this line and comment below line
            return FALSE;
        }

        // If we reach here, then data is valid and working. Commit the queries!
        DB::commit();
        return TRUE;
    }

    /**
     * Account verification
     *
     * @param $activationCode
     * @return Response
     */
    public function accountVerify($activationCode = '') {
        try {
            $user = User::checkActivationCode($activationCode);

            if ($user) {

                if ($user->is_email_verified == 1 && $user->status == 'Active') {
                    \Flash::info(trans('message.auth.account_already_activated'));
                    return redirect()->route('homepage');
                }

                // activate user
                User::activateUser($user->id);

                \Flash::success(trans('message.auth.account_activate_success'));
                return redirect()->route('homepage');
            } else {
                \Flash::error(trans('message.auth.invalid_verification_code_or_url'));
                return redirect()->route('homepage');
            }
        } catch (\Exception $ex) {
            \Flash::error(trans('message.failure'));
            return redirect()->route('homepage');
        }
    }

    /**
     * Redirect the user to the social provider authentication page.
     *
     * @return Response
     */
    public function redirectToProvider($provider) {
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Obtain the user information from social provider.
     *
     * @param $provider
     * @return Response
     */
    public function handleProviderCallback($provider) {
        try {
            $socialLiteUser = Socialite::driver($provider)->user();
            //echo "<pre>";print_r($socialLiteUser);die;
            // All Providers
            /* $socialLiteUser->getId();
              $socialLiteUser->getNickname();
              $socialLiteUser->getName();
              $socialLiteUser->getEmail();
              $socialLiteUser->getAvatar(); */
        } catch (\Exception $e) {
            //\Flash::error(trans('message.failure'));
            return redirect()->route('homepage');
        }

        try {
            //echo "<pre>";print_r($socialLiteUser);die;
            if ($socialLiteUser->email != "") {
                // if we get email from socialite then check it in our database 
                $getUserData = User::checkUserData(['email' => $socialLiteUser->email]);

                if ($getUserData) {
                    // Update social id in users table
                    User::updateUser(['email' => $socialLiteUser->email], [$provider . '_id' => $socialLiteUser->id]);

                    if ($getUserData['is_email_verified'] == 0) {
                        throw new \Exception(trans('message.auth.already_registered_but_account_not_activated'));
                    }

                    // if account is activated then allow login
                    auth()->loginUsingId($getUserData['id']);

                    //login history data
                    $loginHistoryData = [
                        'email' => $socialLiteUser->email,
                        'attempts' => 1,
                        'ip_address' => $_SERVER['REMOTE_ADDR'],
                        'user_agent' => $_SERVER['HTTP_USER_AGENT'],
                        'login_from' => ucfirst($provider)
                    ];

                    LoginHistoryUser::saveAttemptRecord($loginHistoryData, 'success');
                    \Flash::success(trans('message.auth.login_success'));
                    //$authUser = $this->findOrCreateUser($socialLiteUser, $provider);auth()->login($authUser, true);
                    return redirect()->route('homepage');
                }
            }

            // redirect user to individual registration form and $input data will be in session
            $fullName = explode(' ', $socialLiteUser->name);
            $input = [ 'socialiteData' => [
                    'first_name' => $fullName[0],
                    'last_name' => @$fullName[1],
                    'email' => $socialLiteUser->email,
                    'provider' => $provider,
                    'social_id' => $socialLiteUser->id,
                    'gender' => @$socialLiteUser->user['gender'],
                    'date_of_birth' => '',
                    'country_code' => '',
                    'phone_number' => '',
            ]];
            \Flash::info(trans("message.auth.info_redirect_to_reg_after_social_login"));
            return redirect()->route('individualRegister')->with($input); //->with('loginFailedLimitExceed', $loginFailedLimitExceed);
        } catch (\Exception $ex) {
            \Flash::error($ex->getMessage());
            return redirect()->route('homepage');
            //return response()->json(['status' => 'error', 'messages' => ['global_form_message' => $ex->getMessage()]]);
        }
    }

    /**
     * Forgot password form submit - email reset link to user
     *
     * @param $request
     * @return Response
     */
    public function postForgotPassword(Request $request) {

        $email = $request->input('email');
        $validator = validator($request->only('email'), ['email' => 'required|email|exists:users,email,status,Active']);
        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'messages' => $validator->errors()]);
        }
        
        $validator = validator($request->all(), [
            //'email' => 'required|email|exists:users,email,status,Active',
            'date_of_birth' => 'required|date',
            'secret_question' => 'required|exists:users,secret_question_id,email,' . $email,
            'secret_answer' => 'required|exists:users,secret_answer,email,' . $email,
                //'g-recaptcha-response' => 'required|captcha',
                ], ['email.exists' => trans('message.auth.no_account_registered_with_email')]
        );

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'messages' => $validator->errors()]);
        }

        // get user info
        $userInfo = User::checkUserData(['email' => $email]);

        // check date of birth
        if (convertToDateFormat($request->input('date_of_birth')) != $userInfo['date_of_birth']) {
            return response()->json(['status' => 'error', 'messages' => ['date_of_birth' => trans('message.auth.dob_not_match')]]);
        }

        // do one entry in user_password_resets table
        $token = generateToken($email);
        $data['email'] = $email;
        $data['user_id'] = $userInfo['id'];
        $data['token'] = $token;
        UserPasswordReset::create($data);

        $data['full_name'] = $userInfo['first_name'] . ' ' . $userInfo['last_name'];

        // send an email to user with reset password link
        /*Mail::send('front.auth.emails.password', $data, function ($message) use ($userInfo, $email) {
            $message->to($email, $userInfo['first_name'] . ' ' . $userInfo['last_name']);
            $message->subject(trans("form.auth.password_reset"));
        });*/
        
        // send notification
        $tags = ['PASSWORD_RESET_LINK' => route('resetPassword', $token)];
        sendNotification('FORGOT_PASSWORD_FRONT', [$userInfo['id']], $tags, $table = 'users');

        //return response()->json(['status' => 'success', 'messages' => ['global_form_message' => trans("message.auth.password_reset_link_sent")]]);
        \Flash::success(trans("message.auth.password_reset_link_sent"));
        return response()->json(['status' => 'success', 'redirectUrl' => route('homepage')]);
    }

    public function showForgotPasswordForm() {

        /*   if (!auth()->guard('admin')->check()) {
          return view('admin.auth.passwords.forgot');
          } else {
          return redirect()->route(config('project.admin_route') . 'home.index');
          }

         */
    }

    /**
     * Reset password form display
     *
     * @param $token
     * @return View
     */
    public function showResetPasswordForm($token) {

        if (!isLoggedin()) {
            try {
                $userInfo = UserPasswordReset::checkTokenExists($token)->toArray();

                if (empty($userInfo)) {
                    \Flash::error(trans('message.auth.token_does_not_exist'));
                    return redirect()->route('homepage');
                } else {
                    $difference = ' +24 hours';
                    if ((strtotime($userInfo['created_at'] . $difference) < strtotime(Carbon::now()->format("Y-m-d H:i:s"))) || $userInfo['is_used'] == 1) {
                        \Flash::error(trans('message.auth.token_expired'));
                        return redirect()->route('homepage');
                    }
                }
            } catch (\Exception $ex) {
                \Flash::error(trans('message.failure'));
                return redirect()->back();
            }

            return view('front.auth.passwords.reset', compact('token', 'userInfo'));
        } else {
            return redirect()->route('homepage');
        }
    }

    /**
     * Reset password form submit
     *
     * @param $request
     * @return Response
     */
    public function postResetPassword(Request $request) {
        $this->validate($request, [
            'password' => 'required|Between:7,14',
            'confirm_password' => 'required|same:password',
        ]);
        $resetToken = decrypt($request->input("reset_token"));

        try {
            $resetToken = decrypt($request->input("reset_token"));

            $userInfo = UserPasswordReset::checkTokenExists($resetToken)->toArray();

            if (empty($userInfo)) {
                \Flash::error(trans('message.auth.token_does_not_exist'));
                return response()->json(['status' => 'success', 'redirectUrl' => route('homepage')]);
            }

            // update password in users table
            User::updateUser(['id' => $userInfo['user_id']], ['password' => bcrypt($request->input('password'))]);
            // update password reset entry
            UserPasswordReset::updateUserPasswordReset(['is_used' => 1], $resetToken);

            \Flash::success(trans('message.auth.password_reset_success'));
            return response()->json(['status' => 'success', 'redirectUrl' => route('homepage')]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'messages' => ['global_form_message' => trans('message.failure')]]);
            //return response()->json(['status' => 'error', 'messages' => ['global_form_message' => $e->getMessage()]]);
        }
    }

    /**
     * Refresh Captcha
     *
     * @param  No Param
     * @return Captcha image
     */
    public function refereshCaptcha() {
        return app('captcha')->display();
        //return '<script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer></script>'.'<div class="g-recaptcha" data-sitekey="'.env('NOCAPTCHA_SITEKEY').'"></div>';
    }

    public function signinFromAdmin($userId) {
        try {
            $userId = decrypt($userId);
            auth()->loginUsingId($userId);
            \Flash::success(trans('message.auth.login_success'));
            return redirect()->route('homepage');
        } catch (\Exception $ex) {
            \Flash::error(trans('message.failure'));
            return redirect()->route('homepage');
        }
    }
    
    public function getUserPassword(Request $request){
        $rememberPassword = 'rp'.md5($request->input('username').$request->ip());
        $password = Cookie::get($rememberPassword);
        if($password != "")
            return response()->json(['success' => 1, 'p' => base64_encode($password)]);
        else
            return response()->json(['success' => 0]);
    }

}
