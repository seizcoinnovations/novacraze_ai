<?php

/**
 * SubscriptionRepository.php - Repository file
 *
 * This file is part of the Subscription component.
 *-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\SubvendorSubscription\Repositories;

use App\Yantrana\Base\BaseRepository;
use App\Yantrana\Components\SubvendorCategories\Models\Category;
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
    protected $categoryModel = Category::class;

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
            $subscriptions[$plan_name]['id'] = $subscription_plan->_id;
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

    public function companycategories()
    {
        return $company_categories = $this->categoryModel::get();
    }

    public function update_subscriptionplans($request)
    {
        $id = $request->config_plan_id;
        $name = $request->title;
        $status = isset($request->enabled) ? 1 : 0;
        $category_listing_count = $request->category_listing_limit;
        $shop_count = $request->shop_limit;
        $lead_count = $request->lead_limit;
        $views_count = $request->views_limit;
        $click_count = $request->click_limit;
        $booking_management_count = $request->booking_limit;
        $instant_offer_count = $request->instant_offer_limit;
        $advertisement_count = $request->advertisement_limit;
        $custom_bot = $request->custom_bot_limit;
        $plan_months_count = $request->Price_charge;
        $plan_price = $request->Months_charge;

        $update_array = [
            'name' => $name,
            'status' => $status,
            'category_listing_count' => $category_listing_count,
            'shop_count' => $shop_count,
            'lead_count' => $lead_count,
            'views_count' => $views_count,
            'click_count' => $click_count,
            'booking_management_count' => $booking_management_count,
            'instant_offer_count' => $instant_offer_count,
            'advertisement_count' => $advertisement_count,
            'plan_months_count' => $plan_months_count,
            'plan_price' => $plan_price,
        ];

        $update_subscription_plans =  $this->primaryModel::where('id', $id)->update($update_array);

        if( $update_subscription_plans)
        {
            return true;
        }
    }
  
}
