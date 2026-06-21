<?php

namespace App\Services\V1;

use App\Models\Setting;

class SettingService
{
    public function updateSetting(Setting $setting, array $data): Setting
    {
        $setting->update($data);
        return $setting;
    }
}
