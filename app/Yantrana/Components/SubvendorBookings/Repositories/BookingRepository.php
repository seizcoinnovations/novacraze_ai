<?php

/**
 * SubscriptionRepository.php - Repository file
 *
 * This file is part of the Subscription component.
 *-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\SubvendorBookings\Repositories;

use App\Yantrana\Base\BaseRepository;
use App\Yantrana\Components\SubvendorBookings\Models\Booking;
use App\Yantrana\Components\SubvendorBookings\Interfaces\BookingRepositoryInterface;
use Illuminate\Support\Facades\DB;

class BookingRepository extends BaseRepository implements BookingRepositoryInterface
{
    /**
     * primary model instance
     *
     * @var object
     */
    protected $primaryModel = Booking::class;
   
     public function storeBooking(array $inputs = [])
     {

        $booking_array = [
            'subvendor_id' => getsubVendorId(),
            'product' => $inputs['product'],
            'booking_date' => $inputs['booking_date'],
            'comments' => $inputs['comments'],
            'wa_number' => $inputs['wa_number'],
            // 'image' => $inputs['image'],
            'status' => 0
        ];

        $booking_array =  $this->primaryModel::create($booking_array);

        if($booking_array)
        {
            return $booking_array;
        }
     }

     public function totalBookingsCount()
     {
        return $this->primaryModel::count();
     }

     public function fetchBookingDataTableSource()
     {
        $dataTableConfig = [
            'searchable' => [
                'product',
                'wa_number',
                'status',
                'booking_date'
            ],
        ];

        return $this->primaryModel::leftJoin('sub_vendors', 'sub_vendors.id', '=', 'subvendor_bookings.subvendor_id')
            ->select(
                __nestedKeyValues([
                    'subvendor_bookings' => [
                        '_id',
                        '_uid',
                        'product',
                        'booking_date',
                        'comments',
                        'wa_number',
                        'created_at',
                        'status',
                        DB::raw("CASE 
                        WHEN status = 0 THEN 'Inactive'
                        WHEN status = 1 THEN 'Approved'
                        WHEN status = 2 THEN 'Rejected'
                        ELSE 'Unknown' END as status_label")
                    ],
                    'sub_vendors' => [
                        'id as userId',
                        'username as username',
                        'email',
                        // 'status as user_status',
                        // DB::raw("CONCAT(COALESCE(first_name, ''), ' ', COALESCE(last_name, '')) AS fullName"),
                    ],
                ])
            )
            ->dataTables($dataTableConfig)
            ->toArray();
            
     }
     
     public function prepareBookingDelete($bookingIdOrUid)
     {
        $booking =  $this->primaryModel::where('_id', $bookingIdOrUid)->delete();

        if($booking)
        {
            return $booking;
        }
     }

     public function fetchItBooking($bookingIdOrUid)
     {
        // return $this->primaryModel::where('_uid', $bookingIdOrUid)->first()->toArray();
        return $this->primaryModel::select(
            '*',
            DB::raw("CASE 
                WHEN status = 0 THEN 'Inactive'
                WHEN status = 1 THEN 'Approved'
                WHEN status = 2 THEN 'Rejected'
                ELSE 'Unknown' 
            END as status_label")
        )
        ->where('_uid', $bookingIdOrUid)
        ->first()
        ->toArray();
        
     }

     public function updateBooking(array $inputs = [])
     {
        $uid = $inputs['bookingIdOrUid'];
        $booking_array = [
            'product' => $inputs['product'],
            'booking_date' => $inputs['booking_date'],
            'comments' => $inputs['comments'],
            'wa_number' => $inputs['wa_number'],
        ];

        $booking =  $this->primaryModel::where('_uid', $uid)->update($booking_array);

        if($booking)
        {
            return $booking;
        }
     }

     public function prepareInstantOfferReject($instantofferIdOrUid)
     {
        $instant_offer =  $this->primaryModel::where('_id', $instantofferIdOrUid)->update(['status' => 2]);

        if($instant_offer)
        {
            return $instant_offer;
        }
     }

     public function prepareInstantOfferApprove($instantofferIdOrUid)
     {
        $instant_offer =  $this->primaryModel::where('_id', $instantofferIdOrUid)->update(['status' => 1]);

        if($instant_offer)
        {
            return $instant_offer;
        }
     }
}
