<?php

namespace App\Actions;

use App\Models\Category;
use App\Models\File;
use Illuminate\Support\Facades\Storage;

class CategoryAction
{
    /**
     * Create a new class instance.
     */

    public function categoryIndex($validated)
    {
        $category = Category::query();

        if (isset($validated['sort_order'])) {
            $category->orderBy('title', $validated['sort_order']);
        }
        if (isset($validated['search'])) {
            $category = $category->where('title', 'iLike', $validated['title']);
        }
        $category->paginate(perPage: $validated['perPage'], page:  $validated['page'])->withQueryString();

        return $category;
    }

    public function categoryStore($validated)
    {
        $path = Storage::disk('public')->putFile('uploads', $validated['file']);
        $file = File::create(['path' => $path]);

        return Category::create([
            'title' => $validated['title'],
            'file_id' => $file->id,
        ]);
    }

    public function categoryShow($id)
    {
        return Category::findOrFail($id);
    }

    public function categoryUpdate($category, $validated)
    {
        $category->update($validated);
        $category->save();

        if (isset($validated['file'])) {
            $path = Storage::disk('public')->putFile('uploads', $validated['file']);
            Storage::disk('public')->delete($category->file->path);
            $category->file->path = $path;
            $category->file->save();
        }
        return $category;
    }

    public function categoryDelete($category)
    {
        $file = $category->file;
        Storage::disk('public')->delete($file->path);
        $file->delete();
        $category->delete();
        return $category;
    }
}
