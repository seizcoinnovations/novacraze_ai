<?php

namespace App\Yantrana\Components\SubvendorAdvertisement\Controllers;

use Illuminate\Validation\Rule;
use App\Yantrana\Base\BaseController;
use App\Yantrana\Base\BaseRequest;
use App\Yantrana\Components\SubvendorAdvertisement\Advertisement;
use App\Yantrana\Components\SubvendorAdvertisement\AdvertisementEngine;
use App\Yantrana\Components\SubvendorInstantOffers\InstantOfferEngine;
use App\Yantrana\Support\CommonRequest;
use App\Yantrana\Support\CommonPostRequest;


class AdvertisementController extends BaseController
{
    protected $AdvertisementEngine;
    protected $InstantOfferEngine;

    public function __construct(AdvertisementEngine $AdvertisementEngine, InstantOfferEngine $InstantOfferEngine)
    {
        $this->AdvertisementEngine = $AdvertisementEngine;
        $this->InstantOfferEngine = $InstantOfferEngine;
    }
    
    public function advertisementList()
    {
        $totalAdvertisementCount = $this->AdvertisementEngine->fetchAdvertisementsCount();
        $categories = $this->AdvertisementEngine->fetchSubvendorCategories();
        $templates = $this->AdvertisementEngine->fetchSubvendorTemplates();
        return $this->loadView('subvendors.advertisements.advertisement-list',[
            'totalAdvertisementCount' => $totalAdvertisementCount,
            'categories' => $categories,
            'templates' => $templates
        ]);
    }
    
    public function addAdvertisement(CommonRequest $advertisementData)
    {
        $advertisementData->validate([
            'advertisement_name' => 'required|string|min:2|max:100',
            'final_content' => 'required',
            'category_id' => 'required',
            'comments' => 'template_id',
        ]);
        $processReaction = $this->AdvertisementEngine->addAdvertisement($advertisementData->all());
        return $this->processResponse([], [], [], true);
    }

    public function listAdvertisements()
    {
        return $this->AdvertisementEngine->prepareAdvertisementDataTableList();
    }

    public function prepareUpdateAdvertisementData($advertisementIdOrUid)
    {
        // ask engine to process the request
        $processReaction = $this->AdvertisementEngine->prepareAdvertisementUpdateData($advertisementIdOrUid);

        // get back to controller with engine response
        return $this->processResponse($processReaction, [], [], true);
    }

    public function deleteAdvertisement($advertisementIdOrUid)
    {
        // ask engine to process the request
        return $processReaction = $this->AdvertisementEngine->prepareAdvertisementDelete($advertisementIdOrUid);

        // get back to controller with engine response
        // return $this->processResponse($processReaction, [], [], true);
    }
    
}
