<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface {
    public function all() {
        return User::all();
    }

    public function find($id) {
        return User::findOrFail($id);
    }

    public function create(array $data) {
        return User::create($data);
    }

    public function update($id, array $data) {
        $item = User::findOrFail($id);
        $item->update($data);
        return $item;
    }

    public function delete($id) {
        return User::destroy($id);
    }
}
