<?php

/**
 * SubscriptionRepository.php - Repository file
 *
 * This file is part of the Subscription component.
 *-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\SubvendorAdvertisement\Repositories;

use App\Yantrana\Base\BaseRepository;
// use App\Yantrana\Components\Subvendor\Model\Category;
use App\Yantrana\Components\SubvendorAdvertisement\Models\Advertisement;
use App\Yantrana\Components\SubvendorAdvertisement\Interfaces\AdvertisementRepositoryInterface;
use App\Yantrana\Components\SubvendorCategories\Models\Category;
use App\Yantrana\Components\SubvendorTemplates\Models\Template;
use Illuminate\Support\Facades\DB;

class AdvertisementRepository extends BaseRepository implements AdvertisementRepositoryInterface
{
    /**
     * primary model instance
     *
     * @var object
     */
    protected $primaryModel = Advertisement::class;
    protected $secondaryModel = Category::class;
    protected $templateModel = Template::class;
   
    public function totalAdvertisementsCount()
    {
        return $this->primaryModel::count();
    }

    public function companycategories()
    {
        return $this->secondaryModel::get();
    }

    public function ad_templates()
    {
        return $this->templateModel::get();
    }
     
}
