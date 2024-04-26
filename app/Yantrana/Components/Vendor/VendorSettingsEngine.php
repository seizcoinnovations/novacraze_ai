<?php

/**
 * VendorSettingsEngine.php - Main component file
 *
 * This file is part of the Vendor component.
 *-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Vendor;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use App\Yantrana\Base\BaseEngine;
use App\Yantrana\Support\CommonTrait;
use App\Yantrana\Support\Country\Repositories\CountryRepository;
use App\Yantrana\Components\Vendor\Repositories\VendorRepository;
use App\Yantrana\Components\Contact\Repositories\ContactRepository;
use App\Yantrana\Components\Vendor\Repositories\VendorSettingsRepository;
use App\Yantrana\Components\Vendor\Interfaces\VendorSettingsEngineInterface;

class VendorSettingsEngine extends BaseEngine implements VendorSettingsEngineInterface
{
    /**
     * @var CommonTrait - Common Trait
     */
    use CommonTrait;

    /**
     * @var VendorSettingsRepository - VendorSettings Repository
     */
    protected $vendorSettingsRepository;

    /**
     * @var ContactRepository - Contact Repository
     */
    protected $contactRepository;

    /**
     * @var CountryRepository - Country Repository
     */
    protected $countryRepository;

    /**
     * @var VendorRepository - Vendor Repository
     */
    protected $vendorRepository;

    /**
     * Constructor
     *
     * @param  VendorSettingsRepository  $vendorSettingsRepository  - VendorSettings Repository
     * @param  CountryRepository  $countryRepository  - Country Repository
     * @param  VendorRepository  $vendorRepository  - Vendor Repository
     * @param  ContactRepository  $contactRepository  - Contacts Repository
     * @return void
     *-----------------------------------------------------------------------*/

    public function __construct(
        VendorSettingsRepository $vendorSettingsRepository,
        CountryRepository $countryRepository,
        VendorRepository $vendorRepository,
        ContactRepository $contactRepository
        )
    {
        $this->vendorSettingsRepository = $vendorSettingsRepository;
        $this->countryRepository = $countryRepository;
        $this->vendorRepository = $vendorRepository;
        $this->contactRepository = $contactRepository;
    }

    /**
     * Prepare Configuration.
     *
     * @param  string  $pageType
     * @return array
     *---------------------------------------------------------------- */
    public function prepareConfigurations($pageType)
    {
        // Get settings from config
        $defaultSettings = $this->getDefaultSettings(config('__vendor-settings.items.' . $pageType));

        // check if default settings exists
        if (__isEmpty($defaultSettings)) {
            return $this->engineResponse(18, null, __tr('Invalid page type.'));
        }
        $configurationSettings = $dbConfigurationSettings = [];
        // Check if default settings exists
        if (! __isEmpty($defaultSettings)) {
            // Get selected default settings
            $configurationCollection = $this->vendorSettingsRepository->fetchByNames(array_keys($defaultSettings));
            // check if configuration collection exists
            if (! __isEmpty($configurationCollection)) {
                foreach ($configurationCollection as $configuration) {
                    $dbConfigurationSettings[$configuration->name] = $this->castValue($configuration->data_type, $configuration->value);
                }
            }
            // Loop over the default settings
            foreach ($defaultSettings as $defaultSetting) {
                $configurationSettings[$defaultSetting['key']] = $this->prepareDataForConfiguration($dbConfigurationSettings, $defaultSetting);
                $configurationSettings[$defaultSetting['key'] . '_options'] = array_get($defaultSetting, 'options', []);
            }
        }
        //check page type is currency
        if ($pageType == 'general') {
            $configurationSettings['timezone_list'] = $this->getTimeZone();
            $configurationSettings['countries_list'] = $this->countryRepository->fetchAll()->toArray();
            $languages = getAppSettings('translation_languages');
            //set default language
            $languageList[] = [
                'id' => 'en',
                'name' => __tr('System Language (English)'),
                'status' => true,
            ];

            //check is not empty
            if (! __isEmpty($languages)) {
                foreach ($languages as $key => $language) {
                    if ($language['status']) {
                        $languageList[] = [
                            'id' => $language['id'],
                            'name' => $language['name'],
                            'status' => $language['status'],
                        ];
                    }
                }
            }
            $configurationSettings['languageList'] = $languageList;
        } elseif ($pageType == 'currency') {
            $configurationSettings['currencies'] = config('__currencies.currencies');
            $configurationSettings['currency_options'] = $this->generateCurrenciesArray($configurationSettings['currencies']['details']);
        } elseif ($pageType == 'email') {
            $configurationSettings['mail_drivers'] = configItem('mail_drivers');
            $configurationSettings['mail_encryption_types'] = configItem('mail_encryption_types');
        } elseif ($pageType == 'whatsapp_cloud_api_setup') {
            $configurationSettings['contactsListData'] = $this->contactRepository->fetchItAll([
                'vendors__id' => getVendorId()
            ], [
                '_uid',
                'first_name',
                'last_name',
            ]);
        }
        return $this->engineSuccessResponse([
            'configurationData' => $configurationSettings,
        ]);
    }

    /**
     * Process Configuration Store.
     *
     * @param  string  $pageType
     * @param  array  $inputData
     * @return EngineResponse
     *---------------------------------------------------------------- */
    public function updateProcess($pageType, $inputData, $vendorId = null)
    {
        $dataForStoreOrUpdate = $configurationKeysForDelete = [];
        $isDataAddedOrUpdated = false;

        // Get settings from config
        $defaultSettings = $this->getDefaultSettings(config('__vendor-settings.items.' . $pageType));

        // check if default settings exists
        if (__isEmpty($defaultSettings)) {
            return $this->engineResponse(18, ['show_message' => true], __tr('Invalid page type.'));
        }
        // Get selected default settings
        $configurationCollection = $this->vendorSettingsRepository->fetchByNames(array_keys($defaultSettings))->pluck('value', 'name')->toArray();
        // Check if input data exists
        if (! __isEmpty($inputData)) {
            foreach ($defaultSettings as $defaultInputKey => $defaultInputValue) {
                $inputKey = $defaultInputKey;
                $inputValue = Arr::get($inputData, $inputKey);

                // ignore the item for saving/updating if sent the empty values sent
                if (Arr::get($defaultSettings, "$inputKey.ignore_empty") and ! $inputValue) {
                    continue;
                }

                // Check if default text and form text not same
                $castValues = $this->castValue(
                    ($defaultSettings[$inputKey]['data_type'] == 4)
                        ? 5 : $defaultSettings[$inputKey]['data_type'], // for Encode purpose only
                    $inputValue
                );
                if (array_get($defaultSettings[$inputKey], 'hide_value') and ! __isEmpty($inputValue)) {
                    $dataForStoreOrUpdate[] = [
                        'name' => $inputKey,
                        'value' => ($castValues and is_string($castValues) or is_numeric($castValues)) ? encrypt($castValues) : $castValues,
                        'data_type' => $defaultSettings[$inputKey]['data_type'],
                    ];
                } elseif (! array_get($defaultSettings[$inputKey], 'hide_value')) {
                    $dataForStoreOrUpdate[] = [
                        'name' => $inputKey,
                        'value' => $castValues,
                        'data_type' => $defaultSettings[$inputKey]['data_type'],
                    ];
                }
            }
            // Send data for store or update
            if (
                ! __isEmpty($dataForStoreOrUpdate)
                and $this->vendorSettingsRepository->storeOrUpdate($dataForStoreOrUpdate, $vendorId)
            ) {
                activityLog('vendor settings updated');
                $isDataAddedOrUpdated = true;
            }

            // Check if data added / updated or deleted
            if ($isDataAddedOrUpdated) {
                // set token is not expired
                if(isset($inputData['whatsapp_access_token']) and $inputData['whatsapp_access_token']) {
                    $this->updateProcess('internals', [
                        'whatsapp_access_token_expired' => 0
                    ], $vendorId);
                }

                return $this->engineResponse(21,[
                    'show_message' => true,
                    'messageType' => 'success',
                    'reloadPage' => true
                ], __tr('Settings updated successfully ... reloading'));
            }

            return $this->engineResponse(14, ['show_message' => true], __tr('Nothing updated.'));
        }

        return $this->engineFailedResponse(['show_message' => true], __tr('Something went wrong on server.'));
    }

    /**
     * Process Vendor basic details update
     *
     * @param [type] $inputData
     * @return void
     */
    public function updateBasicSettingsProcess($inputData)
    {
        $updateData = [];
        if (Arr::get($inputData, 'store_name')) {
            $updateData['title'] = $inputData['store_name'];
        }

        if (Arr::get($inputData, 'logo_name')) {
            $updateData['logo_image'] = $inputData['logo_name'];
        }

        if (Arr::get($inputData, 'favicon_name')) {
            $updateData['favicon'] = $inputData['favicon_name'];
        }

        if ($this->vendorRepository->updateIt(getVendorUid(), $updateData)) {
            return $this->engineSuccessResponse(['show_message' => true], __tr('Settings updated successfully.'));
        }

        return $this->engineResponse(14, ['show_message' => true], __tr('Nothing updated.'));
    }
}
