<?php

namespace App\Controllers;

use App\Models\Call;
use App\Models\Lead;
use App\Models\Script;
use Framework\Core\BaseController;
use Framework\Http\Request;
use Framework\Http\Responses\JsonResponse;
use Framework\Http\Responses\Response;

class CallController extends BaseController
{
    public function index(Request $request): Response
    {
        return $this->redirect($this->url('lead.index'));
    }

    public function authorize(Request $request, string $action): bool
    {
        return $this->user->isLoggedIn();
    }

    public function room(Request $request): Response
    {
        $id = $request->value('id');
        $lead = Lead::getOne($id);
        
        // Security check: only own leads or admin
        if (!$lead || (!$this->user->getIdentity()->isAdmin() && $lead->owner_id !== $this->user->getIdentity()->id)) {
            return $this->redirect($this->url('lead.index'));
        }

        }

        // Fetch all available scripts for dropdown
        $scripts = Script::getAll();
        $script = Script::getDefault();
        
        // Replace variables in default script
        if ($script) {
            $script->body = str_replace(
                ['{{contact_name}}', '{{company}}', '{{agent_name}}'],
                [$lead->contact_name, $lead->company, $this->user->getIdentity()->getName()],
                $script->body
            );
        }

        return $this->html(compact('lead', 'script', 'scripts'));
    }

    public function logCall(Request $request): Response
    {
        $leadId = $request->value('lead_id');
        $outcome = $request->value('outcome');
        $notes = $request->value('notes');

        // Basic server validation
        if (!$leadId || !$outcome) {
            return new JsonResponse(['status' => 'error', 'message' => 'Missing required fields']);
        }

        $lead = Lead::getOne($leadId);
        if (!$lead) {
            return new JsonResponse(['status' => 'error', 'message' => 'Lead not found']);
        }

        // Save Call
        $call = new Call();
        $call->lead_id = $leadId;
        $call->user_id = $this->user->getIdentity()->id;
        $call->outcome = $outcome;
        $call->notes = $notes;
        $call->save();

        // Update Lead Status
        $lead->status = $outcome === 'closed_won' ? Lead::STATUS_CLOSED_WON : 
                       ($outcome === 'closed_lost' ? Lead::STATUS_CLOSED_LOST : Lead::STATUS_CONTACTED);
        $lead->save();

        return new JsonResponse(['status' => 'success']);
    }

    public function nextLead(Request $request): Response
    {
        // Find next 'new' lead for this user
        $where = "status = ? AND id != ?";
        $params = [Lead::STATUS_NEW, $request->value('current_id') ?? 0];

        if (!$this->user->getIdentity()->isAdmin()) {
            $where .= " AND owner_id = ?";
            $params[] = $this->user->getIdentity()->id;
        }

        $leads = Lead::getAll($where, $params, "id ASC", 1);
        
        if (empty($leads)) {
            return new JsonResponse(['status' => 'empty']);
        }

        $nextLead = $leads[0];
        
        // Prepare Script
        $script = Script::getDefault();
        $scriptBody = $script ? str_replace(
            ['{{contact_name}}', '{{company}}', '{{agent_name}}'],
            [$nextLead->contact_name, $nextLead->company, $this->user->getIdentity()->getName()],
            $script->body
        ) : "No script available.";

        return new JsonResponse([
            'status' => 'found',
            'lead' => $nextLead->jsonSerialize(),
            'script_body' => $scriptBody
        ]);
    }
}
