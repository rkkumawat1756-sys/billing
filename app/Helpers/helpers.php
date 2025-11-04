<?php

use App\Models\Sidebar;
use App\Models\Sessions;
use App\Models\Setting;

if (! function_exists('getSidebarRoleData')) {
    function getSidebarRoleData($parentId = 0)
    {
        $sidebars = Sidebar::where('sidebar_id', $parentId)
            ->where('status', 1)
            ->orderBy('order_by')
            ->get();

        $menu = [];

        foreach ($sidebars as $sidebar) {
            $children = getSidebarRoleData($sidebar->id); // recursive fetch

            $menu[] = [
                'id' => $sidebar->id,
                'name' => $sidebar->name,
                'url' => $sidebar->url,
                'icons' => $sidebar->icons,
                'children' => $children
            ];
        }

        return $menu;
    }
}

if (! function_exists('getSidebarData')) {
    function getSidebarData($parentId = 0, $assigned = [])
    {
        $sidebars = Sidebar::where('sidebar_id', $parentId)
            ->where('status', 1)
            ->orderBy('order_by')
            ->get();

        $menu = [];

        foreach ($sidebars as $sidebar) {
            // recursive call
            $children = getSidebarData($sidebar->id, $assigned);

            // check if this sidebar is in assigned OR its children has assigned items
            if (in_array($sidebar->id, $assigned) || !empty($children)) {
                $menu[] = [
                    'id' => $sidebar->id,
                    'name' => $sidebar->name,
                    'url' => $sidebar->url,
                    'icons' => $sidebar->icons,
                    'children' => $children
                ];
            }
        }

        return $menu;
    }
}


if (!function_exists('getSettingValue')) {
    function getSettingValue($column, $default = null)
    {
        static $settings = null;

        if (!$settings) {
            $settings = Setting::find(1);
        }

        return $settings ? $settings->$column : $default;
    }
}
if (! function_exists('getSessionList')) {
    function getSessionList()
    {
        return collect(); // Return empty collection or array if you want
    }
}

class helper{
    
    public function send($medium, $to, $message, $filepath = null)
    {
        switch ($medium) {
            case 'sms':
                return $this->sendSMS($to, $message);
            case 'email':
                return $this->sendEmail($to, $message);
            case 'whatsapp':
                return $this->sendWhatsApp($to, $message, $filepath);
        }
    }
    
    public static function sendWhatsApp($to, $text = null, $filepath = null)
    {
        //dd($to);
        if (empty($to)) {
            return ['status' => 'error', 'message' => 'Mobile number is required.'];
        }
    
        $params = [
        'username' => 'printstudio',
        'token' => '4ea20f19ed54d47bc0df2c18150a9ef4', // ✅ correct key name
        'type' => 'send', // ✅ required by API
        'number' => '91' . $to, // ✅ add country code
    ];
    
        if (!empty($text)) {
            $params['message'] = $text;
        }
    
        if (!empty($filepath)) {
            $params['fileurl'] = $filepath;
        }
    
        try {

        
            $response = Http::get('https://whatsapp.rusofterp.in/api/send', $params);
          
            if ($response->successful()) {
                return ['status' => 'success', 'response' => $response->body()];
            } else {
                return ['status' => 'error', 'message' => 'API Error', 'response' => $response->body()];
            }
    
        } catch (\Exception $e) {
            return ['status' => 'error', 'message' => 'Request Failed: ' . $e->getMessage()];
        }
    }

}
