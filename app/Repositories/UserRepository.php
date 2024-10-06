<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\DB;

class UserRepository implements UserRepositoryInterface
{
    public function all()
    {
        return User::all();
    }

    public function getTop10()
    {
        return User::orderBy('id', 'DESC')->get()->take(10);
    }

    public function find($id)
    {
        return User::findOrFail($id);
    }

    public function create($request)
    {
        $user = new User();
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->password = $request->password;

        DB::transaction(function () use ($user) {
            $user->save();
        });

        return $user;
    }

    public function update($id, $request)
    {
        $user = User::findOrFail($id);

        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->password = $request->password;

        DB::transaction(function () use ($user) {
            $user->save();
        });

        return $user;
    }

    public function delete($id)
    {
        return User::destroy($id);
    }

    public function setStatus($id, $request)
    {
        $user = User::findOrFail($id);
        $user->status = $request->status;

        DB::transaction(function () use ($user) {
            $user->save();
        });

        return $user;
    }
}
