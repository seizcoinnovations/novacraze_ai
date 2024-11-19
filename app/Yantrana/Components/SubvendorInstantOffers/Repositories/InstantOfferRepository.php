<?php

/**
 * SubscriptionRepository.php - Repository file
 *
 * This file is part of the Subscription component.
 *-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\SubvendorInstantOffers\Repositories;

use App\Yantrana\Base\BaseRepository;
use App\Yantrana\Components\SubvendorInstantOffers\Models\InstantOffer;
use App\Yantrana\Components\SubvendorInstantOffers\Interfaces\InstantOfferRepositoryInterface;
use Illuminate\Support\Facades\DB;

class InstantOfferRepository extends BaseRepository implements InstantOfferRepositoryInterface
{
    /**
     * primary model instance
     *
     * @var object
     */
    protected $primaryModel = InstantOffer::class;
   
     public function storeInstantOffer(array $inputs = [])
     {

        $instantoffer_array = [
            'subvendor_id' => getsubVendorId(),
            'name' => $inputs['instant_offer_title'],
            'description' => $inputs['description'],
            'from_date' => $inputs['from_date'],
            'to_date' => $inputs['to_date'],
            // 'image' => $inputs['image'],
            'status' => 0
        ];

        $instant_offer =  $this->primaryModel::create($instantoffer_array);

        if($instant_offer)
        {
            return $instant_offer;
        }
     }

     public function totalOffersCount()
     {
        return $this->primaryModel::count();
     }

     public function fetchInstantOfferDataTableSource()
     {
        $dataTableConfig = [
            'searchable' => [
                'name',
                'status'
            ],
        ];

        return $this->primaryModel::leftJoin('sub_vendors', 'sub_vendors.id', '=', 'instant_offers.subvendor_id')
            ->select(
                __nestedKeyValues([
                    'instant_offers' => [
                        '_id',
                        '_uid',
                        'name as title',
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
     
     public function prepareInstantOfferDelete($instantofferIdOrUid)
     {
        $instant_offer =  $this->primaryModel::where('_id', $instantofferIdOrUid)->delete();

        if($instant_offer)
        {
            return $instant_offer;
        }
     }

     public function fetchItInstantOffer($instantofferIdOrUid)
     {
        return $this->primaryModel::where('_uid', $instantofferIdOrUid)->first()->toArray();
        
     }

     public function updateInstantOffer(array $inputs = [])
     {
        $uid = $inputs['instantofferIdOrUid'];
        $instantoffer_array = [
            'name' => $inputs['instant_offer_title'],
            'description' => $inputs['description'],
            'from_date' => $inputs['from_date'],
            'to_date' => $inputs['to_date'],
            // 'image' => $inputs['image'],
        ];

        $instant_offer =  $this->primaryModel::where('_uid', $uid)->update($instantoffer_array);

        if($instant_offer)
        {
            return $instant_offer;
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
