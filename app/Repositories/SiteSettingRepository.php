<?php 
namespace App\Repositories;

use App\Models\SiteSetting;
use Illuminate\Http\UploadedFile;

class SiteSettingRepository
{
    public function getSiteSettings($with = [])
    {
        return SiteSetting::with($with)->get();
    }

    public function createSiteSetting(array $data)
    {
        return SiteSetting::create($data);
    }

    public function updateSiteSetting(SiteSetting $siteSetting , array $data)
    {
        $siteSetting->update($data);

        // Handle media uploads
        $this->handleMediaUploads($siteSetting, $data);

        return $siteSetting;
    }

    public function deleteSiteSetting(SiteSetting $siteSetting)
    {
        $siteSetting->delete();
    }

    public function findById(int $id): ?SiteSetting
    {
        return SiteSetting::with('branches.phones')->find($id);
    }

    private function handleMediaUploads(SiteSetting $siteSetting, array $data)
    {
        $mediaFields = [
            'gym_logo' => 'gym_logo',
            'favicon' => 'favicon',
            'email_logo' => 'email_logo',
            'footer_logo' => 'footer_logo',
        ];

        foreach ($mediaFields as $field => $collection) {
            if (isset($data[$field]) && $data[$field] instanceof UploadedFile) {
                $siteSetting->addMedia($data[$field])->toMediaCollection($collection);
            }
        }
    }
}