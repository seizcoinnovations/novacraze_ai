<?php

namespace App\Yantrana\Components\Subvendor\Controllers;

use Illuminate\Validation\Rule;
use App\Yantrana\Base\BaseController;
use App\Yantrana\Support\CommonRequest;
use App\Yantrana\Support\CommonPostRequest;
use App\Yantrana\Components\Auth\AuthEngine;
use App\Yantrana\Components\Subvendor\SubvendorEngine;
use App\Yantrana\Components\Auth\Models\AuthModel;
use App\Yantrana\Components\Dashboard\DashboardEngine;

class SubVendorController extends BaseController
{
    /**
     * @var VendorEngine - Vendor Engine
     */
    protected $subvendorEngine;

    /**
     * @var AuthEngine - Auth Engine
     */
    protected $authEngine;

    /**
     * @var DashboardEngine - Dashboard Engine
     */
    protected $dashboardEngine;

    /**
     * Constructor
     *
     * @param  VendorEngine  $vendorEngine  - Vendor Engine
     * @return void
     *-----------------------------------------------------------------------*/
    public function __construct(SubvendorEngine $subvendorEngine, AuthEngine $authEngine, DashboardEngine $dashboardEngine)
    {
        $this->subvendorEngine = $subvendorEngine;
        $this->authEngine = $authEngine;
        $this->dashboardEngine = $dashboardEngine;
    }

    /* sub vendor list */
    public function subvendorDataTableList()
    {
        return $this->subvendorEngine->prepareVendorDataTableList();
    }

    public function addSubVendor(CommonRequest $request)
    {
        $request->validate([
            'subvendor_title' => 'required|string|min:2|max:100',
            'username' => 'required|string|alpha_dash|min:2|max:45|unique:users,username',
            'email' => 'required|string|email|max:255|unique:users,email' . (getAppSettings('disallow_disposable_emails') ? '|indisposable' : ''),
            'phone' => 'required|digits:10|unique:users,phone',
            'password' => 'required|string|confirmed|min:8',
            'password_confirmation' => 'required',
        ]);

        $processReaction = $this->authEngine->processRegistration($request->all());

        return $this->processResponse($processReaction, [], [], true);
    }
}
