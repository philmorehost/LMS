<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = $this->getSettings();
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->except('_token');

        try {
            foreach ($data as $key => $value) {
                \DB::table('settings')->updateOrInsert(
                    ['key' => $key],
                    ['value' => $value, 'updated_at' => now()]
                );
            }
        } catch (\Exception $e) {}

        return back()->with('success', 'Settings updated successfully.');
    }

    protected function getSettings(): array
    {
        $settings = [];
        try {
            $rows = \DB::table('settings')->get();
            foreach ($rows as $row) {
                $settings[$row->key] = $row->value;
            }
        } catch (\Exception $e) {}
        return $settings;
    }
}
