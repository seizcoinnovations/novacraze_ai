@extends('layouts.app', ['title' => __tr('Subvendors')])

@section('content')
@include('users.partials.header', [
'title' => __tr('Instant Offers') . ' '. auth()->user()->name,
'description' => '',
'class' => 'col-lg-7'
])

<div class="container-fluid">
    <div class="row">
        <div class="col-xl-12 mb-3 mt-md--5">
            <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#addInstantOfferModal">
                <?= __tr('Add New Instant Offer') ?>
            </button>
        </div>
        <div class="col-xl-12">
            {{-- DATATABLE --}}
            <x-lw.datatable id="lwManageVendorsTable" :url="route('subvendors.instant_offers.read.list')" data-page-length="100">
                
                <th data-orderable="true" data-name="title">
                    <?= __tr('Title') ?>
                </th>
                <th data-orderable="true" data-name="created_at">
                    <?= __tr('Created at') ?>
                </th>
                
                <th data-orderable="true" data-name="status">
                    <?= __tr('Status') ?>
                </th>
               
                <th data-template="#actionButtons" name="null">
                    <?= __tr('Action') ?>
                </th>
            </x-lw.datatable>
            {{-- DATATABLE --}}
        </div>
    </div>
    <script type="text/template" id="titleExtendedButtons">
        <a  href ="<%= __Utils.apiURL("{{ route('vendor.dashboard',['vendorIdOrUid'=>'vendorIdOrUid'])}}", {'vendorIdOrUid':__tData._uid}) %>"> <%-__tData.title %> </a> 
    </script>
    <script type="text/template" id="lwQuickActionButtons">
        <a data-method="post" href="<%= __Utils.apiURL("{{ route('central.vendors.user.write.login_as', [ 'vendorUid']) }}", {'vendorUid': __tData._uid}) %>" class="btn btn-light btn-sm lw-ajax-link-action" data-confirm="#lwLoginAs-template" title="{{ __tr('Login as Vendor Admin') }}"><i class="fa fa-sign-in-alt"></i> {{  __tr('Login') }}</a>
        <a class="btn btn-primary btn-sm" href ="<%= __Utils.apiURL("{{ route('central.vendor.details',['vendorIdOrUid'=>'vendorIdOrUid'])}}", {'vendorIdOrUid':__tData._uid}) %>"> {{  __tr('Subscription') }} </a>
    </script>
    <script type="text/template" id="actionButtons">
        <!-- EDIT ACTION -->
        <a data-pre-callback="appFuncs.clearContainer" title="{{ __tr('Edit') }}" class="lw-btn btn btn-sm btn-default lw-ajax-link-action" data-response-template="#lwEditVendorBody" href="<%= __Utils.apiURL("{{ route('vendor.read.update.data', ['vendorIdOrUid']) }}", {'vendorIdOrUid': __tData._uid}) %>" data-toggle="modal" data-target="#lwEditVendor"><i class="fa fa-edit"></i> {{ __tr('Edit') }}</a>
        <!--  DELETE ACTION -->
        <a data-method="post" href="<%= __Utils.apiURL("{{ route('subvendor.instant_offer.delete', ['instantofferIdOrUid']) }}", {'instantofferIdOrUid': __tData._id}) %>" class="btn btn-danger btn-sm lw-ajax-link-action-via-confirm" data-confirm="#lwDeletePlan-template" title="{{ __tr('Delete') }}" data-toggle="modal" data-target="#deletePlan" data-callback-params="{{ json_encode(['modalId' => '#lwDeletePlan-template','datatableId' => '#lwManageVendorsTable']) }}" data-callback="appFuncs.modelSuccessCallback"><i class="fa fa-trash"></i> {{ __tr('Delete') }}</a>
        
    </script>
    <script type="text/template" id="lwLoginAs-template">
        <h2>{{ __tr('Are You Sure!') }}</h2>
        <p>{{ __tr('You want login to this vendor admin account?') }}</p>
</script>
    <!-- VENDOR DELETE TEMPLATE -->
    <script type="text/template" id="lwDeletePlan-template">
        <h2><?= __tr('Are You Sure!') ?></h2>
            <p><?= __tr('Are you sure you want to delete this Vendor?') ?></p>
    </script>
    <!-- /VENDOR DELETE TEMPLATE -->
    {{-- ADD VENDOR MODAL --}}
    <x-lw.modal id="addInstantOfferModal" :header="__tr('Add New Instant Offer')" :hasForm="true">
        {{-- FORM START --}}
        <x-lw.form :action="route('subvendors.instant_offers.write.add')" data-callback="afterSuccessfullyCreated" enctype="multipart/form-data">
            <div class="lw-form-modal-body">
                {{-- INSTANT Offer TITLE --}}
                <div class="form-group mb-3">
                    <div class="input-group input-group-alternative">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-user-alt"></i></span>
                        </div>
                        <input class="form-control" placeholder="{{ __tr('Title') }}" type="text"
                            name="instant_offer_title" value="{{ old('instant_offer_title') }}" required>
                    </div>
                </div>
                {{-- VENDOR TITLE --}}
               
                {{-- USERNAME --}}
                <div class="form-group mb-3">
                    <div class="input-group input-group-alternative">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-id-card"></i></span>
                        </div>
                        <input class="form-control" placeholder="{{ __tr('From') }}" type="date" name="from_date"
                            value="{{ old('from_date') }}" required autofocus>
                    </div>
                </div>
                {{-- USERNAME --}}
                {{-- FIRSTNAME --}}
                <div class="form-group mb-3">
                    <div class="input-group input-group-alternative">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-user"></i></span>
                        </div>
                        <input class="form-control" placeholder="{{ __tr('To') }}" type="date" name="to_date"
                            value="{{ old('to_date') }}" required>
                    </div>
                </div>
                {{-- FIRSTNAME --}}
                {{-- LASTNAME --}}
                <div class="form-group mb-3">
                    <div class="input-group input-group-alternative">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-user"></i></span>
                        </div>
                        <input class="form-control" placeholder="{{ __tr('Image') }}" type="file" name="image"
                            value="{{ old('image') }}" required>
                    </div>
                </div>
                {{-- /LASTNAME --}}
                {{-- EMAIL --}}
                <div class="form-group mb-3">
                    <div class="input-group input-group-alternative">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-at"></i></span>
                        </div>
                        <input class="form-control" placeholder="{{ __tr('Description') }}" type="text" name="description">
                    </div>
                </div>
                {{-- /EMAIL --}}
                
            </div>
            {{-- Form footer --}}
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">{{ __tr('Add') }}</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <?= __tr('Close') ?>
                </button>
            </div>
        </x-lw.form>
    </x-lw.modal>
    <!-- EDIT VENDOR MODAL -->
    <x-lw.modal id="lwEditVendor" :header="__tr('Edit Vendor')" :hasForm="true">
        <!-- EDIT VENDOR FORM  -->
        <x-lw.form id="lwEditPlanForm" :action="route('vendor.write.update')"
            :data-callback-params="['modalId' => '#lwEditVendor', 'datatableId' => '#lwManageVendorsTable']"
            data-callback="appFuncs.modelSuccessCallback">
            <!-- form body -->
            <div data-default-text="{{ __tr('Please wait while we fetch data') }}" id="lwEditVendorBody"
                class="lw-form-modal-body"></div>
            <script type="text/template" id="lwEditVendorBody-template">
                <input type="hidden" name="vendorIdOrUid" value="<%- __tData._uid %>" />
                    <input type="hidden" name="userIdOrUid" value="<%- __tData.userUId %>" />
                <!-- FORM FIELDS -->
                <!-- TITLE -->
                <div class="form-group">
                    <label for="lwTitleField"><?= __tr('Title') ?></label>
                    <div class="input-group input-group-alternative">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-user-alt"></i></span>
                        </div>
                        <input type="text" class="lw-form-field form-control" placeholder="<?= __tr('Title') ?>" id="lwTitleField" value="<%- __tData.title %>" name="title"/>
                    </div>
                </div>
                <!-- /Title -->
                {{-- VENDOR TITLE --}}
                <div class="text-center text-muted mt-4 mb-0 ">
                    {{ __tr('Admin User') }}
                </div>
                <!-- UserName  -->
                <div class="form-group">
                    <label for="lwUserNameEditField"><?= __tr('Username') ?></label>
                    <div class="input-group input-group-alternative">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-id-card"></i></span>
                        </div>
                        <input type="text" class="lw-form-field form-control" placeholder="<?= __tr('Username') ?>" id="lwUserNameEditField" value="<%- __tData.username%>" name="username" required="true" />
                    </div>
                </div>
                <!-- /UserName  -->
                <!-- FIRST NAME -->
                <div class="form-group">
                    <label for="lwDescriptionField"><?= __tr('First Name') ?></label>
                    <div class="input-group input-group-alternative">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-user-alt"></i></span>
                        </div>
                        <input type="text" class="lw-form-field form-control" placeholder="<?= __tr('First Name') ?>" id="lwFirstNameField" value="<%- __tData.first_name %>" name="first_name"/>
                    </div>
                </div>
                <!-- /FIRST NAME -->
                <!-- LAST NAME -->
                <div class="form-group">
                    <label for="lwDescriptionField"><?= __tr('Last Name') ?></label>
                    <div class="input-group input-group-alternative">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-user-alt"></i></span>
                        </div>
                        <input type="text" class="lw-form-field form-control" placeholder="<?= __tr('Last Name') ?>" id="lwLastNameField" value="<%- __tData.last_name %>" name="last_name"/>
                    </div>
                </div>
                <!-- /LAST NAME -->
                <!-- EMAIL  -->
                <div class="form-group">
                    <label for="lwEmailEditField"><?= __tr('Email') ?></label>
                    <div class="input-group input-group-alternative">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-at"></i></span>
                        </div>
                        <input type="text" class="lw-form-field form-control" placeholder="<?= __tr('Email ') ?>" id="lwEmailEditField" value="<%- __tData.email%>" name="email" required="true" />
                    </div>
                </div>
                <!-- /EMAIL  -->
                <!-- STATUS -->
                <div class="form-group pt-3">
                    <label for="lwIsVendorActiveEditField">{{  __tr('Vendor Status') }}</label>
                    <input type="checkbox" id="lwIsVendorActiveEditField" <%- __tData.store_status == 1 ? 'checked' : '' %> data-lw-plugin="lwSwitchery" name="store_status">
                </div>
                <!-- /STATUS -->
                <!-- STATUS -->
                <div class="form-group pt-3">
                    <label for="lwIsActiveEditField">{{  __tr('Admin User Status') }}</label>
                    <input type="checkbox" id="lwIsActiveEditField" <%- __tData.status == 1 ? 'checked' : '' %> data-lw-plugin="lwSwitchery" name="status">
                </div>
                <!-- /STATUS -->
            </script>
            <!-- FORM FOOTER -->
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">{{ __tr('Submit') }}</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __tr('Close') }}</button>
            </div>
        </x-lw.form>
        <!--/  VENDOR FORM END -->
    </x-lw.modal>
    <!-- EDIT VENDOR MODAL END -->
    <!-- FOR CHANGE PASSWORD FOR VENDOR -->
    <x-lw.modal id="lwChangePasswordAuthor" :header="__tr('Change Password')" :hasForm="true">
        <!-- EDIT ACCOUNT FORM -->
        <x-lw.form class="mb-0" id="lwChangeAuthorPassword" :action="route('auth.vendor.change.password')"
            :data-callback-params="['modalId' => '#lwChangePasswordAuthor','datatableId' => '#lwAccountList']"
            data-callback="appFuncs.modelSuccessCallback" data-secured="true">
            <!-- FORM BODY -->
            <div id="lwChangePasswordBody" class="lw-form-modal-body"></div>
            <script type="text/template" id="lwChangePasswordBody-template">
                <!-- FORM FIELDS -->
                <input type="hidden" name="users_id" value="<%-__tData._id %>" />
                <!-- for new password -->
                <div class="form-group">
                    <label for="lwNewPasswordField">
                        <?= __tr('New Password') ?>
                    </label>
                    <div class="input-group input-group-alternative">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-key"></i></span>
                        </div>
                        <input type="password" class="lw-form-field form-control {{ $errors->has('password') ? ' is-invalid' : '' }}" placeholder="<?= __tr('New Password') ?>" id="lwNewPasswordField" value="" name="password" />
                    </div>
                </div>
                <!-- /NEW PASSWORD -->
                <!-- CONFIRM NEW PASSWORD -->
                <div class="form-group">
                    <label for="lwConfirmNewPasswordField">
                        <?= __tr('Confirm New Password') ?>
                    </label>
                    <div class="input-group input-group-alternative">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-key"></i></span>
                        </div>
                        <input type="password" class="lw-form-field form-control"
                        placeholder="<?= __tr('Confirm New Password') ?>" id="lwConfirmNewPasswordField" value=""
                        name="password_confirmation" />
                    </div>
                </div>
                <!-- /CONFIRM NEW PASSWORD-->
            </script>
            <!-- FORM FOOTER -->
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">{{ __tr('Change Password') }}</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __tr('Close') }}</button>
            </div>
        </x-lw.form>
        <!--/  EDIT VENDOR FORM -->
    </x-lw.modal>
    <!--/ EDIT VENDOR MODAL -->
    @push('footer')
    @endpush
    @push('appScripts')
    <script>
        (function($) {
            'use strict';
            window.afterSuccessfullyCreated = function (responseData) {
            if (responseData.reaction == 1) {
                __Utils.viewReload();
            }
        }
        })(jQuery);
    </script>
    @endpush

</div>
@endsection