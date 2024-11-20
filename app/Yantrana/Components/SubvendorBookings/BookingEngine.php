<?php

namespace App\Yantrana\Components\SubvendorBookings;

use Throwable;
use App\Yantrana\Base\BaseEngine;
use App\Yantrana\Components\SubvendorBookings\Repositories\BookingRepository;
use App\Yantrana\Components\SubvendorBookings\Interfaces\BookingRepositoryInterface;
use Illuminate\Support\Arr;

class BookingEngine extends BaseEngine implements BookingRepositoryInterface
{
    protected $BookingRepository;

    public function __construct(BookingRepository $BookingRepository)
    {
        $this->BookingRepository = $BookingRepository;
    }

    public function addBooking($inputData)
    {
        $booking = $this->BookingRepository->storeBooking($inputData);
        if (! $booking) {
            // return $this->authRepository->transactionResponse(2, ['show_message' => true], __tr('Failed to register user'));
        }
        return $this->BookingRepository->transactionResponse(1, array_merge(['show_message' => true], $booking->toArray()), __tr('Instant Offer created successfully.'));
    }

    public function prepareBookingDataTableList()
    {
        $userCollection = $this->BookingRepository->fetchBookingDataTableSource();
        $isDemoMode = isDemo();
        // $orderStatuses = configItem('status_codes');
        $requireColumns = [
            '_id',
            '_uid',
            'product',
            'booking_date',
            'comments',
            'wa_number',
            'created_at' => function ($key) {
                return formatDate($key['created_at']);
            },
            'status_label',
            'userId',
            // 'slug',
        ];

        return $this->dataTableResponse($userCollection, $requireColumns);
    }

    public function fetchBookingsCount()
    {
        return $totalBookingsCount = $this->BookingRepository->totalBookingsCount();
    }

    public function prepareBookingDelete($bookingIdOrUid)
    {
        $userCollection = $this->BookingRepository->prepareBookingDelete($bookingIdOrUid);
    }

    public function prepareBookingUpdateData($bookingIdOrUid)
    {
         $booking = $this->BookingRepository->fetchItBooking($bookingIdOrUid);
        // Check if $vendor not exist then throw not found
        // exception
        if (__isEmpty($booking)) {
            return $this->engineReaction(18, null, __tr('Booking not found.'));
        }
        $isDemoMode = isDemo();
        if($isDemoMode) {
            $booking['product'] = maskForDemo($booking['product']);
            $booking['booking_date'] = maskForDemo($booking['booking_date']);
            $booking['comments'] = maskForDemo($booking['comments']);
            $booking['wa_number'] = maskForDemo($booking['wa_number']);
            $booking['status_label'] = maskForDemo($booking['status_label']);
        }

        return $this->engineReaction(1, $booking);
    }

    public function updateBooking($inputData)
    {
        $booking = $this->BookingRepository->updateBooking($inputData);
        if (! $booking) {
            // return $this->authRepository->transactionResponse(2, ['show_message' => true], __tr('Failed to register user'));
        }
        // return $this->BookingRepository->transactionResponse(1, array_merge(['show_message' => true], $instant_offer->toArray()), __tr('Instant Offer updated successfully.'));
    }

    public function prepareInstantOfferReject($instantofferIdOrUid)
    {
        $userCollection = $this->BookingRepository->prepareInstantOfferReject($instantofferIdOrUid);
    }

    public function prepareInstantOfferApprove($instantofferIdOrUid)
    {
        $userCollection = $this->BookingRepository->prepareInstantOfferApprove($instantofferIdOrUid);
    }

}