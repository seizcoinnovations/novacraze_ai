@php
/**
* File          : subscription-plans.blade.php
----------------------------------------------------------------------------- */
@endphp
@extends('layouts.app', ['title' => ($pageTitle ?? '')])
@section('content')
    @include('users.partials.header', [
    'title' => __tr('Subscription Plans') . ' '. auth()->user()->name,
    'description' => '',
    'class' => 'col-lg-12'
    ])
    <!-- Start of Page Wrapper -->
    <div class="container-fluid accordion" id="lwSubscriptionPlansContainer">
        <div class="row">
            @foreach ($planStructure as $planKey => $plan)
                @php
                    $planId = $plan['id'];
                    $planName = $plan['plan_id'];
                    $features = $plan['features'];
                @endphp
                <div class="col-xl-12 mb-5">
                    <div class="card">
                        <h3 class="card-header" data-toggle="collapse" data-target="#lwPaidBlock{{ $planName }}" aria-expanded="true"
                        aria-controls="lwPaidBlock{{ $planName }}">
                            #{{ $planName }} {{ __tr('Plan Configurations') }}
                        </h3>

                        <div class="card-body collapse" id="lwPaidBlock{{ $planName }}" data-parent="#lwSubscriptionPlansContainer">
                            <x-lw.form id="lwAddNewPlanForm-{{ $planId }}"
                                :action="route('manage.configuration.subscription-plans.write.update')">
                                <input type="hidden" name="config_plan_id" value="{{ $planId }}">

                                <x-lw.checkbox id="select_{{ $planId }}" name="enabled" :checked="$plan['enabled']"
                                data-lw-plugin="lwSwitchery"
                                    :label="__tr('Enable this Plan')" />
                                <hr class="my-3">

                                <x-lw.input-field type="text" id="{{ $planId }}_title" name="title"
                                :label="__tr('Title')" required placeholder="{{ __tr('your plan title') }}"
                                value="{{ strtr($savedPlan['title'] ?? $plan['title'] ?? '', ['__title__' => '']) ?? '' }}" />

                                <h3 class="text-danger mt-4">{{ __tr('Feature Limits') }}</h3>
                                <div class="row pl-3">
                                    @if (!__isEmpty($features))
                                        @foreach ($features as $featureKey => $feature)
                                            @php
                                                $structureFeature = $feature;
                                            @endphp
                                            <fieldset class="col-xl-3 mr-4 float-left">
                                                <legend>{{ $structureFeature['description'] }}</legend>
                                                @if (isset($structureFeature['type']) and ($structureFeature['type'] == 'Switch'))
                                                    <input type="hidden" name="{{ $featureKey }}_limit" value="0">
                                                    <x-lw.checkbox id="{{ $planId }}_{{ $featureKey }}_limit" name="{{ $featureKey }}_limit" data-lw-plugin="lwSwitchery" :checked="$feature['limit']" :label="__tr('Enable __itemDescription__', [
                                                        '__itemDescription__' => $structureFeature['description']
                                                    ])" value="1" />
                                                @else
                                                    <x-lw.input-field :appendText="$structureFeature['limit_duration_title'] ?? ''" type="number"
                                                    id="{{ $planId }}_{{ $featureKey }}_limit"
                                                    name="{{ $featureKey }}_limit" :label="$structureFeature['description']"
                                                    required min="-1" placeholder="{{ $feature['description'] }}"
                                                    value="{{ is_numeric($feature['limit'] ?? '') ? $feature['limit'] : '' }}"
                                                    :helpText="__tr('Use -1 for unlimited')" />                     
                                                @endif
                                            </fieldset>
                                        @endforeach
                                    @endif
                                </div>
                                <h3 class="text-danger mt-4">{{ __tr('Charges') }}</h3>
                                @if (!__isEmpty($plan['charges']))
                                    <div class="row">
                                        <div class="col-xl-12">
                                            @foreach ($plan['charges'] as $itemKey => $itemValue)
                                                @php
                                                    $description = $itemValue['description'];
                                                @endphp
                                                <fieldset class="col-xl-3 float-left mr-4">
                                                    <legend>{{ $description }}</legend>
                                                    @if ($description == 'Months')
                                                        <x-lw.input-field type="number"
                                                            id="{{ $planId }}_{{ $description }}_months"
                                                            name="{{ $description }}_charge" :label="__tr('Months')"
                                                            min="0" placeholder="{{ __tr('Months') }}"
                                                            value="{{ $itemValue['value'] }}">
                                                        </x-lw.input-field>
                                                    @else
                                                        <x-lw.input-field type="number"
                                                            id="{{ $planId }}_{{ $description }}_charge"
                                                            name="{{ $description }}_charge" :label="__tr('Charge Amount')"
                                                            min="0" placeholder="{{ __tr('Charge Amount') }}"
                                                            value="{{ $itemValue['value'] }}">
                                                            <x-slot name="prependText">
                                                                {{ getCurrencySymbol() }}
                                                            </x-slot>
                                                            <x-slot name="appendText">
                                                                {{ getCurrency() }}
                                                            </x-slot>
                                                        </x-lw.input-field>
                                                    @endif
                                                    
                                                </fieldset>
                                            @endforeach
                                        </div>  
                                    </div>
                                @endif
                                <div class="mt-5">
                                    <!-- Submit Button -->
                                    <button type="submit" class="btn btn-primary">{{ __tr('Update') }}</button>
                                </div>
                            </x-lw.form>
                        </div>
                        
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
