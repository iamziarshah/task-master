<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Traits\StandardApiResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use StandardApiResponse;

    protected $userRepositoryInterface;

    public function __construct(UserRepositoryInterface $userRepositoryInterface)
    {
        $this->userRepositoryInterface = $userRepositoryInterface;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = $this->userRepositoryInterface->getTop10();
        return $this->success($data, "User data fetched successfully");
    }

    public function create(UserRequest $request)
    {
        $user = $this->userRepositoryInterface->create($request);
        if ($user) {
            return $this->success($user, 'User Created Successfully', 201);
        } else {
            return $this->error('Something went wrong! Try Again.');
        }
    }

    public function update($id, UserRequest $request)
    {
        $user = $this->userRepositoryInterface->update($id, $request);
        return $this->success($user, 'User updated successfully', 201);
    }

    public function setStatus($id, UserRequest $request)
    {
        $user = $this->userRepositoryInterface->setStatus($id, $request);
        return $this->success($user, 'User status updated successfully!', 201);
    }
}
