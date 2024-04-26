<?php
/**
* CampaignEngine.php - Main component file
*
* This file is part of the Campaign component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Campaign;

use Carbon\Carbon;
use Illuminate\Support\Arr;
use App\Yantrana\Base\BaseEngine;
use Illuminate\Support\Facades\Request;
use App\Yantrana\Components\Campaign\Repositories\CampaignRepository;
use App\Yantrana\Components\Campaign\Interfaces\CampaignEngineInterface;

class CampaignEngine extends BaseEngine implements CampaignEngineInterface
{
    /**
     * @var CampaignRepository - Campaign Repository
     */
    protected $campaignRepository;

    /**
     * Constructor
     *
     * @param  CampaignRepository  $campaignRepository  - Campaign Repository
     * @return void
     *-----------------------------------------------------------------------*/
    public function __construct(CampaignRepository $campaignRepository)
    {
        $this->campaignRepository = $campaignRepository;
    }

    /**
     * Campaign datatable source
     *
     * @return array
     *---------------------------------------------------------------- */
    public function prepareCampaignDataTableSource()
    {
        $campaignCollection = $this->campaignRepository->fetchCampaignDataTableSource();
        $timeNow = now();
        // required columns for DataTables
        $requireColumns = [
            '_id',
            '_uid',
            'title',
            'template_name',
            'template_language',
            'created_at' => function ($rowData) {
                return formatDateTime($rowData['created_at']);
            },
            'scheduled_at' => function ($rowData) {
                return (!$rowData['scheduled_at'] or ($rowData['scheduled_at'] != $rowData['created_at'])) ? '<span>📅 </span>' . formatDateTime($rowData['scheduled_at']) : '<span title="' . __tr('Instant') .'">⚡ </span>' . formatDateTime($rowData['scheduled_at']);
            },
            'delete_allowed' => function ($rowData) use (&$timeNow) {
                return Carbon::parse($rowData['scheduled_at']) > $timeNow;
            },
        ];

        // prepare data for the DataTables
        return $this->dataTableResponse($campaignCollection, $requireColumns);
    }

    /**
     * Campaign delete process
     *
     * @param  mix  $campaignIdOrUid
     * @return array
     *---------------------------------------------------------------- */
    public function processCampaignDelete($campaignIdOrUid)
    {
        // fetch the record
        $campaign = $this->campaignRepository->fetchIt($campaignIdOrUid);
        // check if the record found
        if (__isEmpty($campaign)) {
            // if not found
            return $this->engineResponse(18, null, __tr('Campaign not found'));
        }
        // older campaigns can not be deleted
        if($campaign->scheduled_at < now()) {
            return $this->engineResponse(18, null, __tr('Executed Campaign can not be deleted'));
        }
        // ask to delete the record
        if ($this->campaignRepository->deleteIt($campaign)) {
            // if successful
            return $this->engineSuccessResponse([], __tr('Campaign deleted successfully'));
        }

        // if failed to delete
        return $this->engineFailedResponse([], __tr('Failed to delete Campaign'));
    }

    /**
     * Campaign prepare update data
     *
     * @param  mix  $campaignIdOrUid
     * @return object
     *---------------------------------------------------------------- */
    public function prepareCampaignUpdateData($campaignIdOrUid)
    {
        // data fetch request
        $campaign = $this->campaignRepository->fetchIt($campaignIdOrUid);
        // check if record found
        if (__isEmpty($campaign)) {
            // if record not found
            return $this->engineResponse(18, null, __tr('Campaign not found.'));
        }

        // if record found
        return $this->engineSuccessResponse($campaign->toArray());
    }

    /**
     * Campaign prepare update data
     *
     * @param  mix  $campaignIdOrUid
     * @return object
     *---------------------------------------------------------------- */
    public function prepareCampaignData($campaignIdOrUid)
    {
        // data fetch request
        $campaign = $this->campaignRepository->with(['messageLog', 'queueMessages'])->fetchIt($campaignIdOrUid);
        // if record found
        abortIf(__isEmpty($campaign));
        $rawTime = Carbon::parse($campaign->scheduled_at, 'UTC');
        $scheduleAt = $rawTime->setTimezone($campaign->timezone);
        $campaign->scheduled_at_by_timezone = $scheduleAt;
        if (Request::ajax() === true) {
            $messageLog = $campaign->messageLog;
            $queueMessages = $campaign->queueMessages;
            $campaignData = $campaign->__data;
            $totalContacts = (int) Arr::get($campaignData, 'total_contacts');
            $totalRead = $messageLog->where('status', 'read')->count();
            $totalReadInPercent = round($totalRead / $totalContacts * 100, 2) . '%';
            $totalDelivered = $messageLog->where('status', 'delivered')->count() + $totalRead;
            $totalDeliveredInPercent = round($totalDelivered / $totalContacts * 100, 2) . '%';
            $totalFailed = $queueMessages->where('status', 2)->count() + $messageLog->where('status', 'failed')->count();
            $totalFailedInPercent = round($totalFailed / $totalContacts * 100, 2) . '%';
            updateClientModels([
                'messageLog' =>  $messageLog,
                'queueMessages' =>  $queueMessages,
                'totalDelivered' => $totalDelivered,
                'totalDeliveredInPercent' => $totalDeliveredInPercent,
                'totalRead' => $totalRead,
                'totalReadInPercent' => $totalReadInPercent,
                'totalFailed' => $totalFailed,
                'totalFailedInPercent' => $totalFailedInPercent,
            ]);
        }
        // if record found
        return $this->engineSuccessResponse([
            'campaign' => $campaign
        ]);
    }
}
