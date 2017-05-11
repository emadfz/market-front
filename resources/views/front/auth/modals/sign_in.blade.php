<div class="modal" id="signinModal" tabindex="-1">
    <div class="modal-dialog modal-md" role="document" id="siginInAdjust">
        <div class="modal-content">
            {!! Form::open(['route' => 'postLogin', 'class' => 'login-ajax', 'id' => 'signinForm', 'autocomplete' => 'off']) !!}
            <div class="signin-popup flexbox clearfix">
                <a href="#" class="close" data-dismiss="modal">close</a>

                <div class="col-md-12 bg-color">
                    <div class="modal-title">Sign In <span>Quick and Easy Registration <a href="#" class="registrashow">click here</a></span></div>
                    <div class="form-horizontal">
                        <div class="form-group">
                            <label for="signinUsername" class="col-sm-4 control-label">{{ trans('form.auth.username') }}/{{trans('form.common.email')}}<span class="required">*</span></label>
                            <div class="col-sm-8">
                                {!! Form::text('username', old('username'), ['class'=>'form-control', 'placeholder'=>'', 'id' => 'signinUsername', 'maxlength'=>100, 'autofocus'=>'autofocus']) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="signinPassword" class="col-sm-4 control-label">{{ trans('form.auth.password') }}<span class="required">*</span></label>
                            <div class="col-sm-8">
                                <div class="outer-field">
                                    {!! Form::password('password', ['class'=>'form-control padd-right35', 'id' => 'signinPassword', 'maxlength'=>14, 'autocomplete'=>'off']) !!}
                                    <span class="password-view"></span>
                                </div>
                                <a href="javascript:void(0)" class="forgotlink forgotModal" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-dismiss="modal">Forgot Password ?</a>
                                <div class="login-checks">
                                    <div class="custom-checkbox">
                                        <label for="stay_sign_in">
                                            <input id="stay_sign_in" type="checkbox" name="stay_sign_in"><span></span>Stay Signed in*
                                        </label>
                                        <label for="remember_password">
                                            <input name="remember_password" id="remember_password" type="checkbox" /><span></span>Remember Password
                                        </label>
                                    </div>
                                </div>
                                <p class="infotext">* Checking in this box will make it easier to enter the site to buy and communicate.</p>	
                            </div>
                        </div>

                        <div class="form-group signin_captcha_failed_attempt" id="signin_captcha_failed_attempt" style="display: none;">
                            <label class="col-sm-4 control-label">Captcha<span class="required">*</span></label>
                            <div class="col-sm-8" id="signin_captcha_html"></div>
                        </div>
                        <input type="hidden" value="0" name="captcha_hdn" />

                        <div class="form-btnblock clearfix text-right">
                            <a href="#" title="Contact Administrator" class="text-link">Contact Administrator</a>
                            <input type="submit" title="SignIn" class="btn btn-primary" value="SignIn">

                        </div>
                        <div class="clearfix"></div>
                        <div class="or clearfix"><span>or</span></div>
                        <ul class="signin-social clearfix">
                            <li><a href="{{ route('socialAuthRedirectProvider', ['facebook']) }}" class="facebook" title="{{trans('form.auth.signin_facebook')}}"><span></span>{{trans('form.auth.signin_facebook')}}</a></li>
                            <li><a href="{{ route('socialAuthRedirectProvider', ['twitter']) }}" class="twitter" title="{{trans('form.auth.signin_twitter')}}"><span></span>{{trans('form.auth.signin_twitter')}}</a></li>
                            <li><a href="{{ route('socialAuthRedirectProvider', ['google']) }}" class="google" title="{{trans('form.auth.signin_google')}}"><span></span>{{trans('form.auth.signin_google')}}</a></li>
                            <li><a href="{{ route('socialAuthRedirectProvider', ['linkedin']) }}" class="linkedin" title="{{trans('form.auth.signin_linkedin')}}"><span></span>{{trans('form.auth.signin_linkedin')}}</a></li>
                        </ul>
                    </div>
                </div>

                 <div class="col-md-12 signinright" id="registrapopup">
                    <h3>New users?</h3>
                    <h4>Membership Benefits</h4>
                    <ul class="membership">
                        <li>Easy access to order history, saved items &amp; track your orders online</li> 
                        <li>Faster check out with stored shipping &amp; billing information</li> 
                        <li>Exclusive offers via e-mail such as discounts and shipping upgrades</li>
                    </ul>
                    <!--<h4>Quick and Easy Registration</h4>-->
                    <div class="form-btnblock clearfix text-right">
                        <a href="#" title="Cancel" class="cancel-link" id="showregistration-cancel">Cancel</a>
                        <a href="{{route("individualRegister")}}" class="btn btn-primary" title="Begin Registeration">Begin Registeration</a>
                    </div>
                    <a href="{{URL('/business-register')}}" class="btn btn-link ba-btn" title="Register for a business Account">Register for a business Account</a>
                    <!--<a href="{{route("individualRegister")}}" class="btn btn-link ba-btn" style="float: none;" title="Click Here">Click Here</a>-->
                </div>

            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>


<!-- Modal Signin Start-->
<!--<div class="modal" id="signin" tabindex="-1">
  <div class="modal-dialog modal-md" role="document" id="siginInAdjust">
    <div class="modal-content">
      <div class="signin-popup flexbox clearfix">
      <a href="#" class="close" data-dismiss="modal">close</a>
         <div class="col-md-12 bg-color">
        	<div class="modal-title">Sign In <span>Quick and Easy Registration <a href="#" class="registrashow">click here</a></span></div>
			<div class="form-horizontal">
            	<div class="form-group">
                	<label for="lnameemail" class="col-sm-4 control-label">User Name/Email<span class="required">*</span></label>
                    <div class="col-sm-8">
                      <input type="text" class="form-control" id="lnameemail" placeholder="User Name">
                    </div>
                </div>
                <div class="form-group">
                	<label for="lpassword" class="col-sm-4 control-label">Password<span class="required">*</span></label>
                    <div class="col-sm-8">
                      <div class="outer-field">
                          <input type="password" class="form-control padd-right35" id="lpassword">
                          <span class="password-view">view</span>
                      </div>
                      <a href="#forgotmodal" class="forgotlink" data-toggle="modal" data-dismiss="modal">Forgot Password ?</a>
					<div class="login-checks">
                    <div class="custom-checkbox">
          				<label><input type="checkbox" value=""><span></span>Stay Sign in*</label>
       					<label><input type="checkbox" value="" checked disabled><span></span>Remember Password</label>
                    </div>
                    </div>
                    <p class="infotext">* Checking in this box will make it easier to enter the site to buy, and communicate.</p>	
                    </div>
	            </div>
               
              <div class="form-btnblock clearfix text-right">
                <a href="#" title="Contact Administrator" class="text-link">Contact Administrator</a>
            	<input type="submit" title="SignIn" class="btn btn-primary" value="SignIn">
                
            </div>
             <div class="clearfix"></div>
              <div class="or clearfix"><span>or</span></div>
              <ul class="signin-social clearfix">
            	<li><a href="#" class="facebook" title="Signin with Facebook"><span></span>SignIn with Facebook</a></li>
                <li><a href="#" class="twitter" title="Signin with Twitter"><span></span>SignIn with twitter</a></li>
                <li><a href="#" class="google" title="Signin with Google"><span></span>SignIn with google</a></li>
                <li><a href="#" class="linkedin" title="Signin with Linkedin"><span></span>SignIn with linkedin</a></li>
            </ul>
            </div>        
        </div>
         <div class="col-md-12 signinright" id="registrapopup">
            <h3>New users?</h3>
            <h4>Membership Benefits</h4>
            <ul class="membership">
				<li>Easy access to order history, saved items &amp; track your orders inline</li> 
                <li>Faster check out with stored shipping &amp; billing information</li> 
                <li>Exclusive offers vie e-mail such as discounts and shipping upgrades</li>
            </ul>
            <div class="form-btnblock clearfix text-right">
            <a href="#" title="Cancel" class="cancel-link" id="showregistration-cancel">Cancel</a>
           	<input type="button" title="Begin Registeration" class="btn btn-primary" value="begin registeration" onclick="location.href='registration.html';" >
            </div>
            <a href="registration-step1.html" class="btn btn-link ba-btn" title="Register for a business Account">Register for a business Account</a>
        </div>
    </div>
     
    </div>
  </div>
</div>-->
<!--Modal Signin Close-->