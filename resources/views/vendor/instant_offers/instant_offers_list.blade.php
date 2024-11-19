@extends('layouts.app', ['title' => __tr('Subvendors')])

@section('content')
@include('users.partials.header', [
'title' => __tr('Instant Offers') . ' '. auth()->user()->name,
'description' => '',
'class' => 'col-lg-7'
])


<div class="container-fluid">
    <div class="row">
       
        <div class="col-xl-12">
            {{-- DATATABLE --}}
            <x-lw.datatable id="lwManageVendorsTable" :url="route('central.subvendors.instant_offers.read.list')" data-page-length="100">
                
                <th data-orderable="true" data-name="title">
                    <?= __tr('Title') ?>
                </th>
                <th data-orderable="true" data-name="status_label">
                    <?= __tr('Title') ?>
                </th>
                <th data-orderable="true" data-name="created_at">
                    <?= __tr('Created at') ?>
                </th>
                <th data-template="#actionButtons" name="null">
                    <?= __tr('Action') ?>
                </th>
            </x-lw.datatable>
            {{-- DATATABLE --}}
        </div>
    </div>
    
    <script type="text/template" id="actionButtons">

       

            <% if (__tData.status_label == 'Inactive') { %>
                <!-- EDIT ACTION -->
                <a data-method="post" href="<%= __Utils.apiURL("{{ route('central.subvendors.instant_offers.approve', ['instantofferIdOrUid']) }}", {'instantofferIdOrUid': __tData._id}) %>" class="btn btn-primary btn-sm lw-ajax-link-action-via-confirm" data-confirm="#lwApprovePlan-template" title="{{ __tr('Delete') }}" data-toggle="modal" data-target="#deletePlan" data-callback-params="{{ json_encode(['modalId' => '#lwApprovePlan-template','datatableId' => '#lwManageVendorsTable']) }}" data-callback="appFuncs.modelSuccessCallback"><i class="fa fa-check"></i> {{ __tr('Approve') }}</a>
                <!--  DELETE ACTION -->
                <a data-method="post" href="<%= __Utils.apiURL("{{ route('central.subvendors.instant_offers.reject', ['instantofferIdOrUid']) }}", {'instantofferIdOrUid': __tData._id}) %>" class="btn btn-danger btn-sm lw-ajax-link-action-via-confirm" data-confirm="#lwRejectPlan-template" title="{{ __tr('Delete') }}" data-toggle="modal" data-target="#deletePlan" data-callback-params="{{ json_encode(['modalId' => '#lwRejectPlan-template','datatableId' => '#lwManageVendorsTable']) }}" data-callback="appFuncs.modelSuccessCallback"><i class="fa fa-times"></i> {{ __tr('Reject') }}</a>

            <% } else if(__tData.status_label == 'Approved') {%>
                <a data-method="post" href="<%= __Utils.apiURL("{{ route('subvendor.instant_offer.delete', ['instantofferIdOrUid']) }}", {'instantofferIdOrUid': __tData._id}) %>" class="btn btn-success btn-sm lw-ajax-link-action-via-confirm" data-callback="appFuncs.modelSuccessCallback"><i class="fa fa-check"></i> {{ __tr('Approved') }}</a>
                <% } else if(__tData.status_label == 'Rejected') {%>
                    <a data-method="post" href="<%= __Utils.apiURL("{{ route('subvendor.instant_offer.delete', ['instantofferIdOrUid']) }}", {'instantofferIdOrUid': __tData._id}) %>" class="btn btn-danger btn-sm lw-ajax-link-action-via-confirm" data-callback="appFuncs.modelSuccessCallback"><i class="fa fa-times"></i> {{ __tr('Rejected') }}</a>
                <% } %>
        
        
        
    </script>
    <script type="text/template" id="lwLoginAs-template">
        <h2>{{ __tr('Are You Sure!') }}</h2>
        <p>{{ __tr('You want login to this vendor admin account?') }}</p>
    </script>
    <!-- Instnat offer reject TEMPLATE -->
    <script type="text/template" id="lwRejectPlan-template">
        <h2><?= __tr('Are You Sure!') ?></h2>
            <p><?= __tr('Are you sure you want to reject this Instant Offer?') ?></p>
    </script>
    <!-- /Instnat offer reject DELETE TEMPLATE -->

    <!-- Instnat offer approve TEMPLATE -->
    <script type="text/template" id="lwApprovePlan-template">
        <h2><?= __tr('Are You Sure!') ?></h2>
            <p><?= __tr('Are you sure you want to approve this Instant Offer?') ?></p>
    </script>
    <!-- /Instnat offer approve DELETE TEMPLATE -->
   
 
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