<?php

namespace App\Yantrana\Components\SubvendorSubscription\Controllers;

use Illuminate\Validation\Rule;
use App\Yantrana\Base\BaseController;
use App\Yantrana\Components\SubvendorSubscription\SubvendorSubscriptionEngine;
use App\Yantrana\Support\CommonRequest;
use App\Yantrana\Support\CommonPostRequest;


class SubvendorSubscriptionController extends BaseController
{
    protected $subvendorsubscriptionengine;

    public function __construct(SubvendorSubscriptionEngine $subvendorsubscriptionengine)
    {
        $this->subvendorsubscriptionengine = $subvendorsubscriptionengine;
    }

    
    public function subscriptionplansIndex()
    {
        $subcription_plans =  $this->subvendorsubscriptionengine->fetchallsubscriptionplans();
        // return $subcription_plans =  $this->subvendorsubscriptionengine->allsubscriptionplans();
        return $this->loadView('subvendors.subscription-plans.subscription-plan-index',[
            'planStructure' => $subcription_plans,
        ]);
    }


    //
    public function subscriptionPlans()
    {
        $subcription_plans =  $this->subvendorsubscriptionengine->fetchallsubscriptionplans();
        return $this->loadView('subvendors.subscription-plans.subscription-plans', [
            'planDetails' => getPaidPlans(),
            'freePlan' => getFreePlan(),
            'planStructure' => $subcription_plans,
            'freePlanStructure' => getConfigFreePlan(),
        ]);
    }

    public function subscriptionPlansUpdate($subscriptionPlanIdOrUid)
    {
        validateVendorAccess('administrative');
        return $subscriptionPlanIdOrUid;
        // ask engine to process the request
        $processReaction = $this->subvendorsubscriptionengine->preparesubscriptionUpdateData($subscriptionPlanIdOrUid);

        // get back to controller with engine response
        return $this->processResponse($processReaction, [], [], true);
    }
}
