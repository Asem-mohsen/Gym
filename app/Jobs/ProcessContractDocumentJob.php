<?php

namespace App\Jobs;

use Exception;
use App\Models\SiteSetting;
use App\Services\ContractDocumentService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessContractDocumentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $siteSettingId;

    /**
     * Create a new job instance.
     */
    public function __construct(int $siteSettingId)
    {
        $this->siteSettingId = $siteSettingId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $siteSetting = SiteSetting::find($this->siteSettingId);
            
            if (!$siteSetting) {
                Log::error('SiteSetting not found for contract document processing', [
                    'site_setting_id' => $this->siteSettingId
                ]);
                return;
            }

            $service = new ContractDocumentService();
            $service->handleContractDocument($siteSetting);
            
            Log::info('Contract document processing completed successfully', [
                'site_setting_id' => $this->siteSettingId
            ]);
            
        } catch (Exception $e) {
            Log::error('Error processing contract document', [
                'site_setting_id' => $this->siteSettingId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Re-throw the exception to mark the job as failed
            throw $e;
        }
    }
}
