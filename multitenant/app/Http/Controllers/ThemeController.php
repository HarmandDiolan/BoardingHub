<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreThemeRequest;

class ThemeController extends Controller
{
    public function updateTheme(StoreThemeRequest $request)
    {
        // Save theme settings to JSON file
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
            'card_style' => 'default'
        ];
    }
} 