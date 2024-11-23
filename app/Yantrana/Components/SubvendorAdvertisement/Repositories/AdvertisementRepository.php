<?php

/**
 * SubscriptionRepository.php - Repository file
 *
 * This file is part of the Subscription component.
 *-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\SubvendorAdvertisement\Repositories;

use App\Yantrana\Base\BaseRepository;
// use App\Yantrana\Components\Subvendor\Model\Category;
use App\Yantrana\Components\SubvendorAdvertisement\Models\Advertisement;
use App\Yantrana\Components\SubvendorAdvertisement\Interfaces\AdvertisementRepositoryInterface;
use App\Yantrana\Components\SubvendorCategories\Models\Category;
use App\Yantrana\Components\SubvendorTemplates\Models\Template;
use Illuminate\Support\Facades\DB;

class AdvertisementRepository extends BaseRepository implements AdvertisementRepositoryInterface
{
    /**
     * primary model instance
     *
     * @var object
     */
    protected $primaryModel = Advertisement::class;
    protected $secondaryModel = Category::class;
    protected $templateModel = Template::class;
   
    public function totalAdvertisementsCount()
    {
        return $this->primaryModel::count();
    }

    public function companycategories()
    {
        return $this->secondaryModel::get();
    }

    public function ad_templates()
    {
        return $this->templateModel::get();
    }

    public function storeAdvertisement(array $inputs = [])
    {
        $template_content = $this->templateModel::where('_id', $inputs['template_id'])->value('template');
        $advertisement_array = [
            'subvendor_id' => getsubVendorId(),
            'category_id' => $inputs['category_id'],
            'template_id' => $inputs['template_id'],
            'advertisement_name' => $inputs['advertisement_name'],
            'content_template' => $template_content,
            'content_filled' => $inputs['final_content'],
            // 'image' => $inputs['image'],
        ];

        $advertisement_array =  $this->primaryModel::create($advertisement_array);

        if($advertisement_array)
        {
            return $advertisement_array;
        }
    }

    public function fetchAdvertisementDataTableSource()
    {
        $dataTableConfig = [
            'searchable' => [
                'advertisement_name',
                'created_at'
            ],
        ];

        return $this->primaryModel::leftJoin('sub_vendors', 'sub_vendors.id', '=', 'subvendor_advertisements.subvendor_id')
            ->leftJoin('subvendor_ad_templates_tables', 'subvendor_ad_templates_tables._id', '=', 'subvendor_advertisements.template_id')
            ->leftJoin('sub_vendor_company_categories', 'sub_vendor_company_categories._id', '=', 'subvendor_advertisements.category_id')
            ->select(
                __nestedKeyValues([
                    'subvendor_advertisements' => [
                        '_id',
                        '_uid',
                        'advertisement_name',
                        'template_id',
                        'subvendor_id',
                        'category_id',
                        'content_template',
                        'content_filled',
                        'image',
                        'created_at',
                    ],
                    'sub_vendors' => [
                        'id as userId',
                        'username as username',
                        'email',
                        // 'status as user_status',
                        // DB::raw("CONCAT(COALESCE(first_name, ''), ' ', COALESCE(last_name, '')) AS fullName"),
                    ],
                    'subvendor_ad_templates_tables' => [
                        '_id as templateId',
                        'template_name',
                        'template'
                    ],
                    'sub_vendor_company_categories' => [
                        '_id as categoryId',
                        'name as category_name'
                    ]
                ])
            )
            ->dataTables($dataTableConfig)
            ->toArray();
    }
    
    public function fetchItAdvertisement($advertisementIdOrUid)
    {
        return $this->primaryModel::leftJoin('sub_vendors', 'sub_vendors.id', '=', 'subvendor_advertisements.subvendor_id')
        ->leftJoin('subvendor_ad_templates_tables', 'subvendor_ad_templates_tables._id', '=', 'subvendor_advertisements.template_id')
        ->leftJoin('sub_vendor_company_categories', 'sub_vendor_company_categories._id', '=', 'subvendor_advertisements.category_id')
        ->select(
            __nestedKeyValues([
                'subvendor_advertisements' => [
                    '_id',
                    '_uid',
                    'advertisement_name',
                    'template_id',
                    'subvendor_id',
                    'category_id',
                    'content_template',
                    'content_filled',
                    'image',
                    'created_at',
                ],
                'sub_vendors' => [
                    'id as userId',
                    'username as username',
                    'email',
                    // 'status as user_status',
                    // DB::raw("CONCAT(COALESCE(first_name, ''), ' ', COALESCE(last_name, '')) AS fullName"),
                ],
                'subvendor_ad_templates_tables' => [
                    '_id as templateId',
                    'template_name',
                    'template'
                ],
                'sub_vendor_company_categories' => [
                    '_id as categoryId',
                    'name as category_name'
                ]
            ])
        )
        ->where('subvendor_advertisements._uid', $advertisementIdOrUid)
        ->first()
        ->toArray();
    }

    public function prepareAdvertisementDelete($advertisementIdOrUid)
    {
        $advertisement =  $this->primaryModel::where('_uid', $advertisementIdOrUid)->delete();

        if($advertisement)
        {
            return $advertisement;
        }
    }
}
