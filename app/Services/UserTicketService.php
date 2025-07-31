<?php

namespace App\Services;

use App\Models\UserTicket;
use App\Helpers\CustomHelper;
use Illuminate\Support\Facades\Auth;

class UserTicketService{

    protected $model;
    public function __construct()
    {
        $this->model = new UserTicket();
    }

    public function create($request)
    {
        $user = Auth::user();

        $organizationService = new OrganizationService();
        $organization_id = $organizationService->getAuthOrganizationId();

         // Merge the organization_id into the request data
        $request->merge(['organization_id' => $organization_id]);

        // dd($request->all());
        return $user->tickets()->create($request->all());
    }

    public function update($request, $id)
    {
        $organization = $this->fetch($id);
        return $organization->fill($request->all())->save();
    }

    public function delete($id)
    {
        $organization = $this->fetch($id);
        return $organization->delete();
    }

    public function fetch($id)
    {
        return $this->model->findOrFail(CustomHelper::decode($id));
    }

    public function fetchAll()
    {
        return $this->model->all();
    }

    public function fetchAuthUserTickets()
    {
        $user = auth()->user();
        return $user->tickets;
    }

    public function updateStatus($id)
    {
        $ticket = $this->fetch($id);
        $ticket->is_resolved = 1;
        $ticket->save();
        return $ticket;
    }
}
