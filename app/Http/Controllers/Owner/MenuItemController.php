<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MenuItemController extends Controller
{
    public function index(Request $request)
    {
        $restaurant = Auth::user()->restaurant;
        $categories = $restaurant->menuCategories;
        
        $query = MenuItem::where('restaurant_id', $restaurant->id);
        
        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        $items = $query->with('category')->orderBy('display_order')->get();

        return view('owner.items.index', compact('items', 'categories', 'restaurant'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:menu_categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'photo' => 'nullable|image|max:2048',
        ]);

        $restaurant = Auth::user()->restaurant;
        
        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('menu-items', 'public');
        }

        MenuItem::create([
            'restaurant_id' => $restaurant->id,
            'category_id' => $request->category_id,
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'photo_path' => $photoPath,
            'display_order' => MenuItem::where('category_id', $request->category_id)->count() + 1,
        ]);

        return back()->with('success', 'Menu item added successfully!');
    }

    public function update(Request $request, MenuItem $item)
    {
        $this->authorizeOwner($item);

        $request->validate([
            'category_id' => 'required|exists:menu_categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'photo' => 'nullable|image|max:2048',
        ]);

        $data = $request->only(['category_id', 'name', 'description', 'price']);

        if ($request->hasFile('photo')) {
            // Delete old photo
            if ($item->photo_path) {
                Storage::disk('public')->delete($item->photo_path);
            }
            $data['photo_path'] = $request->file('photo')->store('menu-items', 'public');
        }

        $item->update($data);

        return back()->with('success', 'Menu item updated successfully!');
    }

    public function toggleAvailability(MenuItem $item)
    {
        $this->authorizeOwner($item);

        $item->update(['is_available' => !$item->is_available]);

        return back()->with('success', 'Availability updated!');
    }

    public function destroy(MenuItem $item)
    {
        $this->authorizeOwner($item);

        if ($item->photo_path) {
            Storage::disk('public')->delete($item->photo_path);
        }

        $item->delete();

        return back()->with('success', 'Menu item deleted!');
    }

    protected function authorizeOwner(MenuItem $item)
    {
        if ($item->restaurant_id !== Auth::user()->restaurant->id) {
            abort(403);
        }
    }
}
