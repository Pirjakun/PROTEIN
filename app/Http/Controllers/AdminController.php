<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Product;
use App\Models\Slide;
use App\Models\Archive;
use App\Models\Community;

use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function index()
    {
        $products = Product::all();
        $slides = Slide::all();
        $archives = Archive::all();
        $communities = Community::all();
        return view('admin.dashboard', compact('products', 'slides', 'archives', 'communities'));
    }



    public function create()
    {
        $categories = \App\Models\Category::all();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'category' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'shopee_link' => 'nullable|url',
        ]);

        $input = $request->all();

        if ($image = $request->file('image')) {
            $destinationPath = 'assets/products/';
            $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move(public_path('assets/products'), $profileImage);
            $input['image'] = $profileImage;
        }

        if ($request->hasFile('gallery')) {
            $gallery = [];
            foreach ($request->file('gallery') as $file) {
                $filename = date('YmdHis') . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('assets/products'), $filename);
                $gallery[] = $filename;
            }
            $input['gallery'] = $gallery; // Cast to array, model cast should handle json_encode if configured, or I manually encode? Product model likely casts 'gallery' => 'array'.
        }

        Product::create($input);

        return redirect()->route('admin.dashboard')
            ->with('success', 'Product created successfully.');
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = \App\Models\Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'category' => 'required',
            'shopee_link' => 'nullable|url',
        ]);

        $input = $request->all();
        $product = Product::findOrFail($id);

        if ($image = $request->file('image')) {
            $destinationPath = 'assets/products/';
            $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move(public_path('assets/products'), $profileImage);
            $input['image'] = $profileImage;
        } else {
            unset($input['image']);
        }

        if ($request->hasFile('gallery')) {
            $currentGallery = $product->gallery ?? [];
            foreach ($request->file('gallery') as $file) {
                $filename = date('YmdHis') . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('assets/products'), $filename);
                $currentGallery[] = $filename;
            }
            $input['gallery'] = $currentGallery;
        }

        // Handle Gallery Deletion
        if ($request->has('delete_gallery')) {
            $galleryCleanup = $input['gallery'] ?? ($product->gallery ?? []);
            $toDelete = $request->input('delete_gallery');

            foreach ($toDelete as $delImage) {
                // Remove from array
                if (($key = array_search($delImage, $galleryCleanup)) !== false) {
                    unset($galleryCleanup[$key]);
                }

                // Delete physical file
                $filePath = public_path('assets/products/' . $delImage);
                if (file_exists($filePath)) {
                    @unlink($filePath);
                }
            }

            $input['gallery'] = array_values($galleryCleanup); // Reindex array
        }

        $product->update($input);

        return redirect()->route('admin.dashboard')
            ->with('success', 'Product updated successfully');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->route('admin.dashboard')
            ->with('success', 'Product deleted successfully');
    }

    public function toggleFeatured($id)
    {
        $product = Product::findOrFail($id);

        if ($product->is_featured) {
            // Unfeature
            $product->update(['is_featured' => false]);
            return redirect()->back()->with('success', 'Product un-featured.');
        } else {
            // Feature - Check limit
            $count = Product::where('is_featured', true)->count();
            if ($count >= 3) {
                return redirect()->back()->withErrors(['featured' => 'Maximum 3 featured products allowed. Un-feature one first.']);
            }
            $product->update(['is_featured' => true]);
            return redirect()->back()->with('success', 'Product featured successfully.');
        }
    }

    // Slides Management
    public function storeSlide(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'title' => 'nullable|string|max:255',
        ]);

        $input = $request->except('_token');

        if ($image = $request->file('image')) {
            $destinationPath = 'assets/slides/';
            $slideImage = 'slide_' . date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move(public_path('assets/slides'), $slideImage);
            // $input is already set, just update image field
            $input['image'] = $slideImage;
        }

        Slide::create($input);

        return redirect()->route('admin.dashboard')
            ->with('success', 'Slide added successfully.');
    }

    public function editSlide($id)
    {
        $slide = Slide::findOrFail($id);
        return view('admin.slides.edit', compact('slide'));
    }

    public function updateSlide(Request $request, $id)
    {
        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'title' => 'nullable|string|max:255',
        ]);

        $slide = Slide::findOrFail($id);
        $input = $request->except(['_token', '_method']); // Also exclude _method for PUT/PATCH if using all() but update() usually handles it. update() on model is fine with extra fields if they are not in fillable? No, update() might also throw or just ignore. Eloquent ignores non-fillable attributes on update usually? Actually create() throws MassAssignmentException if strictly guarded, but update might be safer? Let's use except just in case.

        if ($image = $request->file('image')) {
            $destinationPath = 'assets/slides/';
            $slideImage = 'slide_' . date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move(public_path('assets/slides'), $slideImage);
            $input['image'] = $slideImage;
        } else {
            unset($input['image']);
        }

        $slide->update($input);

        return redirect()->route('admin.dashboard')
            ->with('success', 'Slide updated successfully.');
    }

    public function destroySlide($id)
    {
        $slide = Slide::findOrFail($id);
        $slide->delete();

        return redirect()->route('admin.dashboard')
            ->with('success', 'Slide deleted successfully');
    }

    // Archives Management
    public function storeArchive(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'link' => 'required|url',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        $input = $request->except('_token');

        if ($image = $request->file('image')) {
            $destinationPath = 'assets/archives/';
            $archiveImage = 'archive_' . date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move(public_path('assets/archives'), $archiveImage);
            $input['image'] = $archiveImage;
        }

        Archive::create($input);

        return redirect()->route('admin.dashboard')
            ->with('success', 'Archive content added successfully.');
    }

    public function editArchive($id)
    {
        $archive = Archive::findOrFail($id);
        return view('admin.archives.edit', compact('archive'));
    }

    public function updateArchive(Request $request, $id)
    {
        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'link' => 'required|url',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        $archive = Archive::findOrFail($id);
        $input = $request->except(['_token', '_method']);

        if ($image = $request->file('image')) {
            $destinationPath = 'assets/archives/';
            $archiveImage = 'archive_' . date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move(public_path('assets/archives'), $archiveImage);
            $input['image'] = $archiveImage;
        } else {
            unset($input['image']);
        }

        $archive->update($input);

        return redirect()->route('admin.dashboard')
            ->with('success', 'Archive content updated successfully.');
    }

    public function destroyArchive($id)
    {
        $archive = Archive::findOrFail($id);

        // Optional: Delete image file
        $imagePath = public_path('assets/archives/' . $archive->image);
        if (file_exists($imagePath)) {
            @unlink($imagePath);
        }

        $archive->delete();

        return redirect()->route('admin.dashboard')
            ->with('success', 'Archive content deleted successfully');
    }

    // Community Management
    // Community Management
    public function storeCommunity(Request $request)
    {
        // Simple and robust upload logic
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:10240', // 10MB Limit
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');

            if (!$image->isValid()) {
                return back()->withErrors(['image' => 'File upload failed or is invalid.']);
            }

            $communityImage = 'community_' . date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move(public_path('assets/community'), $communityImage);

            Community::create([
                'image' => $communityImage
            ]);

            return redirect()->route('admin.dashboard')
                ->with('success', 'Community content added successfully.');
        }

        return back()->withErrors(['image' => 'No image file provided.']);
    }

    public function destroyCommunity($id)
    {
        $community = Community::findOrFail($id);

        // Delete physical file
        $filePath = public_path('assets/community/' . $community->image);
        if (file_exists($filePath)) {
            @unlink($filePath);
        }

        $community->delete();

        return redirect()->route('admin.dashboard')
            ->with('success', 'Community content deleted successfully');
    }
}

