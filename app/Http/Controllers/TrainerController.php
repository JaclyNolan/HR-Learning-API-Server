<?php

namespace App\Http\Controllers;

use App\Models\Trainer;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class TrainerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected $perPageDefault = 10;
    public function index(Request $request)
    {
        // try {
        $perPage = $request->query('perPage', $this->perPageDefault);
        $sortField = $request->query('sortField', 'name');
        $sortOrder = $request->query('sortOrder', 'asc');
        $search = $request->query('search', '');

        $query = Trainer::query();

        if ($search) {
            $query->where('name', 'LIKE', "%$search%");
        }

        // Add the 'name' field of the related category to the sortable fields
        $sortableFields = ['id', 'name', 'type', 'created_at'];
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

        $trainers = $query->orderBy($sortField, $sortOrder)->paginate($perPage);

        return response()->json($trainers, 200);
    }

    public function takeTen(Request $request)
    {
        $search = $request->input('search');
        $query = Trainer::query();

        if ($search) {
            $query->where('name', 'LIKE', "%$search%");
        }

        $trainers = $query->take(10)->get();
        return response()->json($trainers, 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $name = $request->input('name');
            $type = $request->input('type');
            $education = $request->input('education');
            $working_place = $request->input('working_place');
            $phone_number = $request->input('phone_number');
            $email = $request->input('email');

            $trainer = Trainer::create([
                "name" => $name,
                "type" => $type,
                "education" => $education,
                "working_place" => $working_place,
                "phone_number" => $phone_number,
                "email" => $email,
            ]);

            return response()->json($trainer, 200);
        } catch (BadRequestException) {
            return response()->json('Invalid name or id', 400);
        } catch (Exception) {
            return response()->json('Server Error', 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    public function profileForTrainer(Request $request)
    {
        $user = $request->user()->load(['trainer']);

        $trainer = $user->trainer;
        $trainer->makeHidden('created_at', 'updated_at', 'deleted_at');
        return response()->json($trainer, 200);
    }

    public function updateProfileForTrainer(Request $request)
    {
        $user = $request->user()->load(['trainer']);

        $trainer = $user->trainer;
        $trainer->name = $request->input('name');
        $trainer->type = $request->input('type');
        $trainer->education = $request->input('education');
        $trainer->working_place = $request->input('working_place');
        $trainer->phone_number = $request->input('phone_number');
        $trainer->email = $request->input('email');
        $trainer->save();

        return response()->json($trainer, 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, string $id)
    {
        try {
            $trainer = Trainer::find($id);
            if (!$trainer) throw new BadRequestException;
            return response()->json($trainer, 200);
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
        try {
            $trainer = Trainer::find($id);
            $trainer->name = $request->input('name');
            $trainer->type = $request->input('type');
            $trainer->education = $request->input('education');
            $trainer->working_place = $request->input('working_place');
            $trainer->phone_number = $request->input('phone_number');
            $trainer->email = $request->input('email');
            $trainer->save();
            return response()->json($trainer, 200);
        } catch (Exception) {
            return response()->json('Server Error', 500);
        }
    }

    public function delete(Request $request, string $id)
    {
        try {
            $trainer = Trainer::find($id);
            if (!$trainer) throw new BadRequestException;
            $trainer->delete();
            return response()->json("Successfully deleted trainer with the id " . $id, 200);
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
