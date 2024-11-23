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

    public function addAdvertisement($inputData)
    {
        $advertisement = $this->AdvertisementRepository->storeAdvertisement($inputData);
        if (! $advertisement) {
            // return $this->authRepository->transactionResponse(2, ['show_message' => true], __tr('Failed to register user'));
        }
        return $this->AdvertisementRepository->transactionResponse(1, array_merge(['show_message' => true], $advertisement->toArray()), __tr('Instant Offer created successfully.'));
    }

    public function prepareAdvertisementDataTableList()
    {
        $userCollection = $this->AdvertisementRepository->fetchAdvertisementDataTableSource();
        $isDemoMode = isDemo();
        // $orderStatuses = configItem('status_codes');
        $requireColumns = [
            '_id',
            '_uid',
            'advertisement_name',
            'content_template',
            'content_filled',
            // 'image',
            'created_at' => function ($key) {
                return formatDate($key['created_at']);
            },
            // 'status_label',
            // 'userId',
            // 'slug',
            'template_id',
            'category_id',
            'template_name',
            'category_name'
        ];

        return $this->dataTableResponse($userCollection, $requireColumns);
    }

    public function prepareAdvertisementUpdateData($advertisementIdOrUid)
    {
        $davertisement = $this->AdvertisementRepository->fetchItAdvertisement($advertisementIdOrUid);
        // Check if $vendor not exist then throw not found
        // exception
        if (__isEmpty($davertisement)) {
            return $this->engineReaction(18, null, __tr('Booking not found.'));
        }
        $isDemoMode = isDemo();
        if($isDemoMode) {
            $davertisement['advertisement_name'] = maskForDemo($davertisement['advertisement_name']);
            $davertisement['template_name'] = maskForDemo($davertisement['template_name']);
            $davertisement['category_name'] = maskForDemo($davertisement['category_name']);
            $davertisement['content_filled'] = maskForDemo($davertisement['content_filled']);
            // $davertisement['image'] = maskForDemo($davertisement['iamge']);
        }

        return $this->engineReaction(1, $davertisement);
    }

    public function prepareAdvertisementDelete($advertisementIdOrUid)
    {
        $userCollection = $this->AdvertisementRepository->prepareAdvertisementDelete($advertisementIdOrUid);
    }
   
}