<?php

namespace App\Yantrana\Components\Subscription\Support;

use ArrayObject;

/**
 * Subscription Plan details Response class
 */
class SubscriptionPlanDetails extends ArrayObject
{
    // public $has_active_plan;

    public function __construct($array = [])
    {
        parent::__construct($array, ArrayObject::ARRAY_AS_PROPS);
    }

    /**
     * Check if the vendor has active plan or not
     *
     * @return bool
     */
    public function hasActivePlan()
    {
        return $this->has_active_plan;
    }

    public function planType()
    {
        return $this->plan_type;
    }
    public function currentUsage()
    {
        return $this->current_usage;
    }
    public function isLimitAvailable()
    {
        return $this->is_limit_available;
    }
    public function planTitle()
    {
        return $this->plan_title;
    }
    public function isAuto()
    {
        return $this->subscription_type == 'auto';
    }
}
