<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\ContactEditRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class DashboardApiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        $teamId = $user->current_team_id;

        $data = [
            'total_contacts' => Contact::where('team_id', $teamId)
                ->where(function ($q) {
                    $q->whereNull('approval_status')->orWhere('approval_status', '!=', 'pending');
                })
                ->count(),
            'searches_used' => $user->searches_used,
            'search_quota' => $user->search_quota,
        ];

        if (Gate::allows('approve-contacts')) {
            $data['pending_contacts_count'] = Contact::where('approval_status', 'pending')
                ->when(! $user->isSuperAdmin(), fn ($q) => $q->where('team_id', $teamId))
                ->count();
            $data['pending_edits_count'] = ContactEditRequest::where('status', 'pending')
                ->when(! $user->isSuperAdmin(), fn ($q) => $q->where('team_id', $teamId))
                ->count();
        }

        if ($user->isManager()) {
            $data['my_pending_contacts_count'] = Contact::where('team_id', $teamId)
                ->where('owner_id', $user->id)
                ->where('approval_status', 'pending')
                ->count();
        }

        return response()->json($data);
    }
}
