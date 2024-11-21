@extends('layouts.app', ['class' => 'main-content-has-bg'])

@section('content')
@include('layouts.headers.guest')
<div class="container lw-guest-page-container-block pb-2" id="pageTop">
    <!-- Table -->
    <div class="row justify-content-center">
        <div class="col-lg-8">
            @if(getAppSettings('enable_vendor_registration'))
            <div class="card lw-form-card-box shadow border-0">
                <h1 class="card-header text-center">
                    <div class="my-4">
                        <i class="fa fa-store fa-3x text-gray"></i>
                    </div>
                    {{  __tr('Register as Subvendor') }}
                </h1>
                <div class="card-body">
                    {{-- <p>ID: {{ Route::current()->parameter('subscriptionId') }}</p> --}}
                    @php
                    $formSignUpRoute = route('auth.subvendor.register.process');
                    // if (getAppSettings('activation_required_for_new_user')) {
                    // $formSignUpRoute = route('activation_required.auth.register.process');
                    // }
                    @endphp
                    <x-lw.form :action="$formSignUpRoute" data-secured="true">
                        <!-- Vendor Name -->
                        <div class="form-group">
                            <div class="input-group input-group-alternative mb-1">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fa fa-user-alt"></i></span>
                                </div>
                                <input type="hidden" name="subscription_id" value={{ Route::current()->parameter('subscriptionId') }}>
                                <input class="form-control" placeholder="{{ __tr('Brand Name') }}" type="text"
                                    name="brand_title" value="{{ old('brand_title') }}" required autofocus>
                            </div>
                        </div>

                        <div class="form-group">
                                <x-lw.input-field type="selectize" data-lw-plugin="lwSelectize" data-form-group-class=""
                                required="true" name="brand_category" :label="__tr('Brand Category')">
                                    <x-slot name="selectOptions">
                                        <option value="">{{ __tr('Brand Category') }}</option>
                                        @foreach ($companycategories as $category)
                                            <option value="<?= $category->_id ?>">{{ $category->name }}</option>
                                        @endforeach
                                    </x-slot>
                                </x-lw.input-field>
                        </div>

                        
                        <!-- Username -->
                        <div class="form-group">
                            <div class="input-group input-group-alternative mb-1">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fa fa-id-card"></i></span>
                                </div>
                                <input class="form-control" placeholder="{{ __tr('Username') }}" type="text"
                                    name="username" value="{{ old('username') }}" required autofocus>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <!-- First Name -->
                                <div class="form-group">
                                    <div class="input-group input-group-alternative mb-1">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fa fa-user"></i></span>
                                        </div>
                                        <input class="form-control" placeholder="{{ __tr('Phone Number') }}" type="text"
                                        name="phone_number"  required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                 <!-- Last Name -->
                                <div class="form-group">
                                    <div class="input-group input-group-alternative mb-1">
                                        <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-user"></i></span>
                                    </div>
                                    <input class="form-control" placeholder="{{ __tr('Wa Number') }}" type="text"
                                    name="wa_number" required>
                                </div>
                            </div>
                            </div>
                        </div>
                        <!-- Email address -->
                        <div class="form-group">
                            <div class="input-group input-group-alternative mb-1">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fa fa-at"></i></span>
                                </div>
                                <input class="form-control" placeholder="{{ __tr('Email') }}" type="email" name="email"
                                    value="{{ old('email') }}" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="input-group input-group-alternative mb-1">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fa fa-user-alt"></i></span>
                                </div>
                                <input class="form-control" placeholder="{{ __tr('Address') }}" type="text"
                                    name="address"  required autofocus>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="input-group input-group-alternative mb-1">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fa fa-user-alt"></i></span>
                                </div>
                                <input class="form-control" placeholder="{{ __tr('City') }}" type="text"
                                    name="city"  required autofocus>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="input-group input-group-alternative mb-1">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fa fa-user-alt"></i></span>
                                        </div>
                                        <input class="form-control" placeholder="{{ __tr('District') }}" type="text"
                                            name="district" required autofocus>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="input-group input-group-alternative mb-1">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fa fa-user-alt"></i></span>
                                        </div>
                                        <input class="form-control" placeholder="{{ __tr('State') }}" type="text"
                                            name="state" required autofocus>
                                    </div>
                                </div>
                            </div>
                        </div>
                    
                        <!-- Password -->
                        <div class="form-group">
                            <div class="input-group input-group-alternative mb-1">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fa fa-key"></i></span>
                                </div>
                                <input class="form-control" placeholder="{{ __tr('Password') }}" type="password"
                                    name="password" required>
                            </div>
                        </div>
                        <!-- Confirm Password -->
                        <div class="form-group">
                            <div class="input-group input-group-alternative mb-1">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fa fa-key"></i> <sup><i
                                                class="fa fa-check"></i></sup></span>
                                </div>
                                <input class="form-control" placeholder="{{ __tr('Confirm Password') }}" type="password"
                                    name="password_confirmation" required>
                            </div>
                        </div>
                        <!-- privacy policy -->
                        @if (getAppSettings('user_terms') or getAppSettings('vendor_terms') or getAppSettings('privacy_policy'))
                        <div class="row my-4">
                            <div class="col-12">
                                <div class="custom-control custom-control-alternative custom-checkbox">
                                    <input class="custom-control-input" name="terms_and_conditions" id="itemsAccept"
                                        type="checkbox">
                                    <label class="custom-control-label" for="itemsAccept">
                                        <span class="text-white">{{ __tr('I agree with the') }}
                                            @if (getAppSettings('user_terms'))
                                            <a class="text-success" href="{{ route('app.terms_and_policies', [
                                                'contentName' => 'user_terms'
                                            ]) }}">{{ __tr('User Terms And Conditions') }}</a>,
                                            @endif
                                            @if (getAppSettings('vendor_terms'))
                                            <a class="text-success" href="{{ route('app.terms_and_policies', [
                                                'contentName' => 'vendor_terms'
                                            ]) }}">{{ __tr('Vendor Terms And Conditions') }}</a>,
                                            @endif
                                            @if (getAppSettings('privacy_policy'))
                                            <a class="text-success" href="{{ route('app.terms_and_policies', [
                                                'contentName' => 'privacy_policy'
                                            ]) }}">{{
                                                __tr('Privacy Policy')
                                                }}</a>
                                            @endif
                                        </span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        @endif
                        <!-- create account action -->
                        <div class="text-center">
                            <button  type="submit" class="btn btn-success btn-lg btn-block mt-6  mb-5">{{ __tr('Create Account') }}</button>
                        </div>
                    </x-lw.form>
                </div>
                <div class="card-footer text-center">
                    <!-- social login links -->
                    @if(getAppSettings('allow_google_login'))
                    <a href="<?= route('login.google') ?>" class="btn btn-google btn-user btn-block">
                        <i class="fab fa-google fa-fw"></i> <?= __tr('Continue with Google')  ?>
                    </a>
                    @endif
                    @if(getAppSettings('allow_facebook_login'))
                    <a href="<?= route('login.facebook') ?>" class="btn btn-facebook btn-user btn-block">
                        <i class="fab fa-facebook-f fa-fw"></i> <?= __tr('Continue with Facebook')  ?>
                    </a>
                    @endif
                    <!-- social login links -->
                    <div class="mb-3 mt-5">
                        {{ __tr('Already have an Account?') }}
                    </div>
                    <a href="{{ route('auth.login') }}" class="btn btn-lg btn-warning">
                        <small>{{ __tr('Click here to login') }}</small>
                    </a>
                </div>
            </div>
            @else
            <div class="card lw-form-card-box shadow border-0">
                <div class="card-header text-center">
                    @if (getAppSettings('message_for_disabled_registration'))
                    {!! getAppSettings('message_for_disabled_registration') !!}
                @else
                {{ __tr('Vendor Registrations are closed now.') }}
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection