<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class UserController extends Controller
{

    protected $perPageDefault = 10;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // try {
        $perPage = $request->query('perPage', $this->perPageDefault);
        $sortField = $request->query('sortField', 'name');
        $sortOrder = $request->query('sortOrder', 'asc');
        $search = $request->query('search', '');

        $query = User::query();

        if ($search) {
            $query->where('name', 'LIKE', "%$search%");
        }

        // Add the 'name' field of the related category to the sortable fields
        $sortableFields = ['id', 'name', 'email', 'created_at'];
        if (!in_array($sortField, $sortableFields)) {
            // Default to 'name' if the provided sortField is not in the sortable fields
            $sortField = 'name';
        }

        // Add the 'name' field of the related category to the sortable fields
        $possiblePerPage = [5, 10, 25];
        if (!in_array($perPage, $possiblePerPage)) {
            // Default to 'name' if the provided sortField is not in the sortable fields
            $perPage = $this->perPageDefault;
        }

        $query->orderBy($sortField, $sortOrder);
        /** @var Collection $users */
        $users = $query->with([
            'trainer:id,name',
        ])->paginate($perPage);
        // $users->makeHidden(['created_at', 'updated_at', 'deleted_at']);
        return response()->json($users, 200);
        // } catch (Exception) {
        //     return response()->json([], 500);
        // }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        //
        $search = $request->input('search');
        $query = Role::query();

        if ($search) {
            Log::info($search);
            $query->where('name', 'LIKE', "%$search%");
        }

        $role = $query->take(10)->get();

        return response()->json(['roles' => $role], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $user_name = $request->input('name');
            $user_email = $request->input('email');
            //$role_id = $request->input('role_id');
            $password = $request->input('password');

            // Kiểm tra nếu role_id được cung cấp tồn tại trong bảng roles
            // $role = Role::find($role_id);
            // if (!$role) {
            //     throw new BadRequestException('role_id không hợp lệ');
            // }

            $user = User::create([
                "name" => $user_name,
                "email" => $user_email,
                //"role_id" => $role_id,
                "password" => $password,
            ]);

            return response()->json($user, 200);
        } catch (BadRequestException $e) {
            return response()->json($e->getMessage(), 400);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }



    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $user = User::find($id);
            if (!$user) throw new BadRequestException;
            return response()->json($user, 200);
        } catch (BadRequestException) {
            return response()->json('Invalid id', 400);
        } catch (Exception) {
            return response()->json('Server Error', 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        try {
            $user = User::find($id);
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->role_id = $request->input('role_id');
            $user->save();
            return response()->json($user, 200);
        } catch (Exception) {
            return response()->json('Server Error', 500);
        }
    }

    public function delete(Request $request, string $id)
    {
        try {
            $user = User::find($id);
            if (!$user) throw new BadRequestException;

            $user->delete();
            return response()->json("Successfully deleted user with the id " . $id, 200);
        } catch (BadRequestException) {
            return response()->json("Invalid Id", 400);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
