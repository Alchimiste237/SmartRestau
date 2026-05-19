<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\MenuCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MenuCategoryController extends Controller
{
    public function index()
    {
        $restaurant = Auth::user()->restaurant;
        $categories = $restaurant->menuCategories;

        return view('owner.categories.index', compact('categories', 'restaurant'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $restaurant = Auth::user()->restaurant;

        MenuCategory::create([
            'restaurant_id' => $restaurant->id,
            'name' => $request->name,
            'display_order' => $restaurant->menuCategories()->count() + 1,
        ]);

        return back()->with('success', 'Category created successfully!');
    }

    public function update(Request $request, MenuCategory $category)
    {
        $this->authorizeOwner($category);

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category->update(['name' => $request->name]);

        return back()->with('success', 'Category updated successfully!');
    }

    public function destroy(MenuCategory $category)
    {
        $this->authorizeOwner($category);

        $category->delete();

        return back()->with('success', 'Category deleted successfully!');
    }

    protected function authorizeOwner(MenuCategory $category)
    {
        if ($category->restaurant_id !== Auth::user()->restaurant->id) {
            abort(403);
        }
    }
}
