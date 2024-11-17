<?php

/**
 * VendorRepository.php - Repository file
 *
 * This file is part of the Vendor component.
 *-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Subvendor\Repositories;

use App\Yantrana\Base\BaseRepository;
use App\Yantrana\Components\Subvendor\Interfaces\SubvendorRepositoryInterface;
use App\Yantrana\Components\Subvendor\Models\SubVendor;
use App\Yantrana\Components\SubvendorCompany\Model\SubvendorCompany;
use App\Yantrana\Components\SubvendorCompanyCategories\Models\CompanyCategory;
use App\Yantrana\Components\Auth\Models\AuthModel;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use YesSecurity;    

class SubvendorRepository extends BaseRepository implements SubvendorRepositoryInterface
{
    /**
     * primary model instance
     *
     * @var object
     */
    protected $primaryModel = SubVendor::class;
    protected $secondaryModel = AuthModel::class;
    protected $companyModel = SubvendorCompany::class;
    protected $categoryModel = CompanyCategory::class;

    /**
     * Store Vendor into database
     *
     * @return object|bool
     */
    public function storeSubVendor(array $inputs = [])
    {
        // return $inputs;
        $username = $inputs['username'];
        $email = $inputs['email'];
        $subscription_plan_id = $inputs['subscription_id'];
        $plan_start_date = Carbon::now()->format('Y-m-d');
        $brand = $inputs['brand_title'];
        $brand_category = $inputs['brand_category'];
        $phone_number = $inputs['phone_number'];
        $wa_number = $inputs['wa_number'];
        $address = $inputs['address'];
        $city = $inputs['city'];
        $district = $inputs['district'];
        $state = $inputs['state'];
        $password = $inputs['password'];
        $role_id = 4;

        $vendor_array = [
            'username' => $username,
            'email' => $email,
            'subscription_plan_id' => $subscription_plan_id,
            'plan_start_date' => $plan_start_date,
            'created_at' => Carbon::now()
        ];

        $subvendor =  $this->primaryModel::create($vendor_array);
        $subvendor_id = $subvendor->_id;

        $user_array = [
            'email' => $email,
            'password' => Hash::make($password),
            'status' => 1,
            'username' => Str::lower(Str::slug($username)),
            'mobile_number' => $phone_number,
            'user_roles__id' => $role_id,
            'remember_token' => YesSecurity::generateUid(),
            // 'vendors__id' => $subvendor_id,
            'created_at' => Carbon::now()
        ];

        $user =  $this->secondaryModel::create($user_array);

        $user_id = $user->_id;
        $subvendor =  $this->primaryModel::where('id', $subvendor_id)->update(['user_id' => $user_id]);

        $company_array = [
            'subvendor_id'=> $subvendor_id,
            'name' => $brand,
            'wa_number' => $wa_number,
            'address' => $address,
            'city' => $city,
            'district' => $district,
            'state' => $state,
            // 'latitude' =>
            // 'longitude' =>
            'created_at' => Carbon::now()
        ];

        $company =  $this->companyModel::create($company_array);
        $company_id = $company->_id;

        $category_array = [
            'company_id' => $company_id,
            'category_id' => $brand_category
        ];
        
        $company_category =  $this->categoryModel::create($category_array);

        return $user;
    }

    
}
