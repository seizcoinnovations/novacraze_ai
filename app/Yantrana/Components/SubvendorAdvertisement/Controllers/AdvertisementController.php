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
    
}
