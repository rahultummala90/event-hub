<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EventResource;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class EventController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth:sanctum")->except(["index", "show"]);
        $this->authorizeResource(Event::class, "event");
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = Event::query();
        $relations = ['user', 'attendees', 'attendees.user'];

        foreach($relations as $relation) {
            $query->when(
                $this->shouldIncludeRelation($relation),
                fn($query) => $query->with($relation)
            );
        }

        return EventResource::collection(
            Event::with('user')->paginate()
            // $query->latest()->paginate()
        );
    }

    protected function shouldIncludeRelation(string $relation): bool
    {
        $include = request()->query('include');

        if (! $include) {
            return false;
        }

        $relations = array_map('trim', explode(',', $include));
        
        return in_array($relation, $relations);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'=> 'required|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time'

        ]);

        $event = Event::create([
            ...$validated, 
            'user_id' => $request->user()->id
        ]);

        return new EventResource($event);
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        $event->load('user', 'attendees');
        return new EventResource($event);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event /*string $id*/)
    {
        // if(Gate::denies('update-event', $event)) {
        //     abort(403,'You are not authorized');
        // }

        // $this->authorize('update-event', $event);

        $validated = $request->validate([
            'name'=> 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'sometimes|date',
            'end_time' => 'sometimes|date|after:start_time'

        ]);
        $event->update($validated);

        return new EventResource($event);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(/*string $id*/ Event $event)
    {
        $event->delete();

        // return response()->json([
        //     'message'=> 'Event deleted successfully'
        // ]);

        return response(204);
    }
}
