<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Repositories\AuthRepository;
use App\Traits\StandardApiResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    use StandardApiResponse;

    protected $authRepository;

    public function __construct(AuthRepository $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    public function store(Request $request)
    {
        $data = $this->authRepository->login($request);

        if ($data) {
            return $this->success($data, "User Logged In Successfully");
        } else {
            return $this->error('Incorrect email or password.');
        }
    }
}
