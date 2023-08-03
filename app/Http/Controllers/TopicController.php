<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Topic;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class TopicController extends Controller
{
    protected $perPageDefault = 10;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->query('perPage', $this->perPageDefault);
        $sortField = $request->query('sortField', 'name');
        $sortOrder = $request->query('sortOrder', 'asc');
        $search = $request->query('search', '');

        $query = Topic::query();

        if ($search) {
            $query->where('name', 'LIKE', "%$search%");
        }

        $sortableFields = ['id', 'name', 'created_at'];
        if (!in_array($sortField, $sortableFields)) {
            $sortField = 'name';
        }

        $possiblePerPage = [5, 10, 25];
        if (!in_array($perPage, $possiblePerPage)) {
            $perPage = $this->perPageDefault;
        }

        $query->orderBy($sortField, $sortOrder);
        $topics = $query->with([
            'course:id,name',
        ])->paginate($perPage);

        return response()->json($topics, 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $search = $request->input('search');
        $query = Course::query();

        if ($search) {
            Log::info($search);
            $query->where('name', 'LIKE', "%$search%");
        }

        $course = $query->take(10)->get();

        return response()->json(['course' => $course], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $topic_name = $request->input('name');
            $course_id = $request->input('course_id');
            $description = $request->input('description');

            if ($topic_name == '' || !Course::find($course_id))
                throw new BadRequestException;

            $topic = Topic::create([
                "name" => $topic_name,
                "course_id" => $course_id,
                "description" => $description,
            ]);

            return response()->json($topic, 200);
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
            $topic = Topic::with([
                'course:id,name'
            ])->find($id);
            if (!$topic) throw new BadRequestException;
            return response()->json($topic, 200);
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
            $topic = Topic::find($id);
            $topic->name = $request->input('name');
            $topic->course_id = $request->input('course_id');
            $topic->description = $request->input('description');
            $topic->save();
            return response()->json($topic, 200);
        } catch (Exception) {
            return response()->json('Server Error', 500);
        }
    }

    /**
     * Soft delete the specified resource from storage.
     */
    public function delete(Request $request, string $id)
    {
        try {
            $topic = Topic::find($id);
            if (!$topic) throw new BadRequestException;
            $topic->delete();
            return response()->json("Successfully deleted topic with the id " . $id, 200);
        } catch (BadRequestException) {
            return response()->json("Invalid Id", 400);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }
}
