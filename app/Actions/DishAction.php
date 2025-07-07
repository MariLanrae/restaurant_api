<?php

namespace App\Actions;

use App\Models\Category;
use App\Models\Dish;
use App\Models\File;
use Illuminate\Support\Facades\Storage;

class DishAction
{
    /**
     * Create a new class instance.
     */
    public function dishIndex($validated)
    {
        $dish = Dish::query();

        if (isset($validated['sort'])) {
            $dish->orderBy($validated['sort'], ($validated['sort_order'] ?? 'asc'));
        }
        if (isset($validated['title'])) {
            $dish->where('title', 'iLike', '%' . $validated['title'] . '%');
        }
        if (isset($validated['compound'])) {
            $dish->where('compound', 'iLike', '%' . $validated['compound'] . '%');
        }
        $dish->paginate(perPage: $validated['perPage'], page:  $validated['page'])->withQueryString();

        return $dish;
    }

    public function dishStore($validated)
    {
        $category = Category::where('id', $validated['category_id'])->firstOrFail();
        $path = Storage::disk('public')->putFile('uploads', $validated['file']);

        $file = File::create(['path' => $path]);

        return Dish::create([
            'title' => $validated['title'],
            'compound' => $validated['compound'],
            'price' => $validated['price'],
            'calories' => $validated['calories'],
            'category_id' => $category->id,
            'file_id' => $file->id,
        ]);
    }

    public function dishShow($id)
    {
        return Dish::findOrFail($id);
    }

    public function dishUpdate($validated, $dish)
    {
        if ($validated['file']) {
            $path = Storage::disk('public')->putFile('uploads', $validated['file']);
            Storage::disk('public')->delete($dish->file->path);
            $dish->file->path = $path;
            $dish->file->save();
        }
        $dish->update($validated);

        return $dish;
    }

    public function dishDelete($dish)
    {
        $file = $dish->file;
        Storage::disk('public')->delete($file->path);
        $file->delete();
        $dish->delete();

        return $dish;
    }
}
