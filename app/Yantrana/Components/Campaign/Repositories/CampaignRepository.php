<?php
/**
* CampaignRepository.php - Repository file
*
* This file is part of the Campaign component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Campaign\Repositories;

use App\Yantrana\Base\BaseRepository;
use App\Yantrana\Components\Campaign\Interfaces\CampaignRepositoryInterface;
use App\Yantrana\Components\Campaign\Models\CampaignModel;

class CampaignRepository extends BaseRepository implements CampaignRepositoryInterface
{
    /**
     * primary model instance
     *
     * @var object
     */
    protected $primaryModel = CampaignModel::class;

    /**
     * Fetch campaign datatable source
     *
     * @return mixed
     *---------------------------------------------------------------- */
    public function fetchCampaignDataTableSource()
    {
        // basic configurations for dataTables data
        $dataTableConfig = [
            // searchable columns
            'searchable' => [
                'title',
                'whatsapp_templates__id',
                'scheduled_at',
            ],
        ];
        // get Model result for dataTables
        return CampaignModel::where([
            'vendors__id' => getVendorId()
        ])->dataTables($dataTableConfig)->toArray();
    }

    /**
     * Delete $campaign record and return response
     *
     * @param  object  $inputData
     * @return mixed
     *---------------------------------------------------------------- */
    public function deleteCampaign($campaign)
    {
        // Check if $campaign deleted
        if ($campaign->deleteIt()) {
            // if deleted
            return true;
        }
        // if failed to delete
        return false;
    }

    /**
     * Store new campaign record and return response
     *
     * @param  array  $inputData
     * @return mixed
     *---------------------------------------------------------------- */
    public function storeCampaign($inputData)
    {
        // prepare data to store
        $keyValues = [
            'title',
            'template_name',
            'whatsapp_templates__id' => $inputData['whatsapp_template'],
            'scheduled_at' => $inputData['schedule_at'],
        ];
        return $this->storeIt($inputData, $keyValues);
    }
}
