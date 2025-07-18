<?php

namespace App\Actions;

use App\Models\File;
use Illuminate\Support\Facades\Storage;

abstract class BasicAction
{
    /**
     * Create a new class instance.
     */

    abstract public function getModel();

    public function index($validated)
    {
        $models = ($this->getModel())::query();

        if (isset($validated['search'])) {
            $models = $models->where($validated['search'], 'iLike', '%' . $validated['search_order'] . '%');
        }
        if (isset($validated['sort'])) {
            $models = $models->orderBy($validated['sort'], ($validated['sort_order'] ?? 'asc'));
        }
        $models = $models->paginate(perPage: $validated['perPage'], page:  $validated['page'])->withQueryString();

        return $models;
    }

    public function store($validated)
    {
        if(isset($validated['file'])) {
            $path = Storage::disk('public')->putFile('uploads', $validated['file']);
            $file = File::create(['path' => $path]);
            $validated['file'] = $file->id;
        }
        return $this->getModel()::create($validated);
    }

    public function show($id)
    {
        return $this->getModel()::findOrFail($id);
    }

    public function update($id, $validated)
    {
        if (isset($id->file_id) && isset($validated['file'])) {
            $path = Storage::disk('public')->putFile('uploads', $validated['file']);
            Storage::disk('public')->delete($id->file->path);
            $id->file->path = $path;
            $id->file->save();
        }
        $id->update($validated);

        return $id;
    }

    public function destroy($id)
    {
        if (isset($id->file_id)){
            $file = $id->file;
            Storage::disk('public')->delete($file->path);
            $file->delete();
        }
        $id->delete();

        return $id;
    }
}
