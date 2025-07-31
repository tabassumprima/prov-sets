<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserTicket\Request;
use App\Services\UserTicketService;
use App\Helpers\RouterHelper;
use Illuminate\Support\Facades\Log;

class UserTicketController extends Controller
{
    private $userTicketService, $router, $routerHelper;

    public function __construct(UserTicketService $userTicketService)
    {
        $this->router = 'user_ticket.index';
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
        $userTickets = $this->userTicketService->fetchAll();
        return view('admin.tickets.index', compact('userTickets'));
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
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $ticket = $this->userTicketService->fetch($id);
        $severities = config('constant.default_severities');

        return view('admin.tickets.show', compact('ticket', 'severities'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $error = false;
        $message = '';
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

    public function statusUpdate($id)
    {
        $error = false;
        $message = trans('admin/user_ticket.status_updated');
        try {
            $this->userTicketService->updateStatus($id);
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
