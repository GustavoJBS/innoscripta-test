<?php

namespace App\Http\Controllers;

use Illuminate\Http\{JsonResponse, Response};

class PreferenceController extends Controller
{
    public function index(): Response|JsonResponse
    {
        return response()->json([
            'status'     => true,
            'message'    => 'User Preferences Fetched Successfully',
            'preference' => auth()->user()->preference,
        ], Response::HTTP_OK);
    }

    public function save(): Response|JsonResponse
    {
        $this->validate(request(), [
            'languages'    => ['array'],
            'languages.*'  => ['string'],
            'sources'      => ['array'],
            'sources.*'    => ['numeric', 'exists:sources,id'],
            'categories'   => ['array'],
            'categories.*' => ['numeric', 'exists:categories,id'],
        ]);

        request()->user()
            ->preference()
            ->update([
                'languages'  => request('languages', []),
                'sources'    => request('sources', []),
                'categories' => request('categories', []),
            ]);

        return response()->json([
            'status'  => true,
            'message' => 'User Preferences saved Successfully',
            'user'    => auth()->user(),
        ], Response::HTTP_CREATED);
    }
}
