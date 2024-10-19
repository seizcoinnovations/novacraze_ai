@extends('layouts.app', ['class' => 'main-content-has-bg'])

@section('content')
@include('layouts.headers.guest')
{!! __yesset([
    'dist/css/app-public.css',
], true,
) !!}
{{-- <div class="container lw-guest-page-container-block pb-2" id="pageTop"> --}}
    <section id="pricing" class="pricing-content section-padding">
        <div class="container">
            <div class="section-title text-center">
                <div class="pricing-titles mb-4">
                    <h1 class="display-6">{{ __tr('Simple and Clear Subvendor Pricing Plans') }}</h1>
                </div>
            </div>
            <div class="row text-center">
               
                @foreach ($planStructure as $planKey => $plan)
                        @php
                        // print_r($plan);
                        @endphp
                            <div class="col-lg-4 col-md-6 col-sm-12 mb-4 wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.3s" data-wow-offset="0" style="visibility: visible; animation-duration: 1s; animation-delay: 0.3s; animation-name: fadeInUp;">
                                    <div class="pricing_design">
                                        <div class="single-pricing ">
                                            <div class="price-head">
                                                <h6 class="display-5 mb-4 text-uppercase">{{ $plan['title']}}</h6>
                                                <hr class="bg-success">
                                                {{-- @foreach ($charges as $itemKey => $itemValue)
                                                @php
                                                    if(!$itemValue['enabled']) {
                                                        continue;
                                                    }
                                                @endphp
                                                <h2 class="price mb-1">{{ formatAmount($itemValue['charge'], true, true) }}</h2>
                                                <span>{{ Arr::get($plan['charges'][$itemKey], 'title', '') }}</span>
                                                <br><br>
                                                @endforeach
                                                <small><a class="text-muted" target="_blank" href="https://business.whatsapp.com/products/platform-pricing">{{  __tr('+ WhatsApp Cloud Messaging Charges') }} <i class="fas fa-external-link-alt"></i></a></small> --}}
                                            </div>
                                            <hr class="bg-success mt-4">
                                            <ul>
                                                @foreach ($plan['features'] as $featureKey => $featureValue)
                                            @php
                                                $configFeatureValue = $featureValue;
                                                // $featureValue = $savedPlan['features'][$featureKey];
                                            @endphp
                                                <li>
                                                    {{-- @if (isset($featureValue['type']) and ($featureValue['type'] == 'switch'))
                                                    @if (isset($featureValue['limit']) and $featureValue['limit'])
                                                    <i class="fa fa-check mr-3 bg-success"></i>
                                                    @else
                                                    <i class="fa fa-times mr-3 bg-danger"></i>
                                                    @endif
                                                    {{ ($configFeatureValue['description']) }}
                                                    @else
                                                    <strong>@if (isset($featureValue['limit']) and $featureValue['limit'] < 0) {{ __tr('Unlimited') }} @elseif(isset($featureValue['limit'])) {{ __tr($featureValue['limit']) }} @endif </strong> {{ ($configFeatureValue['description']) }} {{ ($configFeatureValue['limit_duration_title'] ?? '') }}
                                                    @endif --}}
                                                </li>
                                            @endforeach
                                            </ul>
                                            <div class="pricing-price">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--- END COL -->
                        @endforeach
            </div><!--- END ROW -->
        </div><!--- END CONTAINER -->
    </section>
{{-- </div> --}}


@endsection