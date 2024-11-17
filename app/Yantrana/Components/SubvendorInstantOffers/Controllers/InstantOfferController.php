<?php

namespace App\Yantrana\Components\SubvendorInstantOffers\Controllers;

use Illuminate\Validation\Rule;
use App\Yantrana\Base\BaseController;
use App\Yantrana\Base\BaseRequest;
use App\Yantrana\Components\SubvendorInstantOffers\InstantOffer;
use App\Yantrana\Components\SubvendorInstantOffers\InstantOfferEngine;
use App\Yantrana\Support\CommonRequest;
use App\Yantrana\Support\CommonPostRequest;


class InstantOfferController extends BaseController
{
    protected $InstantOfferEngine;

    public function __construct(InstantOfferEngine $InstantOfferEngine)
    {
        $this->InstantOfferEngine = $InstantOfferEngine;
    }
    
    public function instantoffersList()
    {
        // $subcription_plans =  $this->subvendorsubscriptionengine->fetchallsubscriptionplans();
        // return $subcription_plans =  $this->subvendorsubscriptionengine->allsubscriptionplans();
        return $this->loadView('subvendors.instant-offers.instant_offers_list',[
            // 'planStructure' => $subcription_plans,
        ]);
    }

    public function addInstantOffers(CommonRequest $instantoffersData)
    {
        $instantoffersData->validate([
            'instant_offer_title' => 'required|string|min:2|max:100',
            'from_date' => 'required',
            'to_date' => 'required',
            // 'image' => 'required',
        ]);
        $processReaction = $this->InstantOfferEngine->addInstantoffer($instantoffersData->all());
        return $this->processResponse([], [], [], true);
    }

    public function listInstantOffers()
    {
        return $this->InstantOfferEngine->prepareInstantofferDataTableList();
    }

    public function deleteInstantOffers($instantofferIdOrUid)
    {
        
        // ask engine to process the request
        return $processReaction = $this->InstantOfferEngine->prepareInstantOfferDelete($instantofferIdOrUid);

        // get back to controller with engine response
        // return $this->processResponse($processReaction, [], [], true);
    }

  
}
