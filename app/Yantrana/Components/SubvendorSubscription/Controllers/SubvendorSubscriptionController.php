<?php

namespace App\Yantrana\Components\SubvendorSubscription\Controllers;

use Illuminate\Validation\Rule;
use App\Yantrana\Base\BaseController;
use App\Yantrana\Base\BaseRequest;
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

    public function subvendorRegistration($sub_id)
    {
        $company_categories =  $this->subvendorsubscriptionengine->fetchallcompanycategories();
        return $this->loadView('subvendors.auth.registration',[
            'subscriptionId' => $sub_id,
            'companycategories' => $company_categories
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

    public function subscriptionPlansUpdate(BaseRequest $request)
    {
        validateVendorAccess('administrative');
        // ask engine to process the request
        return $processReaction = $this->subvendorsubscriptionengine->preparesubscriptionUpdateData($request);

        // get back to controller with engine response
        return $this->processResponse($processReaction, [], [], true);
    }
}
