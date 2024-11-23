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
            <x-lw.datatable id="lwManageVendorsTable" :url="route('subvendors.advertisements.read.list')" data-page-length="100">
                
                <th data-orderable="true" data-name="advertisement_name">
                    <?= __tr('Advertisement Name') ?>
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
    <script type="text/template" id="titleExtendedButtons">
        <a  href ="<%= __Utils.apiURL("{{ route('vendor.dashboard',['vendorIdOrUid'=>'vendorIdOrUid'])}}", {'vendorIdOrUid':__tData._uid}) %>"> <%-__tData.title %> </a> 
    </script>
    <script type="text/template" id="lwQuickActionButtons">
        <a data-method="post" href="<%= __Utils.apiURL("{{ route('central.vendors.user.write.login_as', [ 'vendorUid']) }}", {'vendorUid': __tData._uid}) %>" class="btn btn-light btn-sm lw-ajax-link-action" data-confirm="#lwLoginAs-template" title="{{ __tr('Login as Vendor Admin') }}"><i class="fa fa-sign-in-alt"></i> {{  __tr('Login') }}</a>
        <a class="btn btn-primary btn-sm" href ="<%= __Utils.apiURL("{{ route('central.vendor.details',['vendorIdOrUid'=>'vendorIdOrUid'])}}", {'vendorIdOrUid':__tData._uid}) %>"> {{  __tr('Subscription') }} </a>
    </script>
    <script type="text/template" id="actionButtons">
        <!-- View ACTION -->
        <a data-pre-callback="appFuncs.clearContainer" title="{{ __tr('View') }}" class="lw-btn btn btn-sm btn-primary lw-ajax-link-action" data-response-template="#lwAdvertisementBookingBody" href="<%= __Utils.apiURL("{{ route('subvendor.advertisement.read.update.data', ['bookingIdOrUid']) }}", {'bookingIdOrUid': __tData._uid}) %>" data-toggle="modal" data-target="#lwAdvertisementBooking"><i class="fa fa-eye"></i> {{ __tr('View') }}</a>
        <!-- EDIT ACTION -->
        <a data-pre-callback="appFuncs.clearContainer" title="{{ __tr('Edit') }}" class="lw-btn btn btn-sm btn-default lw-ajax-link-action" data-response-template="#lwEditBookingBody" href="<%= __Utils.apiURL("{{ route('subvendor.advertisement.read.update.data', ['advertisementIdOrUid']) }}", {'advertisementIdOrUid': __tData._uid}) %>" data-toggle="modal" data-target="#lwEditBooking"><i class="fa fa-edit"></i> {{ __tr('Edit') }}</a>
        <!--  DELETE ACTION -->
        <a data-method="post" href="<%= __Utils.apiURL("{{ route('subvendor.advertisement.delete', ['advertisementIdOrUid']) }}", {'advertisementIdOrUid': __tData._uid}) %>" class="btn btn-danger btn-sm lw-ajax-link-action-via-confirm" data-confirm="#lwDeletePlan-template" title="{{ __tr('Delete') }}" data-toggle="modal" data-target="#deletePlan" data-callback-params="{{ json_encode(['modalId' => '#lwDeletePlan-template','datatableId' => '#lwManageVendorsTable']) }}" data-callback="appFuncs.modelSuccessCallback"><i class="fa fa-trash"></i> {{ __tr('Delete') }}</a>
        
    </script>
    <script type="text/template" id="lwLoginAs-template">
        <h2>{{ __tr('Are You Sure!') }}</h2>
        <p>{{ __tr('You want login to this vendor admin account?') }}</p>
</script>
    <!-- VENDOR DELETE TEMPLATE -->
    <script type="text/template" id="lwDeletePlan-template">
        <h2><?= __tr('Are You Sure!') ?></h2>
            <p><?= __tr('Are you sure you want to delete this Advertisement?') ?></p>
    </script>
    <!-- /VENDOR DELETE TEMPLATE -->
    {{-- ADD ADVERTISEMENT MODAL --}}
    <x-lw.modal id="addAdvertisementModal" :header="__tr('Add New Advertisement')" :hasForm="true">
        {{-- FORM START --}}
        <x-lw.form :action="route('subvendors.advertisement.write.add')" data-callback="afterSuccessfullyCreated" enctype="multipart/form-data">
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
                                <option value="{{ $template->_id }}" data-content="{{ $template->template }}">{{ $template->template_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                {{-- Templates TITLE --}}
                {{-- Dynamic Content --}}
                <div id="template-description" class="form-group mb-3">
                    <div id="template-dynamic-content" class="alert alert-info"></div>
                </div>
                <input type="hidden" name="final_content" id="final-content">
                {{-- <div class="form-group mb-3" id="template-description" style="display: none;">
                    <label for="template-dynamic-content">Template Details:</label>
                    
                </div> --}}
                {{-- Dynamic Content --}}
               {{-- Content TITLE --}}
               {{-- <div class="form-group mb-3">
                    <div class="input-group input-group-alternative">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-user-alt"></i></span>
                        </div>
                        <textarea class="form-control" required name="content"></textarea>
                    </div>
                </div> --}}
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
    <!-- EDIT ADVERTISEMENT MODAL -->
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
    <!-- EDIT ADVERTISEMENT MODAL END -->
   
    <!--VIEW ADVERTISEMENT MODAL -->
    <x-lw.modal id="lwAdvertisementBooking" :header="__tr('Advertisement Details')" :hasForm="true">
        <!-- EDIT VENDOR FORM  -->
        <x-lw.form id="lwEditPlanForm" :action="route('subvendor.booking.write.update')"
            :data-callback-params="['modalId' => '#lwAdvertisementBooking', 'datatableId' => '#lwManageVendorsTable']"
            data-callback="appFuncs.modelSuccessCallback">
            <!-- form body -->
            <div data-default-text="{{ __tr('Please wait while we fetch data') }}" id="lwAdvertisementBookingBody"
                class="lw-form-modal-body"></div>
            <script type="text/template" id="lwAdvertisementBookingBody-template">
                <input type="hidden" name="bookingIdOrUid" value="<%- __tData._uid %>" />
                <!-- FORM FIELDS -->
                <!-- TITLE -->
                <div class="form-group">
                    <label for="lwTitleField"><?= __tr('Advertisement Name') ?></label>
                    <div class="input-group input-group-alternative">
                        <input type="text" class="lw-form-field form-control" placeholder="<?= __tr('Advertisement Name') ?>" id="lwTitleField" value="<%- __tData.advertisement_name %>" name="adname" @disabled(true)/>
                    </div>
                </div>
                
                <!-- UserName  -->
                <div class="form-group">
                    <label for="lwUserNameEditField"><?= __tr('Category') ?></label>
                    <div class="input-group input-group-alternative">
                        
                        <input type="text" class="lw-form-field form-control" placeholder="<?= __tr('Category') ?>" id="lwUserNameEditField" value="<%- __tData.category_name%>" name="Category" required="true" @disabled(true)/>
                    </div>
                </div>
                <!-- /UserName  -->
                <!-- FIRST NAME -->
                <div class="form-group">
                    <label for="lwDescriptionField"><?= __tr('Template Name') ?></label>
                    <div class="input-group input-group-alternative">
                        
                        <input type="text" class="lw-form-field form-control" placeholder="<?= __tr('Template Name') ?>" id="lwFirstNameField" value="<%- __tData.template_name %>" name="wa_number" @disabled(true)/>
                    </div>
                </div>
                <!-- /FIRST NAME -->
                <!-- FIRST NAME -->
                <div class="form-group">
                    <label for="lwDescriptionField"><?= __tr('Content') ?></label>
                    <div class="input-group input-group-alternative">
                        <textarea class="lw-form-field form-control"  @disabled(true)><%- __tData.content_filled %></textarea>
                        
                    </div>
                </div>
                <!-- /FIRST NAME -->
                <!-- LAST NAME -->
                <div class="form-group">
                    <label for="lwDescriptionField"><?= __tr('Created at') ?></label>
                    <div class="input-group input-group-alternative">
                        <input type="text" class="lw-form-field form-control" placeholder="<?= __tr('Template Name') ?>" id="lwFirstNameField" value="<%- new Date(__tData.created_at).toLocaleDateString('en-GB') %>"  name="wa_number" @disabled(true)/>
                    </div>
                </div>
                <!-- /LAST NAME -->
            </script>
        </x-lw.form>
        <!--/  VENDOR FORM END -->
    </x-lw.modal>
    <!--VIEW ADVERTISEMENT MODAL -->
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

        document.addEventListener('DOMContentLoaded', function () {
        const templateSelect = document.getElementById('template');
        const dynamicContentContainer = document.getElementById('template-dynamic-content');

        templateSelect.addEventListener('change', function () {
            const selectedOption = templateSelect.options[templateSelect.selectedIndex];
            const templateContent = selectedOption.getAttribute('data-content');
            
            dynamicContentContainer.innerHTML = ''; // Clear existing fields

            if (templateContent) {
                const fields = parseTemplate(templateContent);
                dynamicContentContainer.appendChild(fields);
            }
        });

        // Parse the template content and create input fields
        function parseTemplate(template) {
            const container = document.createElement('div');
            const regex = /_{3,}/g; // Matches 3 or more underscores
            let match;
            let lastIndex = 0;

            // Dynamically replace underscores with input fields
            while ((match = regex.exec(template)) !== null) {
                const textBefore = template.slice(lastIndex, match.index);
                if (textBefore) {
                    container.appendChild(document.createTextNode(textBefore));
                }

                const input = document.createElement('input');
                input.type = 'text';
                input.name = 'dynamic_fields[]'; // Collect all dynamic fields in an array
                input.className = 'form-control d-inline-block mx-1';
                input.style.width = 'auto';
                container.appendChild(input);

                lastIndex = regex.lastIndex;
            }

            // Add remaining text after the last match
            const remainingText = template.slice(lastIndex);
            if (remainingText) {
                container.appendChild(document.createTextNode(remainingText));
            }

            return container;
        }
    });

    document.addEventListener('DOMContentLoaded', function () {
    const templateSelect = document.getElementById('template'); // Ensure this matches the ID of your select element

    document.querySelector('form').addEventListener('submit', function (e) {
        e.preventDefault(); // Prevent default form submission for testing
        const dynamicFields = document.querySelectorAll('input[name="dynamic_fields[]"]');
        const selectedOption = templateSelect.options[templateSelect.selectedIndex];
        const templateContent = selectedOption.getAttribute('data-content');

        if (!templateContent) {
            console.error('Template content is missing!');
            return;
        }

        let finalContent = templateContent;
        dynamicFields.forEach((input) => {
            finalContent = finalContent.replace(/_{3,}/, input.value);
        });

        document.getElementById('final-content').value = finalContent;

        console.log('Final Content:', finalContent); // Log for testing
        // Uncomment the following line to allow form submission after processing
        // e.target.submit();
    });
});


    </script>
    @endpush
<style>
.form-control {

  border-bottom: 1px solid #cad1d7 !important;
  border-left: none !important;
  border-right: none !important;
  border-top: none !important;
  margin-bottom: 10px;
}
</style>
</div>
@endsection