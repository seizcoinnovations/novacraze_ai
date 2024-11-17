<?php

namespace App\Yantrana\Components\SubvendorInstantOffers;

use Throwable;
use App\Yantrana\Base\BaseEngine;
use App\Yantrana\Components\SubvendorInstantOffers\Repositories\InstantOfferRepository;
use App\Yantrana\Components\SubvendorInstantOffers\Interfaces\InstantOfferRepositoryInterface;
use Illuminate\Support\Arr;

class InstantOfferEngine extends BaseEngine implements InstantOfferRepositoryInterface
{
    protected $InstantOfferRepository;

    public function __construct(InstantOfferRepository $InstantOfferRepository)
    {
        $this->InstantOfferRepository = $InstantOfferRepository;
    }

    public function addInstantoffer($inputData)
    {
        $instant_offer = $this->InstantOfferRepository->storeInstantOffer($inputData);
        if (! $instant_offer) {
            // return $this->authRepository->transactionResponse(2, ['show_message' => true], __tr('Failed to register user'));
        }
        return $this->InstantOfferRepository->transactionResponse(1, array_merge(['show_message' => true], $instant_offer->toArray()), __tr('Instant Offer created successfully.'));
    }

    public function prepareInstantofferDataTableList()
    {
        $userCollection = $this->InstantOfferRepository->fetchInstantOfferDataTableSource();
        $isDemoMode = isDemo();
        $orderStatuses = configItem('status_codes');
        $requireColumns = [
            '_id',
            '_uid',
            'title',
            'created_at' => function ($key) {
                return formatDate($key['created_at']);
            },
            'status' => function ($key) use ($orderStatuses) {
                return Arr::get($orderStatuses, $key['status']);
            },
            
            'userId',
            // 'slug',
        ];

        return $this->dataTableResponse($userCollection, $requireColumns);
    }

    public function prepareInstantOfferDelete($instantofferIdOrUid)
    {
        $userCollection = $this->InstantOfferRepository->prepareInstantOfferDelete($instantofferIdOrUid);
    }

}