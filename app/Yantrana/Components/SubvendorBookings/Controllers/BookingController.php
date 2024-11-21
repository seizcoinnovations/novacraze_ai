<?php

namespace App\Yantrana\Components\SubvendorBookings\Controllers;

use Illuminate\Validation\Rule;
use App\Yantrana\Base\BaseController;
use App\Yantrana\Base\BaseRequest;
use App\Yantrana\Components\SubvendorBookings\InstantOffer;
use App\Yantrana\Components\SubvendorBookings\BookingEngine;
use App\Yantrana\Support\CommonRequest;
use App\Yantrana\Support\CommonPostRequest;


class BookingController extends BaseController
{
    protected $BookingEngine;

    public function __construct(BookingEngine $BookingEngine)
    {
        $this->BookingEngine = $BookingEngine;
    }
    
    public function bookingList()
    {
        $totalBookingCount = $this->BookingEngine->fetchBookingsCount();
        return $this->loadView('subvendors.bookings.booking-list',[
            'totalBookingCount' => $totalBookingCount,
        ]);
    }

    public function addBookings(CommonRequest $bookingsData)
    {
        $bookingsData->validate([
            'product' => 'required|string|min:2|max:100',
            'booking_date' => 'required',
            'wa_number' => 'required',
            'comments' => 'required',
        ]);
        $processReaction = $this->BookingEngine->addBooking($bookingsData->all());
        return $this->processResponse([], [], [], true);
    }

    public function listBookings()
    {
        return $this->BookingEngine->prepareBookingDataTableList();
    }

    public function deleteBooking($bookingIdOrUid)
    {
        
        // ask engine to process the request
        return $processReaction = $this->BookingEngine->prepareBookingDelete($bookingIdOrUid);

        // get back to controller with engine response
        // return $this->processResponse($processReaction, [], [], true);
    }

    public function listBookings_vendor()
    {
        return $this->loadView('vendor.bookings.bookings_list');
    }

    public function prepareUpdateBookingData($bookingIdOrUid)
    {
        // ask engine to process the request
        $processReaction = $this->BookingEngine->prepareBookingUpdateData($bookingIdOrUid);

        // get back to controller with engine response
        return $this->processResponse($processReaction, [], [], true);
    }

    public function updateBookingData(CommonRequest $bookingData)
    {
        $bookingData->validate([
            'product' => 'required|string|min:2|max:100',
            'booking_date' => 'required',
            'wa_number' => 'required',
            'comments' => 'required',
        ]);
        $processReaction = $this->BookingEngine->updateBooking($bookingData->all());
        return $this->processResponse([], [], [], true);
    }

    public function rejectBooking($bookingIdOrUid)
    {
        return $processReaction = $this->BookingEngine->prepareBookingReject($bookingIdOrUid);
    }

    public function approveBooking($bookingIdOrUid)
    {
        return $processReaction = $this->BookingEngine->prepareBookingApprove($bookingIdOrUid);
    }

  
}
