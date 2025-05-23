<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ThemeController extends Controller
{
    public function index()
    {
        $themeSettings = $this->getThemeSettings();
        return view('tenant.admin.theme', compact('themeSettings'));
    }

    public function updateTheme(Request $request)
    {
        $request->validate([
            'primary_color' => 'required|string',
            'secondary_color' => 'required|string',
            'sidebar_color' => 'required|string',
            'text_color' => 'required|string',
            'font_family' => 'required|string',
            'navbar_style' => 'required|string',
            'card_style' => 'required|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $themeSettings = [
            'primary_color' => $request->primary_color,
            'secondary_color' => $request->secondary_color,
            'sidebar_color' => $request->sidebar_color,
            'text_color' => $request->text_color,
            'font_family' => $request->font_family,
            'navbar_style' => $request->navbar_style,
            'card_style' => $request->card_style,
            'updated_at' => now()->toDateTimeString()
        ];

        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if (isset($this->getThemeSettings()['logo_path'])) {
                Storage::disk('public')->delete($this->getThemeSettings()['logo_path']);
            }

            $logoPath = $request->file('logo')->store('theme/logos', 'public');
            $themeSettings['logo_path'] = $logoPath;
        } else {
            // Keep existing logo if no new one uploaded
            $existingSettings = $this->getThemeSettings();
            if (isset($existingSettings['logo_path'])) {
                $themeSettings['logo_path'] = $existingSettings['logo_path'];
            }
        }

        Storage::disk('public')->put('theme/settings.json', json_encode($themeSettings));

        return redirect()->back()->with('success', 'Theme settings updated successfully!');
    }

    public function getThemeSettings()
    {
        if (Storage::disk('public')->exists('theme/settings.json')) {
            return json_decode(Storage::disk('public')->get('theme/settings.json'), true);
        }

        // Default theme settings
        return [
            'primary_color' => '#343a40',
            'secondary_color' => '#495057',
            'sidebar_color' => '#343a40',
            'text_color' => '#ffffff',
            'font_family' => 'Segoe UI',
            'navbar_style' => 'dark',
            'card_style' => 'default',
            'logo_path' => null
        ];
    }
} 