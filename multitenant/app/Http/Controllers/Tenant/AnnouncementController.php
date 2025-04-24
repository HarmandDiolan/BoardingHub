<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function index(){

        $announcements = Announcement::latest()->get();
        
        return view('tenant.admin.announcements', compact('announcements'));
    }

    public function store(Request $request){
        $request -> validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        Announcement::create([
            'title' => $request->title,
            'content' => $request->content,
            'is_active' => $request->has('is_active')
        ]);

        return redirect()->back()->with('success', 'Announcement created successfully');

    }


}
