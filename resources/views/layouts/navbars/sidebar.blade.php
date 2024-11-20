<nav class="navbar navbar-vertical fixed-left navbar-expand-md navbar-light bg-white" id="sidenav-main">
    <div class="container-fluid">
        <span>
            <!-- Toggler -->
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#sidenav-collapse-main"
        aria-controls="sidenav-main" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <!-- Brand -->
    <a class="navbar-brand pt-0 d-none d-sm-inline" href="{{ url('/') }}">
        <img src="{{ getAppSettings('logo_image_url') }}" class="navbar-brand-img"
            alt="{{ getAppSettings('name') }}">
    </a>
        </span>
        <!-- User -->
        <ul class="nav align-items-center d-md-none">
            <li class="nav-item">
                @include('layouts.navbars.locale-menu')
              </li>
            <li class="nav-item dropdown">
                <a class="nav-link" href="#" role="button" data-toggle="dropdown" aria-haspopup="true"
                    aria-expanded="false">
                    <div class="media align-items-center">
                        <span class="avatar avatar-sm rounded-circle">
                            <i class="fa fa-user"></i>
                        </span>
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-right">
                    <div class=" dropdown-header noti-title">
                        <h6 class="text-overflow m-0">{{ __tr('Welcome!') }}</h6>
                    </div>
                    <a href="{{ route('user.profile.edit') }}" class="dropdown-item">
                        <i class="fa fa-user"></i>
                        <span>{{ __tr('My profile') }}</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a data-method="post" href="{{ route('auth.logout') }}" class="dropdown-item lw-ajax-link-action">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>{{ __tr('Logout') }}</span>
                    </a>
                </div>
            </li>
        </ul>
        <!-- Collapse -->
        <div class="collapse navbar-collapse" id="sidenav-collapse-main">
            <!-- Collapse header -->
            <div class="navbar-collapse-header d-md-none">
                <div class="row">
                    <div class="col-6 collapse-brand">
                        <a href="{{ url('/') }}">
                            <img src="{{ getAppSettings('logo_image_url') }}">
                        </a>
                    </div>
                    <div class="col-6 collapse-close">
                        <button type="button" class="navbar-toggler" data-toggle="collapse"
                            data-target="#sidenav-collapse-main" aria-controls="sidenav-main" aria-expanded="false"
                            aria-label="Toggle sidenav">
                            <span></span>
                            <span></span>
                        </button>
                    </div>
                </div>
            </div>
            <!-- Navigation -->
            <ul class="navbar-nav">
                @if (hasCentralAccess())
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('central.console') }}">
                            <i class="fa fa-tachometer-alt text-danger"></i> {{ __tr('Dashboard') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('central.vendors') }}">
                            <i class="fa fa-store text-yellow"></i> {{ __tr('Vendors') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#lwSubscriptionSubMenu" data-toggle="collapse" role="button"
                            aria-expanded="true" aria-controls="lwSubscriptionSubMenu">
                            <i class="fa fa-user-tag text-blue"></i>
                            <span class="nav-link-text">{{ __tr('Subscriptions') }}</span>
                        </a>
                        <div class="collapse show lw-expandable-nav" id="lwSubscriptionSubMenu">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('central.subscriptions') }}">
                                        <i class="fa fa-user-tag text-orange"></i> {{ __tr('Auto') }}
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('central.subscription.manual_subscription.read.list_view') }}">
                                        <i class="fa fa-user-tag text-orange"></i> {{ __tr('Manual') }} @if(getPendingSubscriptionCount())<span class="badge badge-danger ml-2">{{ getPendingSubscriptionCount() }}</span> @endif
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('manage.translations.languages') }}">
                            <i class="fa fa-language text-primary"></i> {{ __tr('Translations') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#configurationMenu" data-toggle="collapse" role="button"
                            aria-expanded="true" aria-controls="configurationMenu">
                            <i class="fa fa-cogs text-blue"></i>
                            <span class="nav-link-text">{{ __tr('Configurations') }}</span>
                        </a>

                        <div class="collapse show lw-expandable-nav" id="configurationMenu">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a class="nav-link {{ request('pageType') == 'general' ? 'active' : '' }}"
                                        href="{{ route('manage.configuration.read', ['pageType' => 'general']) }}">
                                        <small class="mr-2"><i class="fa fa-cog text-default"></i></small>
                                        {{ __tr('General') }}
                                    </a>
                                </li>
                                <li class="nav-item {{ request('pageType') == 'user' ? 'active' : '' }}">
                                    <a class="nav-link"
                                        href="{{ route('manage.configuration.read', ['pageType' => 'user']) }}">
                                        <small class="mr-2"><i class="fa fa-user text-info"></i></small>
                                        {!! __tr('User & Vendor') !!}
                                    </a>
                                </li>
                                <li class="nav-item {{ request('pageType') == 'currency' ? 'active' : '' }}">
                                    <a class="nav-link"
                                        href="{{ route('manage.configuration.read', ['pageType' => 'currency']) }}">
                                        <small class="mr-2"><i class="fa fa-money-check-alt text-success"></i></small>
                                        {{ __tr('Currency') }}
                                    </a>
                                </li>
                                <li class="nav-item {{ markAsActiveLink('manage.configuration.payment') }}">
                                    <a class="nav-link <?= (isset($pageType) and $pageType == 'payment') ? 'active' : '' ?>"
                                        href="<?= route('manage.configuration.read', ['pageType' => 'payment']) ?>">
                                        <small class="mr-2"><i class="fa fa-money-check-alt text-yellow"></i></small>
                                        {{ __tr('Payment Gateways') }}
                                    </a>
                                </li>
                                <li class="nav-item {{ markAsActiveLink('manage.configuration.subscription-plans') }}">
                                    <a class="nav-link" href="{{ route('manage.configuration.subscription-plans') }}">
                                        <small class="mr-2"><i class="fa fa-user text-danger"></i></small>
                                        {{ __tr('Subscription Plans') }}
                                    </a>
                                </li>
                                <li class="nav-item {{ request('pageType') == 'email' ? 'active' : '' }}">
                                    <a class="nav-link"
                                        href="{{ route('manage.configuration.read', ['pageType' => 'email']) }}">
                                        <small class="mr-2"><i class="fa fa-at text-warning"></i></small>
                                        {{ __tr('Email') }}
                                    </a>
                                </li>
                                <li class="nav-item {{ request('pageType') == 'social-login' ? 'active' : '' }}">
                                    <a class="nav-link"
                                        href="{{ route('manage.configuration.read', ['pageType' => 'social-login']) }}">
                                        <small class="mr-2"><i class="fas fa-user-plus text-danger"></i></small>
                                        {{ __tr('Social Login') }}
                                    </a>
                                </li>
                                <li class="nav-item {{ request('pageType') == 'other' ? 'active' : '' }}">
                                    <a class="nav-link"
                                        href="{{ route('manage.configuration.read', ['pageType' => 'other']) }}">
                                        <small class="mr-2"><i class="fa fa-cog text-danger"></i></small>
                                        {!! __tr('Setup & Integrations') !!}
                                    </a>
                                </li>
                                <li class="nav-item <?= Request::fullUrl() == route('manage.configuration.read', ['pageType' => 'licence-information']) ? 'active' : '' ?>">
                                    <a class="nav-link"  href="<?= route('manage.configuration.read', ['pageType' => 'licence-information']) ?>">
                                        <i class="fas fa-certificate"></i>
                                        <span><?= __tr('License') ?></span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endif
                @if (hasVendorAccess() or hasVendorUserAccess())
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('vendor.console') }}">
                                <i class="fa fa-tachometer-alt text-danger"></i>
                                {{ __tr('Dashboard') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#" data-toggle="modal" data-target="#lwScanMeDialog">
                                <i class="fa fa-qrcode text-blue"></i>
                                {{ __tr('QR Code') }}
                            </a>
                        </li>
                        @if (hasVendorAccess('subvendor'))
                    
                            <li class="nav-item">
                                <a class="nav-link" href="#vendorSubvendorSubmenuNav" data-toggle="collapse" role="button"
                                    aria-expanded="true" aria-controls="vendorSubvendorSubmenuNav">
                                    <i class="fa fa-truck text-red"></i>
                                    <span class="">{{ __tr('SubVendor') }}</span>
                                </a>
                                <div class="collapse show lw-expandable-nav" id="vendorSubvendorSubmenuNav">
                                    <ul class="nav nav-sm flex-column">
                                        <li class="nav-item">
                                            <a class="nav-link {{ markAsActiveLink('central.vendors.subvendors') }}"
                                                href="{{ route('central.vendors.subvendors') }}">
                                                <i class="fa fa-list text-red"></i>
                                                {{ __tr('List') }}
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link {{ markAsActiveLink('central.subvendors.subscriptionplans') }}"
                                                href="{{ route('central.subvendors.subscriptionplans') }}">
                                                <i class="fa fa-stream text-red"></i>
                                                {{ __tr('Subscription Plans') }}
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link {{ markAsActiveLink('central.subvendors.instant_offers') }}"
                                                href="{{ route('central.subvendors.instant_offers') }}">
                                                <i class="fa fa-stream text-red"></i>
                                                {{ __tr('Instant Offers') }}
                                            </a>
                                        </li>
                                        
                                    </ul>
                                </div>
                            </li>
                        @endif
                    
                        @if (hasVendorAccess('messaging'))
                            <li class="nav-item">
                            <strong> <a class="nav-link" href="{{ route('vendor.chat_message.contact.view') }}">
                                <i class="fa fa-comments text-primary"></i>
                                {{ __tr('WhatsApp Chat') }} <span x-cloak x-show="unreadMessagesCount" class="badge badge-success rounded-pill ml-2" x-text="unreadMessagesCount"></span>
                            </a></strong>
                            </li>
                        @endif
                        @if (hasVendorAccess('manage_contacts'))
                            <li class="nav-item">
                                <a class="nav-link" href="#vendorContactSubmenuNav" data-toggle="collapse" role="button"
                                    aria-expanded="true" aria-controls="vendorContactSubmenuNav">
                                    <i class="fa fa-users text-green"></i>
                                    <span class="">{{ __tr('Contacts') }}</span>
                                </a>
                            <div class="collapse show lw-expandable-nav" id="vendorContactSubmenuNav">
                                <ul class="nav nav-sm flex-column">
                                    <li class="nav-item">
                                        <a class="nav-link {{ markAsActiveLink('vendor.contact.read.list_view') }}"
                                            href="{{ route('vendor.contact.read.list_view') }}">
                                            <i class="fa fa-list text-info"></i>
                                            {{ __tr('List') }}
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ markAsActiveLink('vendor.contact.custom_field.read.list_view') }}"
                                            href="{{ route('vendor.contact.custom_field.read.list_view') }}">
                                            <i class="fa fa-stream text-info"></i>
                                            {{ __tr('Custom Fields') }}
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ markAsActiveLink('vendor.contact.group.read.list_view') }}"
                                            href="{{ route('vendor.contact.group.read.list_view') }}">
                                            <i class="fa fa-list-alt text-info"></i>
                                            {{ __tr('Groups') }}
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    @endif
                    @if (hasVendorAccess('manage_campaigns'))
                        <li class="nav-item">
                            <a class="nav-link {{ markAsActiveLink('vendor.campaign.read.list_view') }}"
                                href="{{ route('vendor.campaign.read.list_view') }}">
                                <i class="fa fa-bullhorn text-danger"></i>
                                {{ __tr('Campaigns') }}
                            </a>
                        </li>
                    @endif
                    @if (hasVendorAccess('messaging'))
                        <li class="nav-item">
                            <a class="nav-link {{ markAsActiveLink('vendor.whatsapp_service.templates.read.list_view') }}"
                                href="{{ route('vendor.whatsapp_service.templates.read.list_view') }}">
                                <i class="fa fa-layer-group text-primary"></i>
                                {{ __tr('Templates') }}
                            </a>
                        </li>
                    @endif
                    @if (hasVendorAccess('manage_bot_replies'))
                        <li class="nav-item">
                            <a class="nav-link {{ markAsActiveLink('vendor.bot_reply.read.list_view') }}"
                                href="{{ route('vendor.bot_reply.read.list_view') }}">
                                <i class="fa fa-robot text-primary"></i>
                                {{ __tr('Bot Replies') }}
                            </a>
                        </li>
                    @endif
                    @if (hasVendorAccess('administrative'))
                        <li class="nav-item">
                            <a class="nav-link {{ markAsActiveLink('vendor.user.read.list_view') }}"
                                href="{{ route('vendor.user.read.list_view') }}">
                                <i class="fa fa-users text-dark"></i>
                                {{ __tr('System Users') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ markAsActiveLink('subscription.read.show') }}"
                                href="{{ route('subscription.read.show') }}">
                                <i class="fa fa-id-card text-blue"></i>
                                {{ __tr('My Subscription') }}
                            </a>
                        </li>
                        <li class="nav-item">
                                <a class="nav-link" href="#vendorSettingsNav" data-toggle="collapse" role="button"
                                    aria-expanded="true" aria-controls="vendorSettingsNav">
                                    <i class="fa fa-cogs text-green"></i>
                                    <span class="">{{ __tr('Settings') }}</span>
                                </a>
                            <div class="collapse show lw-expandable-nav" id="vendorSettingsNav">
                                <ul class="nav nav-sm flex-column">
                                    <li class="nav-item">
                                        <a class="nav-link <?= (isset($pageType) and $pageType == 'general') ? 'active' : '' ?>"
                                            href="<?= route('vendor.settings.read', ['pageType' => 'general']) ?>">
                                            <small class="mr-2"><i class="fa fa-cog text-default"></i></small>
                                            {{ __tr('General') }}
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <strong><a class="nav-link <?= (isset($pageType) and $pageType == 'whatsapp-cloud-api-setup') ? 'active' : '' ?>"
                                            href="<?= route('vendor.settings.read', ['pageType' => 'whatsapp-cloud-api-setup']) ?>">
                                            <span class="mr-2"><i class="fab fa-2x fa-whatsapp text-success"></i></span>
                                            {{ __tr('Cloud API Setup') }}
                                        </a></strong>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link <?= (isset($pageType) and $pageType == 'ai-chat-bot-setup') ? 'active' : '' ?>"
                                            href="<?= route('vendor.settings.read', ['pageType' => 'ai-chat-bot-setup']) ?>">
                                            <span class="mr-2"><i class="fa fa-brain text-blue"></i></span>
                                            {{ __tr('AI Chat Bot') }}
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link <?= (isset($pageType) and $pageType == 'api-access') ? 'active' : '' ?>"
                                            href="<?= route('vendor.settings.read', ['pageType' => 'api-access']) ?>">
                                            <span class="mr-2"><i class="fa fa-terminal text-yellow"></i></span>
                                            {{ __tr('API Access') }}
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    @endif
                @endif
                
                @if (hassubVendorAccess())
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('subvendor.console') }}">
                            <i class="fa fa-tachometer-alt text-danger"></i>
                            {{ __tr('Dashboard') }}
                        </a>
                    </li>
                    @if (hassubVendorAccess('instantoffers'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('subvendor.instantOffers') }}">
                                <i class="fa fa-money-check-alt text-success"></i>
                                {{ __tr('Instant Offers') }}
                            </a>
                        </li>
                    @endif
                    @if (hassubVendorAccess('bookings'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('subvendor.bookings') }}">
                                <i class="fa fa-pencil-alt text-warning"></i>
                                {{ __tr('Bookings') }}
                            </a>
                        </li>
                    @endif
                @endif

            </ul>
        </div>
    </div>
</nav>