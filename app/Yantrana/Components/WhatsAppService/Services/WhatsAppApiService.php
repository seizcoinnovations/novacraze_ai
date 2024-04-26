<?php
/**
* WhatsAppApiService.php -
*
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\WhatsAppService\Services;

use App\Yantrana\Base\BaseEngine;
use App\Yantrana\Components\WhatsAppService\Interfaces\WhatsAppServiceEngineInterface;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class WhatsAppApiService extends BaseEngine implements WhatsAppServiceEngineInterface
{
    protected $baseApiRequestEndpoint = 'https://graph.facebook.com/v19.0/'; // Base Request endpoint

    protected $waAccountId; // WhatsApp Business Account ID
    protected $whatsAppPhoneNumberId; // Phone number ID
    protected $accessToken; // Access token
    protected $vendorId = null;

    /**
     * Constructor
     *
     *
     * @return void
     *-----------------------------------------------------------------------*/
    public function __construct(
    ) {
    }

    /**
     * Configure settings based on vendor id
     *
     * @param string $serviceItem
     * @return mixed
     */
    protected function getServiceConfiguration($serviceItem)
    {
        return getVendorSettings($serviceItem, null, null, $this->vendorId ?: getVendorId());
    }

    /**
     * Fetch All the templates of the account
     *
     * @return array
     */
    public function getTemplates()
    {
        return $this->apiGetRequest("{$this->getServiceConfiguration('whatsapp_business_account_id')}/message_templates")['data'];
    }

    /**
     * Delete template
     *
     * @link https://developers.facebook.com/docs/graph-api/reference/whats-app-business-hsm/#Deleting
     *
     * @return void
     */
    public function deleteTemplate($whatsAppTemplateName, $whatsAppTemplateId)
    {
        return $this->apiDeleteRequest("{$this->getServiceConfiguration('whatsapp_business_account_id')}/message_templates", [
            'name' => $whatsAppTemplateName,
            'hsm_id' => $whatsAppTemplateId,
        ]);
    }

    /**
     * Send Template Message
     *
     * @param  object  $whatsAppTemplate
     * @param  int  $toNumber
     * @param  array  $components
     *
     * @link https://developers.facebook.com/docs/whatsapp/cloud-api/guides/send-message-templates
     *
     * @return array
     */
    public function sendTemplateMessage($whatsAppTemplateName, $whatsAppTemplateLanguage, $toNumber, $components = [], $vendorId = null)
    {
        if($vendorId) {
            $this->vendorId = $vendorId;
        }
        return $this->apiPostRequest("{$this->getServiceConfiguration('current_phone_number_id')}/messages", [
            'to' => $toNumber,
            'type' => 'template',
            'template' => [
                'name' => $whatsAppTemplateName,
                'language' => [
                    'code' => $whatsAppTemplateLanguage,
                ],
                'components' => $components,
            ],
        ]);
    }

    /**
     * Send Message
     *
     * @param  int  $toNumber
     * @param  int  $toNumber
     *
     * @link https://developers.facebook.com/docs/whatsapp/cloud-api/guides/send-messages/#sending-free-form-messages

     *
     * @return array
     */
    public function sendMessage($toNumber, $body, $vendorId = null)
    {
        if($vendorId) {
            $this->vendorId = $vendorId;
        }
        return $this->apiPostRequest("{$this->getServiceConfiguration('current_phone_number_id')}/messages", [
            'to' => $toNumber,
            'type' => 'text',
            'text' => [
                'preview_url' => true,
                'body' => $body,
            ],
        ]);
    }

    /**
     * Send Interactive Message
     *
     * @param  int  $toNumber
     * @param  array  $messageData
     *
     * @link https://developers.facebook.com/docs/whatsapp/cloud-api/guides/send-messages/#interactive-messages

     *
     * @return array
     */
    public function sendInteractiveMessage($toNumber, $messageData, $vendorId = null)
    {
        if($vendorId) {
            $this->vendorId = $vendorId;
        }
        $messageData = array_merge([
            'media_link' => '',
            'header_type' => '', // "text", "image", or "video"
            'header_text' => '',
            'body_text' => '',
            'footer_text' => '',
            'buttons' => [
            ],
        ], $messageData);
        $interactiveData = [
            'type' => 'button',
        ];

        if($messageData['header_type'] and ($messageData['header_type'] != 'text')) {
            $interactiveData['header'] = [
                'type' => $messageData['header_type'], // Header types can be "text", "image", or "video"
                $messageData['header_type'] => [
                    'link' => $messageData['media_link'], // your media link
                ]
            ];
        } elseif($messageData['header_type'] and ($messageData['header_type'] == 'text')) {
            $interactiveData['header'] = [
                'type' => 'text', // Header types can be "text", "image", or "video"
                'text' => $messageData['header_text'], // Your header text here
            ];
        }
        if($messageData['body_text']) {
            $interactiveData['body'] = [
                'text' => $messageData['body_text'], // Your footer text here
            ];
        }
        if($messageData['footer_text']) {
            $interactiveData['footer'] = [
                'text' => $messageData['footer_text'], // Your footer text here
            ];
        }
        $buttons = [];
        if($messageData['buttons']) {
            $buttonIndex = 1;
            foreach ($messageData['buttons'] as $button) {
                $buttons[] = [
                    'type' => 'reply',
                    'reply' => [
                        'id' => 'button-id' . $buttonIndex,
                        'title' => $button,
                    ],
                ];
                $buttonIndex++;
            }
            $interactiveData['action'] = [
                'buttons' => $buttons
            ];
        }

        return $this->apiPostRequest("{$this->getServiceConfiguration('current_phone_number_id')}/messages", [
            'to' => $toNumber,
            'type' => 'interactive',
            'interactive' => $interactiveData,
        ]);
    }

    /**
     * Send Media Message
     *
     * @param  int  $toNumber
     * @param  int  $toNumber
     *
     * @link https://developers.facebook.com/docs/whatsapp/cloud-api/guides/send-messages/#media-messages
     * @link https://developers.facebook.com/docs/whatsapp/cloud-api/reference/messages#media-object
     *
     * @return array
     */
    public function sendMediaMessage($toNumber, string $type, string $mediaLink, $caption = '', $filename = '', $vendorId = null)
    {
        if($vendorId) {
            $this->vendorId = $vendorId;
        }
        $typeDetails = [
            'link' => $mediaLink,
        ];
        // if not audio or sticker
        if (! in_array($type, [
            'audio',
            'sticker',
        ])) {
            $typeDetails['caption'] = $caption;
        }
        // if its document
        if (in_array($type, [
            'document',
        ])) {
            $typeDetails['filename'] = $filename;
        }

        return $this->apiPostRequest("{$this->getServiceConfiguration('current_phone_number_id')}/messages", [
            'to' => $toNumber,
            'type' => $type,
            $type => $typeDetails,
        ]);
    }

    /**
     * Send Message
     *
     * @param  int  $toNumber
     * @param  int  $toNumber
     *
     * @link https://developers.facebook.com/docs/whatsapp/cloud-api/guides/mark-message-as-read
     *
     * @return array
     */
    public function markAsRead($toNumber, $messageId, $vendorId = null)
    {
        if($vendorId) {
            $this->vendorId = $vendorId;
        }
        return $this->apiPostRequest("{$this->getServiceConfiguration('current_phone_number_id')}/messages", [
            'to' => $toNumber,
            'status' => 'read',
            'message_id' => $messageId,
        ]);
    }

    /**
     * Health Status
     *
     * @link https://developers.facebook.com/docs/whatsapp/cloud-api/health-status
     *
     * @return array
     */
    public function healthStatus()
    {
        return $this->apiGetRequest("{$this->getServiceConfiguration('whatsapp_business_account_id')}", [
            'fields' => 'health_status',
        ]);
    }

    /**
     * Get Phone Numbers
     *
     * @link https://developers.facebook.com/docs/whatsapp/business-management-api/manage-phone-numbers#all-phone-numbers
     *
     * @return array
     */
    public function phoneNumbers()
    {
        return $this->apiGetRequest("{$this->getServiceConfiguration('whatsapp_business_account_id')}/phone_numbers", []);
    }

    /**
     * Get Phone Numbers
     *
     * @link https://developers.facebook.com/docs/whatsapp/cloud-api/reference/business-profiles
     *
     * @return array
     */
    public function businessProfile()
    {
        return $this->apiGetRequest("{$this->getServiceConfiguration('current_phone_number_id')}/whatsapp_business_profile", []);
    }

    /**
     * Upload Media to WhatsApp server
     * Useful if your uploaded media url is barred by facebook as you may get error like:
     * Your message couldn't be sent because it includes content that other people on Facebook have reported as abusive
     *
     * @link https://developers.facebook.com/docs/whatsapp/cloud-api/reference/media/#upload-media
     *
     * @param  string  $file  - file local path or url
     * @param  string|null  $mimeType  - required if provided file is url based
     * @return array
     */
    public function uploadMedia(string $file, ?string $mimeType = null)
    {
        try {
            if (Str::startsWith($file, 'http')) {
                if (! $mimeType) {
                    return new Exception(__tr('For the url based media type is required'), 400);
                }
            } else {
                $mimeType = mime_content_type($file);
            }
            $ch = curl_init();
            $url = $this->baseApiRequestEndpoint . $this->getServiceConfiguration('current_phone_number_id') . '/media';
            $data = [
                'file' => new \CURLFile($file, $mimeType),
                'type' => $mimeType,
                'messaging_product' => 'whatsapp',
            ];
            $headers = [];
            $headers[] = 'Authorization: Bearer ' . $this->getServiceConfiguration('whatsapp_access_token');
            $headers[] = 'Content-type: multipart/form-data';
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            $result = curl_exec($ch);
            if ($result === false) {
                $result = curl_error($ch) . ' - ' . curl_errno($ch);
            } else {
                $resultDecode = json_decode($result, true);
                if ($resultDecode) {
                    $result = $resultDecode;
                    if (! isset($result['error'])) {
                        return $result;
                    } else {
                        return new Exception($result['error']['message'], $result['error']['code'] ? $result['error']['code'] : 500);
                    }
                }

                return $result;
            }
            curl_close($ch);
        } catch (Exception $e) {
            abortIf(
                true,
                $e->getCode(),
                $e->getMessage()
            );
        }
    }

    public function downloadMedia($mediaId, $vendorId = null)
    {
        if($vendorId) {
            $this->vendorId = $vendorId;
        }
        $retrievedMedia = $this->apiGetRequest("$mediaId", []);
        $mediaResponse = $this->baseApiRequest()->get($retrievedMedia['url']);

        return array_merge($retrievedMedia, [
            'body' => $mediaResponse->body(),
        ]);
    }

    /**
     * ----------------------------------------------------------------------------------------------------------------
     * Below are the BASE Requests like get,post, delete etc
     * -----------------------------------------------------------------------------------------------------------------
     */

    /**
     * Manual API requests
     *
     * @return array
     */
    protected function apiGetRequest(string $requestSubject, array $parameters = [])
    {
        return $this->baseApiRequest()->get("{$this->baseApiRequestEndpoint}{$requestSubject}", $parameters)->json();
    }

    /**
     * Manual API requests
     *
     * @return array
     */
    protected function apiPostRequest(string $requestSubject, array $parameters = [])
    {
        return $this->baseApiRequest()->post("{$this->baseApiRequestEndpoint}/$requestSubject", array_merge(
            [
                'messaging_product' => 'whatsapp',
                'recipient_type' => 'individual',
            ],
            $parameters
        ))->json();
    }

    /**
     * Manual API requests
     *
     * @return array
     */
    protected function apiDeleteRequest(string $requestSubject, array $parameters = [])
    {
        return $this->baseApiRequest()->delete("{$this->baseApiRequestEndpoint}/$requestSubject", $parameters)->json();
    }

    /**
     * Base API requests
     *
     * @return Http query request
     */
    protected function baseApiRequest()
    {
        return Http::withToken($this->getServiceConfiguration('whatsapp_access_token'))->throw(function ($response, $e) {
            $getContents = $response->getBody()->getContents();
            $getContentsDecoded = json_decode($getContents, true);
            $userMessage = Arr::get($getContentsDecoded, 'error.error_user_title', '') . ' '
            . Arr::get($getContentsDecoded, 'error.message', '') . ' '
            . Arr::get($getContentsDecoded, 'error.error_user_msg', '') . ' '
            . Arr::get($getContentsDecoded, 'error.error_data.details');
            if(!$userMessage) {
                $userMessage = $e->getMessage();
            }
            // __logDebug($userMessage);
            // set notification as your key is token expired
            if(Str::contains($e->getMessage(), 'Session has expired') and !getVendorSettings(
                'whatsapp_access_token_expired',
                null,
                null,
                $this->vendorId ?? getVendorId()
            )
            ) {
                setVendorSettings(
                    'internals',
                    [
                        'whatsapp_access_token_expired' => true
                    ],
                    $this->vendorId ?? getVendorId()
                );
            }
            // stop and response back for error if any
            abortIf(
                true,
                $response->status(),
                $userMessage
            );
        });
    }
}
