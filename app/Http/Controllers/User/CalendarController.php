<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\EventService;
use App\Traits\CheckPermission;

class CalendarController extends Controller
{
    use CheckPermission;

    private $eventService;

    public function __construct(EventService $eventService)
    {
        $this->eventService  = $eventService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorizePermission('view-calender');
        $calendars = $this->eventService->getUniqueEventType();
        return view('user.calendar',compact('calendars'));
    }

    public function show()
    {
        $events = $this->eventService->fetchAll();
        return response()->json($events);
    }
}
