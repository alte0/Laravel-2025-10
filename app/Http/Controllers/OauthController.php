<?php

namespace App\Http\Controllers;

use App\Http\Requests\Oauth\OauthLoginRequest;
use App\Http\Requests\Oauth\OauthRegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use OpenApi\Attributes as OA;

class OauthController extends Controller
{
    #[OA\Post(
        path: '/api/v1/oauth/register',
        description: 'Registers a new user and returns an authentication token',
        summary: 'Register a new user',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: 'application/json',
                schema: new OA\Schema(
                    required: ['name', 'email', 'password'],
                    properties: [
                        new OA\Property(property: 'name', type: 'string', example: 'John Doe'),
                        new OA\Property(property: 'email', type: 'string', format: 'email', example: 'john@example.com'),
                        new OA\Property(property: 'password', type: 'string', format: 'password', example: 'secret123'),
                    ]
                )
            )
        ),
        tags: ['Authentication'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'User registered successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'token', type: 'string', example: 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9...'),
                        new OA\Property(property: 'name', type: 'string', example: 'John Doe'),
                    ]
                )
            ),
            new OA\Response(response: 422, description: 'Validation error')
        ]
    )]
    public function register(OauthRegisterRequest $request): JsonResponse
    {
        $user = User::query()->create($request->validated());
        $response = [
            'token' => $user->createToken(config('app.name'))->accessToken,
            'name' => $user->name,
        ];

        return new JsonResponse($response);
    }

    #[OA\Post(
        path: '/api/v1/oauth/login',
        description: 'Logs in a user and returns an authentication token',
        summary: 'Authenticate user and get token',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: 'application/json',
                schema: new OA\Schema(
                    required: ['email', 'password'],
                    properties: [
                        new OA\Property(property: 'email', type: 'string', format: 'email', example: 'john@example.com'),
                        new OA\Property(property: 'password', type: 'string', format: 'password', example: 'secret123'),
                    ]
                )
            )
        ),
        tags: ['Authentication'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'User authenticated successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'token', type: 'string', example: 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9...'),
                        new OA\Property(property: 'name', type: 'string', example: 'John Doe'),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Invalid credentials'),
            new OA\Response(response: 422, description: 'Validation error')
        ]
    )]
    public function login(OauthLoginRequest $request): JsonResponse
    {
        $credentials = $request->validated();

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            $response = [
                'token' => $user->createToken(config('app.name'))->accessToken,
                'name' => $user->name,
            ];

            return new JsonResponse($response);
        }

        return new JsonResponse(['error' => 'Invalid credentials'], 401);
    }
}
