@extends('layouts.app', ['title' => __tr('Subvendors_ Bookings')])

@section('content')
@include('users.partials.header', [
'title' => __tr('Bookings') . ' '. auth()->user()->name,
'description' => '',
'class' => 'col-lg-7'
])


<div class="container-fluid">
    <div class="row">
       
        <div class="col-xl-12">
            {{-- DATATABLE --}}
            <x-lw.datatable id="lwManageVendorsTable" :url="route('central.subvendors.bookings.read.list')" data-page-length="100">
                
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
    
    <script type="text/template" id="actionButtons">

             <!-- View ACTION -->
            <a data-pre-callback="appFuncs.clearContainer" title="{{ __tr('View') }}" class="lw-btn btn btn-sm btn-primary lw-ajax-link-action" data-response-template="#lwViewBookingBody" href="<%= __Utils.apiURL("{{ route('central.subvendors.booking.read.data', ['bookingIdOrUid']) }}", {'bookingIdOrUid': __tData._uid}) %>" data-toggle="modal" data-target="#lwViewBooking"><i class="fa fa-eye"></i> {{ __tr('View') }}</a>

            <% if (__tData.status_label == 'Inactive') { %>
                <!-- EDIT ACTION -->
                <a data-method="post" href="<%= __Utils.apiURL("{{ route('central.subvendors.bookings.approve', ['bookingIdOrUid']) }}", {'bookingIdOrUid': __tData._id}) %>" class="btn btn-primary btn-sm lw-ajax-link-action-via-confirm" data-confirm="#lwApprovePlan-template" title="{{ __tr('Delete') }}" data-toggle="modal" data-target="#deletePlan" data-callback-params="{{ json_encode(['modalId' => '#lwApprovePlan-template','datatableId' => '#lwManageVendorsTable']) }}" data-callback="appFuncs.modelSuccessCallback"><i class="fa fa-check"></i> {{ __tr('Approve') }}</a>
                <!--  DELETE ACTION -->
                <a data-method="post" href="<%= __Utils.apiURL("{{ route('central.subvendors.bookings.reject', ['bookingIdOrUid']) }}", {'bookingIdOrUid': __tData._id}) %>" class="btn btn-danger btn-sm lw-ajax-link-action-via-confirm" data-confirm="#lwRejectPlan-template" title="{{ __tr('Delete') }}" data-toggle="modal" data-target="#deletePlan" data-callback-params="{{ json_encode(['modalId' => '#lwRejectPlan-template','datatableId' => '#lwManageVendorsTable']) }}" data-callback="appFuncs.modelSuccessCallback"><i class="fa fa-times"></i> {{ __tr('Reject') }}</a>

            <% } else if(__tData.status_label == 'Approved') {%>
                <a data-method="post" href="<%= __Utils.apiURL("{{ route('subvendor.instant_offer.delete', ['bookingIdOrUid']) }}", {'bookingIdOrUid': __tData._id}) %>" class="btn btn-success btn-sm lw-ajax-link-action-via-confirm" data-callback="appFuncs.modelSuccessCallback"><i class="fa fa-check"></i> {{ __tr('Approved') }}</a>
                <% } else if(__tData.status_label == 'Rejected') {%>
                    <a data-method="post" href="<%= __Utils.apiURL("{{ route('subvendor.instant_offer.delete', ['bookingIdOrUid']) }}", {'bookingIdOrUid': __tData._id}) %>" class="btn btn-danger btn-sm lw-ajax-link-action-via-confirm" data-callback="appFuncs.modelSuccessCallback"><i class="fa fa-times"></i> {{ __tr('Rejected') }}</a>
                <% } %>
        
        
        
    </script>
    <script type="text/template" id="lwLoginAs-template">
        <h2>{{ __tr('Are You Sure!') }}</h2>
        <p>{{ __tr('You want login to this vendor admin account?') }}</p>
    </script>
    <!-- Instnat offer reject TEMPLATE -->
    <script type="text/template" id="lwRejectPlan-template">
        <h2><?= __tr('Are You Sure!') ?></h2>
            <p><?= __tr('Are you sure you want to reject this Booking?') ?></p>
    </script>
    <!-- /Instnat offer reject DELETE TEMPLATE -->

    <!-- Instnat offer approve TEMPLATE -->
    <script type="text/template" id="lwApprovePlan-template">
        <h2><?= __tr('Are You Sure!') ?></h2>
            <p><?= __tr('Are you sure you want to approve this Booking?') ?></p>
    </script>
    <!-- /Instnat offer approve DELETE TEMPLATE -->
   
    <!--VIEW BOOKING MODAL -->
    <x-lw.modal id="lwViewBooking" :header="__tr('Booking Details')" :hasForm="true">
        <!-- EDIT VENDOR FORM  -->
        <x-lw.form id="lwEditPlanForm"
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