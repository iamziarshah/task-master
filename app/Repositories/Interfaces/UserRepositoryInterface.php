<?php

namespace App\Repositories\Interfaces;

interface UserRepositoryInterface
{
    public function all();
    public function getTop10();
    public function find($id);
    public function create($request);
    public function update($id, $request);
    public function delete($id);
    public function setStatus($id, $request);
}
