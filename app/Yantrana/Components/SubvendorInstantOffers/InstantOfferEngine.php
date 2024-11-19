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
        // $orderStatuses = configItem('status_codes');
        $requireColumns = [
            '_id',
            '_uid',
            'title',
            'created_at' => function ($key) {
                return formatDate($key['created_at']);
            },
            'status_label',
            'userId',
            // 'slug',
        ];

        return $this->dataTableResponse($userCollection, $requireColumns);
    }

    public function fetchInstantOffersCount()
    {
        return $totalOffersCount = $this->InstantOfferRepository->totalOffersCount();
    }

    public function prepareInstantOfferDelete($instantofferIdOrUid)
    {
        $userCollection = $this->InstantOfferRepository->prepareInstantOfferDelete($instantofferIdOrUid);
    }

    public function prepareInstantofferUpdateData($instantofferIdOrUid)
    {
         $instant_offer = $this->InstantOfferRepository->fetchItInstantOffer($instantofferIdOrUid);
        // Check if $vendor not exist then throw not found
        // exception
        if (__isEmpty($instant_offer)) {
            return $this->engineReaction(18, null, __tr('Instant Offer not found.'));
        }
        $isDemoMode = isDemo();
        if($isDemoMode) {
            $instant_offer['name'] = maskForDemo($instant_offer['name']);
            $instant_offer['description'] = maskForDemo($instant_offer['description']);
            $instant_offer['from_date'] = maskForDemo($instant_offer['from_date']);
            $instant_offer['to_date'] = maskForDemo($instant_offer['to_date']);
        }

        return $this->engineReaction(1, $instant_offer);
    }

    public function updateInstantoffer($inputData)
    {
        $instant_offer = $this->InstantOfferRepository->updateInstantOffer($inputData);
        if (! $instant_offer) {
            // return $this->authRepository->transactionResponse(2, ['show_message' => true], __tr('Failed to register user'));
        }
        // return $this->InstantOfferRepository->transactionResponse(1, array_merge(['show_message' => true], $instant_offer->toArray()), __tr('Instant Offer updated successfully.'));
    }

    public function prepareInstantOfferReject($instantofferIdOrUid)
    {
        $userCollection = $this->InstantOfferRepository->prepareInstantOfferReject($instantofferIdOrUid);
    }

    public function prepareInstantOfferApprove($instantofferIdOrUid)
    {
        $userCollection = $this->InstantOfferRepository->prepareInstantOfferApprove($instantofferIdOrUid);
    }

}