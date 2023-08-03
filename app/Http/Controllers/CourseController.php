<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseCategory;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class CourseController extends Controller
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

        $query = Course::query();

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

        $query->orderBy($sortField, $sortOrder);
        /** @var Collection $courses */
        $courses = $query->with([
            'courseCategory:id,name',
        ])->paginate($perPage);
        // $courses->makeHidden(['created_at', 'updated_at', 'deleted_at']);
        return response()->json($courses, 200);
        // } catch (Exception) {
        //     return response()->json([], 500);
        // }
    }

    public function indexForTrainer(Request $request)
    {
        // try {
        $sortField = $request->query('sortField', 'name');
        $sortOrder = $request->query('sortOrder', 'asc');
        $search = $request->query('search', '');
        /** @var User $user */
        $user = $request->user();

        $query = Course::query();

        //To-do: Course must belong to trainer
        $query->whereHas('topics', function ($q) use ($user) {
            $q->whereHas('trainers', function ($q) use ($user) {
                $q->where('trainers.id', $user->trainer_id);
            });
        });

        if ($search) {
            $query->where('name', 'LIKE', "%$search%");
        }

        // Add the 'name' field of the related category to the sortable fields
        $sortableFields = ['id', 'name', 'created_at'];
        if (!in_array($sortField, $sortableFields)) {
            // Default to 'name' if the provided sortField is not in the sortable fields
            $sortField = 'name';
        }
        $query->orderBy($sortField, $sortOrder);
        /** @var Collection $courses */
        $courses = $query->with([
            'courseCategory:id,name',
            'topics' => function ($q) use ($user) {
                $q->select('id', 'name', 'course_id', 'description')
                    ->whereHas('trainers', function ($q) use ($user) {
                        $q->where('trainers.id', $user->trainer_id);
                    });
            }
        ])->get();
        $courses->makeHidden('created_at', 'updated_at', 'deleted_at');
        // $courses->makeHidden(['created_at', 'updated_at', 'deleted_at']);
        return response()->json($courses, 200);
        // } catch (Exception) {
        //     return response()->json([], 500);
        // }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $search = $request->input('search');
        $query = CourseCategory::query();

        if ($search) {
            Log::info($search);
            $query->where('name', 'LIKE', "%$search%");
        }

        $courseCategory = $query->take(10)->get();

        return response()->json(['course_categories' => $courseCategory], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $course_name = $request->input('name');
            $course_category_id = $request->input('course_category_id');
            $description = $request->input('description');

            if ($course_name == '' || !CourseCategory::find($course_category_id))
                throw new BadRequestException;

            $course = Course::create([
                "name" => $course_name,
                "course_category_id" => $course_category_id,
                "description" => $description,
            ]);

            return response()->json($course, 200);
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
    public function edit(Request $request, string $id)
    {
        try {
            $course = Course::with([
                'courseCategory:id,name'
            ])->find($id);
            if (!$course) throw new BadRequestException;
            return response()->json($course, 200);
        } catch (BadRequestException) {
            return response()->json('Invalid id', 400);
        } catch (Exception) {
            return response()->json('Server Error', 500);
        }
    }

    public function editTrainee(Request $request, string $id)
    {
        $course = Course::with([
            'trainees'
        ])->find($id);

        return response()->json($course, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $course = Course::find($id);
            $course->name = $request->input('name');
            $course->course_category_id = $request->input('course_category_id');
            $course->description = $request->input('description');
            $course->save();
            return response()->json($course, 200);
        } catch (Exception) {
            return response()->json('Server Error', 500);
        }
    }

    public function updateTrainee(Request $request, string $id)
    {
        $newTraineeIds = $request->input('trainee_ids');

        $course = Course::with([
            'trainees'
        ])->find($id);
        $oldTrainees = $course->trainees;

        //To-do: overwrite the new trainee with the old one
        foreach ($oldTrainees as $oldTrainee) {
            $course->trainees()->detach($oldTrainee->id);
        }

        foreach ($newTraineeIds as $newTraineeId) {
            $course->trainees()->attach($newTraineeId);
        }

        $course = $course->fresh(['trainees']);

        return response()->json($course, 200);
    }

    /**
     * Soft delete the specified resource from storage.
     */
    public function delete(Request $request, string $id)
    {
        try {
            $course = Course::find($id);
            if (!$course) throw new BadRequestException;
            /** @var Course $course */
            $course->delete();
            return response()->json("Successfully deleted course with the id " . $id, 200);
        } catch (BadRequestException) {
            return response()->json("Invalid Id", 400);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }
}
