<?php

/**
 * VendorRepository.php - Repository file
 *
 * This file is part of the Vendor component.
 *-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Vendor\Repositories;

use App\Yantrana\Base\BaseRepository;
use App\Yantrana\Components\Subvendor\Interfaces\SubvendorRepositoryInterface;
use App\Yantrana\Components\Subvendor\Models\SubVendor;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class VendorRepository extends BaseRepository implements SubvendorRepositoryInterface
{
    /**
     * primary model instance
     *
     * @var object
     */
    protected $primaryModel = SubVendor::class;

    /**
     * Store Vendor into database
     *
     * @return object|bool
     */
    public function storeSubvendor(array $inputs = [])
    {
        return $this->storeIt($inputs);
    }

    /**
     * Fetch List of users
     *
     * @param    int || int $status
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchSubvendorsDataTableSource()
    {
        $dataTableConfig = [
            'searchable' => [
                'title',
                'fullName' => DB::raw("CONCAT(COALESCE(first_name, ''), ' ', COALESCE(last_name, ''))"),
                'email',
                'username',
                'slug',
            ],
        ];

        return $this->primaryModel::leftJoin('users', 'users.vendors__id', '=', 'vendors._id')
            ->select(
                __nestedKeyValues([
                    'vendors' => [
                        '_id',
                        '_uid',
                        'title',
                        'created_at',
                        'status',
                        'slug',
                    ],
                    'users' => [
                        '_id as userId',
                        'username as username',
                        'email',
                        'status as user_status',
                        DB::raw("CONCAT(COALESCE(first_name, ''), ' ', COALESCE(last_name, '')) AS fullName"),
                    ],
                ])
            )
            ->dataTables($dataTableConfig)
            ->toArray();
    }

 }
