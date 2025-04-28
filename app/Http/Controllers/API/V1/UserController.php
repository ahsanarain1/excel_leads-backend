<?php

namespace App\Http\Controllers\API\V1;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserCollection;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10); // Number of items per page, default is 10
        // $users = User::paginate($perPage);
        $users = User::all();
        return new UserCollection($users);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,seller', // Ensure valid role selection
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Assign role
        $user->assignRole($request->role);

        return response()->json([
            'success' => true,
            'message' => 'Agent registered successfully!',
            'user' => new UserResource($user)
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return new UserResource(User::findOrFail($user));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        $user->delete();

        return response()->json(['success'=> true,'message' => 'User deleted successfully']);
    }
    public function getUser(Request $request)
    {
        $user = $request->user();
        return new UserResource($user);
    }

    /**
     * Get all sellers.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSellers()
    {
        // Retrieve users with the 'seller' role
        $sellers = User::role('seller')->get();
        $collection = new UserCollection($sellers);
        return response()->json([
            'success' => true,
            'data' => $collection
        ]);
    }
}
