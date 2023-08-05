<?php

namespace App\Http\Controllers;

use App\Models\Trainee;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class TraineeController extends Controller
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

        $query = Trainee::query();

        if ($search) {
            $query->where('name', 'LIKE', "%$search%");
        }

        // Add the 'name' field of the related category to the sortable fields
        $sortableFields = ['id', 'name', 'age', 'date_of_birth', 'toeic_score', 'created_at'];
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
        /** @var Collection $courses */
        $courses = $query->paginate($perPage);
        // $courses->makeHidden(['created_at', 'updated_at', 'deleted_at']);
        return response()->json($courses, 200);
        // } catch (Exception) {
        //     return response()->json([], 500);
        // }
    }

    public function takeTen(Request $request)
    {
        $search = $request->input('search');
        $query = Trainee::query();

        if ($search) {
            $query->where('name', 'LIKE', "%$search%");
        }

        $trainees = $query->take(10)->get();
        return response()->json($trainees, 200);
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
        //
        try {
            $name = $request->input('name');
            $account = $request->input('account');
            $age = $request->input('age');
            $date_of_birth = $request->input('date_of_birth');
            $education = $request->input('education');
            $main_programming_language = $request->input('main_programming_language');
            $toeic_score = $request->input('toeic_score');
            $department = $request->input('department');
            $location = $request->input('location');


            $courseCategory = Trainee::create([
                "name" => $name,
                "account" => $account,
                "age" => $age,
                "date_of_birth" => $date_of_birth,
                "education" => $education,
                "main_programming_language" => $main_programming_language,
                "department" => $department,
                "toeic_score" => $toeic_score,
                "location" => $location,
            ]);

            return response()->json($courseCategory, 200);
        } catch (BadRequestException) {
            return response()->json('Invalid name or id', 400);
        } catch (Exception $e) {
            // return response()->json('Server Error', 500);
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
    public function edit(Request $request, string $id)
    {
        try {
            $trainee = Trainee::find($id);
            if (!$trainee) throw new BadRequestException;
            return response()->json($trainee, 200);
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
            $trainee = Trainee::find($id);
            $trainee->name = $request->input('name');
            $trainee->account = $request->input('account');
            $trainee->age = $request->input('age');
            $trainee->date_of_birth = $request->input('date_of_birth');
            $trainee->education = $request->input('education');
            $trainee->main_programming_language = $request->input('main_programming_language');
            $trainee->department = $request->input('department');
            $trainee->toeic_score = $request->input('toeic_score');
            $trainee->location = $request->input('location');

            $trainee->save();
            return response()->json($trainee, 200);
        } catch (Exception) {
            return response()->json('Server Error', 500);
        }
    }

    public function delete(Request $request, string $id)
    {
        try {
            $trainee = Trainee::find($id);
            if (!$trainee) throw new BadRequestException();
            $trainee->delete();
            return response()->json("Successfully deleted trainee with the id " . $id, 200);
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
