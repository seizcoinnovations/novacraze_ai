@php
/**
* Component : WhatsAppService
* Controller : WhatsAppServiceController
* File : templates.list.blade.php
----------------------------------------------------------------------------- */
@endphp
@extends('layouts.app', ['title' => __tr('Templates List')])
@section('content')
@include('users.partials.header', [
'title' => __tr('WhatsApp Templates'),
'description' => '',
'class' => 'col-lg-7'
])
<div class="container-fluid mt-lg--6">
    <div class="row">
        <!-- button -->
        <div class="col-xl-12 mb-3">
           <div class="float-right">
            <a class="lw-btn btn btn-primary lw-ajax-link-action" data-callback="reloadDtOnSuccess" data-method="post" href="{{ route('vendor.whatsapp_service.templates.write.sync') }}" > {{ __tr('Sync WhatsApp Templates') }}</a>
            <a class="lw-btn btn btn-default" target="_blank" href="https://business.facebook.com/wa/manage/message-templates/?waba_id={{ getVendorSettings('whatsapp_business_account_id') }}" > {{ __tr('Manage Templates') }} <i class="fas fa-external-link-alt"></i></a>
           </div>
        </div>
        <!--/ button -->
        <div class="col-xl-12">
            <x-lw.datatable id="lwTemplatesList"  data-page-length="100" :url="route('vendor.whatsapp_service.templates.read.list')">
                <th data-orderable="true" data-name="template_name">{{ __tr('Name') }}</th>
                <th data-orderable="true" data-name="language">{{ __tr('Language') }}</th>
                <th data-orderable="true" data-name="category">{{ __tr('Category') }}</th>
                <th data-orderable="true" data-name="status">{{ __tr('Status') }}</th>
                <th data-orderable="true" data-order-by="true" data-order-type="desc" data-name="updated_at">{{ __tr('Last Sync') }}</th>
                <th data-template="#templatesActionColumnTemplate" name="null">{{ __tr('Action') }}</th>
            </x-lw.datatable>
        </div>
        <!-- action template -->
        <script type="text/template" id="templatesActionColumnTemplate">
            <a target="_blank" title="{{  __tr('Edit Template') }}" class="lw-btn btn btn-sm btn-default" href="https://business.facebook.com/wa/manage/message-templates/?&waba_id={{ getVendorSettings('whatsapp_business_account_id') }}&id=<%- __tData.template_id %>">{{  __tr('Edit Template') }} <i class="fas fa-external-link-alt"></i></a>
            <a title="{{  __tr('Delete Template') }}" data-callback="reloadDtOnSuccess" data-method="post" data-confirm="#lwConfirmTemplateDelete" data-confirm-params="<%- toJsonString({'templateName': __tData.template_name}) %>" class="lw-btn btn btn-sm btn-danger lw-ajax-link-action" href="<%= __Utils.apiURL(" {{ route('vendor.whatsapp_service.templates.write.delete',['whatsappTemplateUid']) }}", {'whatsappTemplateUid': __tData._uid}) %>">{{  __tr('Delete Template') }}</a>
        </script>
        <!-- /action template -->
        <script type="text/template" id="lwConfirmTemplateDelete">
            <h3>{!! __tr('Are you sure you want to delete __templateName__ template', [
                '__templateName__' => '<strong><%- __tData.templateName %></strong>'
                ]) !!}</h3>
        </script>
    </div>
</div>
@endsection()