<?php

namespace App\Http\Controllers;

use App\Models\HeroBanner;
use Illuminate\Http\Request;

class AdminHeroBannerController extends Controller
{
    public function index()
    {
        $heroBanners = HeroBanner::ordered()->get();
        return view('admin.hero-banners.index', compact('heroBanners'));
    }

    public function create()
    {
        return view('admin.hero-banners.create');
    }

    public function store(Request $request)
    {
        // Check if request is empty (indicates POST size limit exceeded)
        if (empty($request->all()) && empty($_FILES)) {
            return back()->withErrors(['error' => 'Upload failed: File too large or POST data limit exceeded. Try a smaller file (under 10MB).']);
        }

        \Log::info('Hero banner upload started', [
            'has_file' => $request->hasFile('media'),
            'post_size' => strlen(file_get_contents('php://input')),
            'content_length' => $request->header('Content-Length')
        ]);

        try {
            $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
                'title' => 'nullable|string|max:255',
                'description' => 'nullable|string',
                'media' => 'required|file|mimes:jpeg,jpg,png,gif,mp4,webm|max:10240', // 10MB limit
                'button_text' => 'nullable|string|max:255',
                'button_link' => 'nullable|url',
                'order' => 'required|integer|min:0'
            ]);

            if ($validator->fails()) {
                \Log::error('Validation failed', $validator->errors()->toArray());
                return back()->withInput()->withErrors($validator->errors());
            }

            if (!$request->hasFile('media')) {
                return back()->withInput()->withErrors(['media' => 'Media file is required']);
            }

            $file = $request->file('media');
            
            if (!$file->isValid()) {
                \Log::error('Invalid file', ['error' => $file->getError()]);
                return back()->withInput()->withErrors(['media' => 'Invalid file uploaded. Error code: ' . $file->getError()]);
            }

            $extension = strtolower($file->getClientOriginalExtension());
            $allowedExtensions = ['jpeg', 'jpg', 'png', 'gif', 'mp4', 'webm'];
            
            if (!in_array($extension, $allowedExtensions)) {
                return back()->withInput()->withErrors(['media' => 'File type not supported. Allowed: ' . implode(', ', $allowedExtensions)]);
            }

            $mediaType = in_array($extension, ['mp4', 'webm']) ? 'video' : 'image';
            
            $filename = time() . '_' . uniqid() . '.' . $extension;
            $destinationPath = public_path('storage/hero-banners');
            
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }
            
            $success = $file->move($destinationPath, $filename);
            
            if (!$success) {
                return back()->withInput()->withErrors(['media' => 'Failed to upload file to server']);
            }
            
            HeroBanner::create([
                'title' => $request->title,
                'description' => $request->description,
                'media_path' => '/storage/hero-banners/' . $filename,
                'media_type' => $mediaType,
                'button_text' => $request->button_text,
                'button_link' => $request->button_link,
                'order' => $request->order,
                'is_active' => $request->has('is_active')
            ]);

            \Log::info('Hero banner created successfully');
            return redirect()->route('admin.hero-banners.index')->with('success', 'Hero banner created successfully');
            
        } catch (\Exception $e) {
            \Log::error('Hero banner creation failed: ' . $e->getMessage());
            return back()->withInput()->withErrors(['error' => 'Upload failed: ' . $e->getMessage()]);
        }
    }

    public function edit(HeroBanner $heroBanner)
    {
        return view('admin.hero-banners.edit', compact('heroBanner'));
    }

    public function update(Request $request, HeroBanner $heroBanner)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'media' => 'nullable|file|mimes:jpeg,png,jpg,gif,mp4,webm,mov|max:8192',
            'button_text' => 'nullable|string|max:255',
            'button_link' => 'nullable|url',
            'order' => 'required|integer|min:0'
        ]);

        $data = [
            'title' => $request->title,
            'description' => $request->description,
            'button_text' => $request->button_text,
            'button_link' => $request->button_link,
            'order' => $request->order,
            'is_active' => $request->has('is_active')
        ];

        if ($request->hasFile('media')) {
            $file = $request->file('media');
            $extension = strtolower($file->getClientOriginalExtension());
            $mediaType = in_array($extension, ['mp4', 'webm', 'mov']) ? 'video' : 'image';
            
            $filename = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('storage/hero-banners');
            
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }
            
            $file->move($destinationPath, $filename);
            
            $data['media_path'] = '/storage/hero-banners/' . $filename;
            $data['media_type'] = $mediaType;
        }

        $heroBanner->update($data);

        return redirect()->route('admin.hero-banners.index')->with('success', 'Hero banner updated successfully');
    }

    public function destroy(HeroBanner $heroBanner)
    {
        if (file_exists(public_path($heroBanner->media_path))) {
            unlink(public_path($heroBanner->media_path));
        }
        
        $heroBanner->delete();
        return redirect()->route('admin.hero-banners.index')->with('success', 'Hero banner deleted successfully');
    }
}