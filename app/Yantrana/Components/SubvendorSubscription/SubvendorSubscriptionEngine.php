<?php

namespace App\Yantrana\Components\SubvendorSubscription;

use Throwable;
use App\Yantrana\Base\BaseEngine;
use App\Yantrana\Components\SubvendorSubscription\Repositories\SubvendorSubscriptionRepository;
use App\Yantrana\Components\SubvendorSubscription\Interfaces\SubvendorSubscriptionRepositoryInterface;


class SubvendorSubscriptionEngine extends BaseEngine implements SubvendorSubscriptionRepositoryInterface
{
    protected $SubvendorSubscriptionRepository;

    public function __construct(SubvendorSubscriptionRepository $SubvendorSubscriptionRepository)
    {
        $this->SubvendorSubscriptionRepository = $SubvendorSubscriptionRepository;
    }

    public function fetchallsubscriptionplans()
    {
        return $subscription_plans = $this->SubvendorSubscriptionRepository->fetchsubscriptionplans();
    }

    public function allsubscriptionplans()
    {
        return $subscription_plans = $this->SubvendorSubscriptionRepository->subscriptionplans();
    }

    public function preparesubscriptionUpdateData()
    {
        
    }
}