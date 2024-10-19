<?php

/**
 * SubscriptionRepository.php - Repository file
 *
 * This file is part of the Subscription component.
 *-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\SubvendorSubscription\Repositories;

use App\Yantrana\Base\BaseRepository;
use App\Yantrana\Components\SubvendorSubscription\Interfaces\SubvendorSubscriptionRepositoryInterface;
use App\Yantrana\Components\SubvendorSubscription\Models\SubvendorSubscription;
use Illuminate\Support\Facades\DB;

class SubvendorSubscriptionRepository extends BaseRepository implements SubvendorSubscriptionRepositoryInterface
{
    /**
     * primary model instance
     *
     * @var object
     */
    protected $primaryModel = SubvendorSubscription::class;

    public function subscriptionplans()
    {
        return $subscription_plans =  $this->primaryModel::latest()->get();;
    }

    public function fetchsubscriptionplans()
    {
        $subscription_plans =  $this->primaryModel::latest()->get();
        $subscriptions = array();
        foreach ($subscription_plans as $key => $subscription_plan) {
            $plan_number = $key + 1;
            $plan_name = 'plan_'.$plan_number;
            $subscriptions[$plan_name]['id'] = $subscription_plan->id;
            $subscriptions[$plan_name]['plan_id'] = $plan_name;
            $subscriptions[$plan_name]['enabled'] = ($subscription_plan->status == 1) ? true : false;
            $subscriptions[$plan_name]['title'] = $subscription_plan->name;
            $subscriptions[$plan_name]['features']['category_listing']['description'] = 'Category Listing';
            $subscriptions[$plan_name]['features']['category_listing']['limit'] = $subscription_plan->category_listing_count;
            $subscriptions[$plan_name]['features']['shop']['description'] = 'Shop Management';
            $subscriptions[$plan_name]['features']['shop']['limit'] = $subscription_plan->shop_count;
            $subscriptions[$plan_name]['features']['lead']['description'] = 'Lead Generation';
            $subscriptions[$plan_name]['features']['lead']['limit'] = $subscription_plan->lead_count;
            $subscriptions[$plan_name]['features']['views']['description'] = 'Views';
            $subscriptions[$plan_name]['features']['views']['limit'] = $subscription_plan->views_count;
            $subscriptions[$plan_name]['features']['click']['description'] = 'Clicks';
            $subscriptions[$plan_name]['features']['click']['limit'] = $subscription_plan->click_count;
            $subscriptions[$plan_name]['features']['booking']['description'] ='Booking Management';
            $subscriptions[$plan_name]['features']['booking']['limit'] = $subscription_plan->booking_management_count;
            $subscriptions[$plan_name]['features']['instant_offer']['description'] = 'Instant Offers';
            $subscriptions[$plan_name]['features']['instant_offer']['limit'] = $subscription_plan->instant_offer_count;
            $subscriptions[$plan_name]['features']['advertisement']['description'] = 'Advertisement';
            $subscriptions[$plan_name]['features']['advertisement']['limit'] = $subscription_plan->advertisement_count;
            $subscriptions[$plan_name]['features']['custom_bot']['description'] = 'Custom Bot';
            $subscriptions[$plan_name]['features']['custom_bot']['type'] = 'Switch';
            $subscriptions[$plan_name]['features']['custom_bot']['limit'] = $subscription_plan->custom_bot;
            $subscriptions[$plan_name]['charges']['months']['description'] = 'Months';
            $subscriptions[$plan_name]['charges']['months']['value'] = $subscription_plan->plan_months_count;
            $subscriptions[$plan_name]['charges']['price']['description'] = 'Price'; 
            $subscriptions[$plan_name]['charges']['price']['value'] = $subscription_plan->plan_price; 
        }
        return $subscriptions;
    }
  
}
