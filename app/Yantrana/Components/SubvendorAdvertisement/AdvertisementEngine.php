<?php

namespace App\Yantrana\Components\SubvendorAdvertisement;

use Throwable;
use App\Yantrana\Base\BaseEngine;
use App\Yantrana\Components\SubvendorAdvertisement\Repositories\AdvertisementRepository;
use App\Yantrana\Components\SubvendorAdvertisement\Interfaces\AdvertisementRepositoryInterface;
use Illuminate\Support\Arr;

class AdvertisementEngine extends BaseEngine implements AdvertisementRepositoryInterface
{
    protected $AdvertisementRepository;

    public function __construct(AdvertisementRepository $AdvertisementRepository)
    {
        $this->AdvertisementRepository = $AdvertisementRepository;
    }

    public function fetchAdvertisementsCount()
    {
        return $totalAdvertisementsCount = $this->AdvertisementRepository->totalAdvertisementsCount();
    }

    public function fetchSubvendorCategories()
    {
        return $companycategories = $this->AdvertisementRepository->companycategories();
    }

    public function fetchSubvendorTemplates()
    {
        return $templates = $this->AdvertisementRepository->ad_templates();
    }
   
}