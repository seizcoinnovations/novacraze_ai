@extends('layouts.app', ['title' => __tr('Subvendors - Advertisements')])

@section('content')
@include('users.partials.header', [
'title' => __tr('Advertisements') . ' '. auth()->user()->name,
'description' => '',
'class' => 'col-lg-7'
])

<div class="container-fluid">
    @php
        $allotted_adv_count = getUserAuthInfo('subscription_details')['advertisement_count'];
    @endphp
    @if ($totalAdvertisementCount >= $allotted_adv_count)
        <div class="alert alert-danger">
                {{  __tr('Limit exceeded, please upgrade your plan to add more Advertisement') }}
                <br>
        </div>
    @endif
</div>
<br><br>
<div class="container-fluid">
    <div class="row">
        @if ($totalAdvertisementCount < $allotted_adv_count)
            <div class="col-xl-12 mb-3 mt-md--5">
                <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#addAdvertisementModal">
                    <?= __tr('Add New Advertisement') ?>
                </button>
            </div>
       @endif
        <div class="col-xl-12">
            {{-- DATATABLE --}}
            <x-lw.datatable id="lwManageVendorsTable" :url="route('subvendors.bookings.read.list')" data-page-length="100">
                
                <th data-orderable="true" data-name="product">
                    <?= __tr('Product') ?>
                </th>
                <th data-orderable="true" data-name="booking_date">
                    <?= __tr('Booking Date') ?>
                </th>
                
                <th data-orderable="true" data-name="wa_number">
                    <?= __tr('WA Number') ?>
                </th>

                <th data-orderable="true" data-name="status_label">
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
        <!-- View ACTION -->
        <a data-pre-callback="appFuncs.clearContainer" title="{{ __tr('View') }}" class="lw-btn btn btn-sm btn-primary lw-ajax-link-action" data-response-template="#lwViewBookingBody" href="<%= __Utils.apiURL("{{ route('subvendor.booking.read.update.data', ['bookingIdOrUid']) }}", {'bookingIdOrUid': __tData._uid}) %>" data-toggle="modal" data-target="#lwViewBooking"><i class="fa fa-eye"></i> {{ __tr('View') }}</a>
        <!-- EDIT ACTION -->
        <a data-pre-callback="appFuncs.clearContainer" title="{{ __tr('Edit') }}" class="lw-btn btn btn-sm btn-default lw-ajax-link-action" data-response-template="#lwEditBookingBody" href="<%= __Utils.apiURL("{{ route('subvendor.booking.read.update.data', ['bookingIdOrUid']) }}", {'bookingIdOrUid': __tData._uid}) %>" data-toggle="modal" data-target="#lwEditBooking"><i class="fa fa-edit"></i> {{ __tr('Edit') }}</a>
        <!--  DELETE ACTION -->
        <a data-method="post" href="<%= __Utils.apiURL("{{ route('subvendor.booking.delete', ['bookingIdOrUid']) }}", {'bookingIdOrUid': __tData._id}) %>" class="btn btn-danger btn-sm lw-ajax-link-action-via-confirm" data-confirm="#lwDeletePlan-template" title="{{ __tr('Delete') }}" data-toggle="modal" data-target="#deletePlan" data-callback-params="{{ json_encode(['modalId' => '#lwDeletePlan-template','datatableId' => '#lwManageVendorsTable']) }}" data-callback="appFuncs.modelSuccessCallback"><i class="fa fa-trash"></i> {{ __tr('Delete') }}</a>
        
    </script>
    <script type="text/template" id="lwLoginAs-template">
        <h2>{{ __tr('Are You Sure!') }}</h2>
        <p>{{ __tr('You want login to this vendor admin account?') }}</p>
</script>
    <!-- VENDOR DELETE TEMPLATE -->
    <script type="text/template" id="lwDeletePlan-template">
        <h2><?= __tr('Are You Sure!') ?></h2>
            <p><?= __tr('Are you sure you want to delete this Booking?') ?></p>
    </script>
    <!-- /VENDOR DELETE TEMPLATE -->
    {{-- ADD BOOKING MODAL --}}
    <x-lw.modal id="addAdvertisementModal" :header="__tr('Add New Advertisement')" :hasForm="true">
        {{-- FORM START --}}
        <x-lw.form :action="route('subvendors.bookings.write.add')" data-callback="afterSuccessfullyCreated" enctype="multipart/form-data">
            <div class="lw-form-modal-body">
                {{-- Advertisement TITLE --}}
                <div class="form-group mb-3">
                    <div class="input-group input-group-alternative">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-user-alt"></i></span>
                        </div>
                        <input class="form-control" placeholder="{{ __tr('Advertisement Name') }}" type="text"
                            name="advertisement_name" value="{{ old('advertisement_name') }}" required>
                    </div>
                </div>
                {{-- Advertisement TITLE --}}
                {{-- Categories TITLE --}}
                <div class="form-group mb-3">
                    <div class="input-group input-group-alternative">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-user-alt"></i></span>
                        </div>
                        <select name="category_id" class="form-control" required>
                            <option value="">---Select Category----</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->_id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                {{-- Categories TITLE --}}
                {{-- Templates TITLE --}}
                <div class="form-group mb-3">
                    <div class="input-group input-group-alternative">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-user-alt"></i></span>
                        </div>
                        <select id="template" name="template_id" class="form-control" required>
                            <option value="">---Select Templates----</option>
                            @foreach ($templates as $template)
                                <option value="{{ $template->_id }}">{{ $template->template_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                {{-- Templates TITLE --}}
               {{-- Content TITLE --}}
               <div class="form-group mb-3">
                    <div class="input-group input-group-alternative">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-user-alt"></i></span>
                        </div>
                        <textarea class="form-control" required name="content"></textarea>
                    </div>
                </div>
                {{-- Content TITLE --}}
                {{-- Image --}}
               <div class="form-group mb-3">
                <div class="input-group input-group-alternative">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fa fa-user-alt"></i></span>
                    </div>
                    <input class="form-control"  type="file"
                            name="ad_image" required>
                </div>
            </div>
            {{-- Image --}}
                
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
    <!-- EDIT BOOKING MODAL -->
    <x-lw.modal id="lwEditBooking" :header="__tr('Edit Booking')" :hasForm="true">
        <!-- EDIT VENDOR FORM  -->
        <x-lw.form id="lwEditPlanForm" :action="route('subvendor.booking.write.update')"
            :data-callback-params="['modalId' => '#lwEditBooking', 'datatableId' => '#lwManageVendorsTable']"
            data-callback="appFuncs.modelSuccessCallback">
            <!-- form body -->
            <div data-default-text="{{ __tr('Please wait while we fetch data') }}" id="lwEditBookingBody"
                class="lw-form-modal-body"></div>
            <script type="text/template" id="lwEditBookingBody-template">
                <input type="hidden" name="bookingIdOrUid" value="<%- __tData._uid %>" />
                <!-- FORM FIELDS -->
                <!-- TITLE -->
                <div class="form-group">
                    <label for="lwTitleField"><?= __tr('Title') ?></label>
                    <div class="input-group input-group-alternative">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-user-alt"></i></span>
                        </div>
                        <input type="text" class="lw-form-field form-control" placeholder="<?= __tr('Product') ?>" id="lwTitleField" value="<%- __tData.product %>" name="product"/>
                    </div>
                </div>
                
                <!-- UserName  -->
                <div class="form-group">
                    <label for="lwUserNameEditField"><?= __tr('From') ?></label>
                    <div class="input-group input-group-alternative">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-id-card"></i></span>
                        </div>
                        <input type="text" class="lw-form-field form-control" placeholder="<?= __tr('Booking Date') ?>" id="lwUserNameEditField" value="<%- __tData.booking_date%>" name="booking_date" required="true" />
                    </div>
                </div>
                <!-- /UserName  -->
                <!-- FIRST NAME -->
                <div class="form-group">
                    <label for="lwDescriptionField"><?= __tr('To') ?></label>
                    <div class="input-group input-group-alternative">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-user-alt"></i></span>
                        </div>
                        <input type="text" class="lw-form-field form-control" placeholder="<?= __tr('WA Number') ?>" id="lwFirstNameField" value="<%- __tData.wa_number %>" name="wa_number"/>
                    </div>
                </div>
                <!-- /FIRST NAME -->
                <!-- LAST NAME -->
                <div class="form-group">
                    <label for="lwDescriptionField"><?= __tr('Description') ?></label>
                    <div class="input-group input-group-alternative">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-user-alt"></i></span>
                        </div>
                        <textarea class="lw-form-field form-control" placeholder="<?= __tr('Comments') ?>" id="lwLastNameField" value="<%- __tData.comments %>" name="comments">
                            <%- __tData.comments %>
                        </textarea>
                        <input type="text" />
                    </div>
                </div>
                <!-- /LAST NAME -->
                
                
            </script>
            <!-- FORM FOOTER -->
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">{{ __tr('Submit') }}</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __tr('Close') }}</button>
            </div>
        </x-lw.form>
        <!--/  VENDOR FORM END -->
    </x-lw.modal>
    <!-- EDIT BOOKING MODAL END -->
   
    <!--VIEW BOOKING MODAL -->
    <x-lw.modal id="lwViewBooking" :header="__tr('Booking Details')" :hasForm="true">
        <!-- EDIT VENDOR FORM  -->
        <x-lw.form id="lwEditPlanForm" :action="route('subvendor.booking.write.update')"
            :data-callback-params="['modalId' => '#lwViewBooking', 'datatableId' => '#lwManageVendorsTable']"
            data-callback="appFuncs.modelSuccessCallback">
            <!-- form body -->
            <div data-default-text="{{ __tr('Please wait while we fetch data') }}" id="lwViewBookingBody"
                class="lw-form-modal-body"></div>
            <script type="text/template" id="lwViewBookingBody-template">
                <input type="hidden" name="bookingIdOrUid" value="<%- __tData._uid %>" />
                <!-- FORM FIELDS -->
                <!-- TITLE -->
                <div class="form-group">
                    <label for="lwTitleField"><?= __tr('Title') ?></label>
                    <div class="input-group input-group-alternative">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-user-alt"></i></span>
                        </div>
                        <input type="text" class="lw-form-field form-control" placeholder="<?= __tr('Product') ?>" id="lwTitleField" value="<%- __tData.product %>" name="product" @disabled(true)/>
                    </div>
                </div>
                
                <!-- UserName  -->
                <div class="form-group">
                    <label for="lwUserNameEditField"><?= __tr('Booking Date') ?></label>
                    <div class="input-group input-group-alternative">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-id-card"></i></span>
                        </div>
                        <input type="text" class="lw-form-field form-control" placeholder="<?= __tr('Booking Date') ?>" id="lwUserNameEditField" value="<%- __tData.booking_date%>" name="booking_date" required="true" @disabled(true)/>
                    </div>
                </div>
                <!-- /UserName  -->
                <!-- FIRST NAME -->
                <div class="form-group">
                    <label for="lwDescriptionField"><?= __tr('WA Number') ?></label>
                    <div class="input-group input-group-alternative">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-user-alt"></i></span>
                        </div>
                        <input type="text" class="lw-form-field form-control" placeholder="<?= __tr('WA Number') ?>" id="lwFirstNameField" value="<%- __tData.wa_number %>" name="wa_number" @disabled(true)/>
                    </div>
                </div>
                <!-- /FIRST NAME -->
                <!-- FIRST NAME -->
                <div class="form-group">
                    <label for="lwDescriptionField"><?= __tr('Status') ?></label>
                    <div class="input-group input-group-alternative">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-user-alt"></i></span>
                        </div>
                        <input type="text" class="lw-form-field form-control" placeholder="<?= __tr('Status') ?>" id="lwFirstNameField" value="<%- __tData.status_label %>" name="status_label" @disabled(true)/>
                    </div>
                </div>
                <!-- /FIRST NAME -->
                <!-- LAST NAME -->
                <div class="form-group">
                    <label for="lwDescriptionField"><?= __tr('Description') ?></label>
                    <div class="input-group input-group-alternative">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-user-alt"></i></span>
                        </div>
                        <textarea class="lw-form-field form-control" placeholder="<?= __tr('Comments') ?>" id="lwLastNameField" value="<%- __tData.comments %>" name="comments" @disabled(true)>
                            <%- __tData.comments %>
                        </textarea>
                    </div>
                </div>
                <!-- /LAST NAME -->
            </script>
        </x-lw.form>
        <!--/  VENDOR FORM END -->
    </x-lw.modal>
    <!--VIEW BOOKING MODAL -->
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