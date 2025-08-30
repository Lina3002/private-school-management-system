<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\ActivityLog;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = Setting::orderBy('group')->orderBy('key')->get();
        return view('superadmin.settings', compact('settings'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'key' => 'required|string|unique:settings,key',
            'value' => 'nullable',
            'type' => 'required|string',
            'description' => 'nullable|string',
            'group' => 'nullable|string',
        ]);
        $setting = Setting::create($data);
        ActivityLog::create([
            'user_id' => auth()->id(),
            'description' => 'Created setting: ' . $data['key'],
            'subject_type' => Setting::class,
            'subject_id' => $setting->id,
            'properties' => json_encode($data),
            'ip_address' => $request->ip(),
        ]);
        return redirect()->route('superadmin.settings.index')->with('success', 'Setting created.');
    }

    public function update(Request $request, Setting $setting)
    {
        $data = $request->validate([
            'value' => 'nullable',
            'type' => 'required|string',
            'description' => 'nullable|string',
            'group' => 'nullable|string',
        ]);
        $setting->update($data);
        ActivityLog::create([
            'user_id' => auth()->id(),
            'description' => 'Updated setting: ' . $setting->key,
            'subject_type' => Setting::class,
            'subject_id' => $setting->id,
            'properties' => json_encode($data),
            'ip_address' => $request->ip(),
        ]);
        return redirect()->route('superadmin.settings.index')->with('success', 'Setting updated.');
    }

    public function destroy(Request $request, Setting $setting)
    {
        $setting->delete();
        ActivityLog::create([
            'user_id' => auth()->id(),
            'description' => 'Deleted setting: ' . $setting->key,
            'subject_type' => Setting::class,
            'subject_id' => $setting->id,
            'properties' => '{}',
            'ip_address' => $request->ip(),
        ]);
        return redirect()->route('superadmin.settings.index')->with('success', 'Setting deleted.');
    }
}
