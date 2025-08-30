<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{
    public function index()
    {
        $school = Auth::user()->school;
        // Global settings (no school_id column present in settings table)
        $settings = Setting::orderBy('group')->orderBy('key')->get();
        return view('admin.settings.index', compact('settings', 'school'));
    }

    public function update(Request $request, $id)
    {
        $school = Auth::user()->school;
        $setting = Setting::findOrFail($id);
        $request->validate([
            'key' => 'required',
            'value' => 'required',
        ]);
        $setting->update($request->only(['key', 'value']));
        return redirect()->route('admin.settings.index')->with('success', 'Setting updated.');
    }
    public function destroy($id)
    {
        $school = Auth::user()->school;
        $setting = Setting::findOrFail($id);
        $setting->delete();
        return redirect()->route('admin.settings.index')->with('success', 'Setting deleted.');
    }
}
