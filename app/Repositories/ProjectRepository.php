<?php

namespace App\Repositories;

use App\Models\Project;
use App\Repositories\Interfaces\ProjectRepositoryInterface;

class ProjectRepository implements ProjectRepositoryInterface {
    public function all() {
        return Project::all();
    }

    public function find($id) {
        return Project::findOrFail($id);
    }

    public function create(array $data) {
        return Project::create($data);
    }

    public function update($id, array $data) {
        $item = Project::findOrFail($id);
        $item->update($data);
        return $item;
    }

    public function delete($id) {
        return Project::destroy($id);
    }
}
