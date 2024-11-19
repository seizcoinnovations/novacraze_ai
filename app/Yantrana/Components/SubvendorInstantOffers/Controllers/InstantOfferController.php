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
        $totalOffersCount = $this->InstantOfferEngine->fetchInstantOffersCount();
        return $this->loadView('subvendors.instant-offers.instant_offers_list',[
            'totalOffersCount' => $totalOffersCount,
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

    public function listInstantOffer_vendor()
    {
        return $this->loadView('vendor.instant_offers.instant_offers_list');
    }

    public function prepareUpdateInstantOfferData($instantofferIdOrUid)
    {
        // ask engine to process the request
        $processReaction = $this->InstantOfferEngine->prepareInstantofferUpdateData($instantofferIdOrUid);

        // get back to controller with engine response
        return $this->processResponse($processReaction, [], [], true);
    }

    public function updateInstantOfferData(CommonRequest $instantoffersData)
    {
        $instantoffersData->validate([
            'instant_offer_title' => 'required|string|min:2|max:100',
            'from_date' => 'required',
            'to_date' => 'required',
            // 'image' => 'required',
        ]);
        $processReaction = $this->InstantOfferEngine->updateInstantoffer($instantoffersData->all());
        return $this->processResponse([], [], [], true);
    }

    public function rejectInstantoffer($instantofferIdOrUid)
    {
        return $processReaction = $this->InstantOfferEngine->prepareInstantOfferReject($instantofferIdOrUid);
    }

    public function approveInstantoffer($instantofferIdOrUid)
    {
        return $processReaction = $this->InstantOfferEngine->prepareInstantOfferApprove($instantofferIdOrUid);
    }

  
}
