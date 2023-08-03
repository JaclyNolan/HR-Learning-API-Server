<?php

namespace App\Http\Controllers;

use App\Models\CourseCategory;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class CourseCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */

     protected $perPageDefault = 10;

    public function index(Request $request)
    {
        //
        $perPage = $request->query('perPage', $this->perPageDefault);
        $sortField = $request->query('sortField', 'name');
        $sortOrder = $request->query('sortOrder', 'asc');
        $search = $request->query('search', '');

        $query = CourseCategory::query();

        if ($search) {
            $query->where('name', 'LIKE', "%$search%");
        }

        // Add the 'name' field of the related category to the sortable fields
        $sortableFields = ['id', 'name', 'created_at'];
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

        $courses = $query->orderBy($sortField, $sortOrder)->paginate($perPage);
        // $courses->makeHidden(['created_at', 'updated_at', 'deleted_at']);
        return response()->json($courses, 200);
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
            $courseCategory_name = $request->input('name');
            $description = $request->input('description');

            CourseCategory::create([
                "name" => $courseCategory_name,
                "description" => $description,
            ]);

            return response()->json('Successfully added ' . $courseCategory_name . ' course category', 200);
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

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(Request $request, string $id)
    {
        try {
            $courseCategory = CourseCategory::find($id);
            if (!$courseCategory) throw new BadRequestException;
            $courseCategory->delete();
            return response()->json("Successfully deleted course category with the id " . $id, 200);
        } catch (BadRequestException) {
            return response()->json("Invalid Id", 400);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }
}
