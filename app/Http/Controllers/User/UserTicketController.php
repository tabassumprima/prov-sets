<?php

namespace App\Http\Controllers\User;

use App\Helpers\RouterHelper;
use App\Http\Controllers\Controller;
use App\Services\UserTicketService;
use App\Http\Requests\UserTicket\UserTicketRequest;
use App\Traits\CheckPermission;
use Illuminate\Support\Facades\Log;

class UserTicketController extends Controller
{
    use CheckPermission;

    private $userTicketService, $router, $routerHelper;

    public function __construct(UserTicketService $userTicketService)
    {
        $this->router = 'tickets.index';
        $this->userTicketService = $userTicketService;
        $this->routerHelper = new RouterHelper();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorizePermission('view-report-issue');
        $userTickets = $this->userTicketService->fetchAuthUserTickets();
        $severities = config('constant.default_severities');
    
        return view('user.user_tickets.index', compact('userTickets', 'severities'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserTicketRequest $request)
    {
        $this->authorizePermission('create-report-issue');
        $error = false;
        $message = trans('user/user_ticket.created');
        try {
            $this->userTicketService->create($request);
        } catch (\Exception $e) {
            $error = true;
            $message = $e->getMessage();
            Log::error($e);
        }
        if ($error)
            return $this->routerHelper->redirectBack($error, $message);
        return $this->routerHelper->redirect($this->router, $error, $message);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->authorizePermission('update-report-issue');
        $ticket = $this->userTicketService->fetch($id);
        $severities = config('constant.default_severities');
        
        return view('user.user_tickets.edit', compact('ticket', 'severities'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserTicketRequest $request, $id)
    {
        $this->authorizePermission('update-report-issue');
        $error = false;
        $message = trans('user/user_ticket.updated');
        try {
            $this->userTicketService->update($request, $id);
        } catch (\Exception $e) {
            $error = true;
            $message = $e->getMessage();
            Log::error($e);
        }
        if ($error)
            return $this->routerHelper->redirectBack($error, $message);
        return $this->routerHelper->redirect($this->router, $error, $message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorizePermission('delete-report-issue');
        $error = false;
        $message = trans('user/user_ticket.deleted');;
        try {
            $this->userTicketService->delete($id);
        } catch (\Exception $e) {
            $error = true;
            $message = $e->getMessage();
            Log::error($e);
        }
        if ($error)
            return $this->routerHelper->redirectBack($error, $message);
        return $this->routerHelper->redirect($this->router, $error, $message);
    }
}
