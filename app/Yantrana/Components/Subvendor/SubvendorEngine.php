<?php

/**
 * VendorEngine.php - Main component file
 *
 * This file is part of the Vendor component.
 *-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Vendor;

use Illuminate\Support\Arr;
use App\Yantrana\Base\BaseEngine;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Yantrana\Components\Auth\Repositories\AuthRepository;
use App\Yantrana\Components\User\Repositories\UserRepository;
use App\Yantrana\Components\Vendor\Repositories\VendorRepository;
use App\Yantrana\Components\Vendor\Interfaces\VendorEngineInterface;

class VendorEngine extends BaseEngine implements VendorEngineInterface
{
    /**
     * @var VendorRepository - Vendor Repository
     */
    protected $vendorRepository;

    /**
     * @var AuthRepository - Auth Repository
     */
    protected $authRepository;

    /**
     * @var UserRepository - User Repository
     */
    protected $userRepository;

    /**
     * Constructor
     *
     * @param  VendorRepository  $vendorRepository  - Vendor Repository
     * @return void
     *-----------------------------------------------------------------------*/
    public function __construct(AuthRepository $authRepository, VendorRepository $vendorRepository, UserRepository $userRepository)
    {
        $this->authRepository = $authRepository;
        $this->vendorRepository = $vendorRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * Prepare User Data table list.
     *
     * @param  int  $status
     *
     *---------------------------------------------------------------- */
    public function prepareVendorDataTableList()
    {
        $userCollection = $this->vendorRepository->fetchVendorsDataTableSource();
        $isDemoMode = isDemo();
        $orderStatuses = configItem('status_codes');
        $requireColumns = [
            '_id',
            '_uid',
            'title',
            'created_at' => function ($key) {
                return formatDate($key['created_at']);
            },
            'fullName' => function ($key) use (&$isDemoMode) {
                return $isDemoMode ? maskForDemo($key['fullName'], 'fullName') : $key['fullName'];
            },
            'status' => function ($key) use ($orderStatuses) {
                return Arr::get($orderStatuses, $key['status']);
            },
            'user_status' => function ($key) use ($orderStatuses) {
                return Arr::get($orderStatuses, $key['user_status']);
            },
            'email' => function ($key) use (&$isDemoMode) {
                return $isDemoMode ? maskForDemo($key['email'], 'email') : $key['email'];
            },
            'username' => function ($key) use (&$isDemoMode) {
                return $isDemoMode ? maskForDemo($key['username'], 'username') : $key['username'];
            },
            'userId',
            'slug',
        ];

        return $this->dataTableResponse($userCollection, $requireColumns);
    }

    
}
