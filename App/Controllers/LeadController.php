<?php

namespace App\Controllers;

use App\Models\Lead;
use App\Core\SecureController;
use Framework\Http\Request;
use Framework\Http\Responses\Response;

class LeadController extends SecureController
{
    public function authorize(Request $request, string $action): bool
    {
        // 1. Run parent checks (CSRF)
        if (!parent::authorize($request, $action)) {
            return false;
        }

        // Only logged in users can manage leads
        return $this->user->isLoggedIn();
    }

    public function index(Request $request): Response
    {
        // If Admin, show all. If Agent, show own?
        // Requirement says "Agent... prezeranie a filtrovanie svojich leadov".
        // Admin sees all.
        
        $where = null;
        $params = [];

        if (!$this->user->getIdentity()->isAdmin()) {
            $where = "owner_id = ?";
            $params[] = $this->user->getIdentity()->id;
        }

        $leads = Lead::getAll($where, $params, "created_at DESC");
        return $this->html(compact('leads'));
    }

    public function create(Request $request): Response
    {
        return $this->html();
    }

    public function store(Request $request): Response
    {
        $company = $request->value('company');
        $contact_name = $request->value('contact_name');
        $phone = $request->value('phone');
        $email = $request->value('email');

        $errors = [];
        if (empty($company)) $errors[] = "Company is required";
        if (empty($contact_name)) $errors[] = "Contact Name is required";
        if (empty($phone)) $errors[] = "Phone is required";
        // Phone validation (regex) - simplistic for now
        if (!preg_match('/^[0-9+\-\s]+$/', $phone)) $errors[] = "Invalid phone format";

        if (!empty($errors)) {
            return $this->view('lead.create', ['errors' => $errors]);
        }

        $lead = new Lead();
        $lead->company = $company;
        $lead->contact_name = $contact_name;
        $lead->phone = $phone;
        $lead->email = $email;
        $lead->owner_id = $this->user->getIdentity()->id;
        $lead->status = Lead::STATUS_NEW;
        $lead->save();

        return $this->redirect($this->url('lead.index'));
    }

    public function edit(Request $request): Response
    {
        $id = $request->value('id');
        $lead = Lead::getOne($id);

        if (!$lead || (!$this->user->getIdentity()->isAdmin() && $lead->owner_id !== $this->user->getIdentity()->id)) {
            // Unauthorized or not found
            return $this->redirect($this->url('lead.index'));
        }

        $attachments = \App\Models\Attachment::getAll("lead_id = ?", [$lead->id]);

        return $this->html(compact('lead', 'attachments'));
    }

    public function update(Request $request): Response
    {
        $id = $request->value('id');
        $lead = Lead::getOne($id);

        if (!$lead || (!$this->user->getIdentity()->isAdmin() && $lead->owner_id !== $this->user->getIdentity()->id)) {
             return $this->redirect($this->url('lead.index'));
        }

        $company = $request->value('company');
        $contact_name = $request->value('contact_name');
        $phone = $request->value('phone');
        $email = $request->value('email');
        $status = $request->value('status');

        $errors = [];
        if (empty($company)) $errors[] = "Company is required";
        if (empty($contact_name)) $errors[] = "Contact Name is required";
        
        if (!empty($errors)) {
             return $this->view('lead.edit', ['lead' => $lead, 'errors' => $errors]);
        }

        $lead->company = $company;
        $lead->contact_name = $contact_name;
        $lead->phone = $phone;
        $lead->email = $email;
        $lead->status = $status;
        $lead->save();

        return $this->redirect($this->url('lead.index'));
    }

    public function delete(Request $request): Response
    {
        $id = $request->value('id');
        $lead = Lead::getOne($id);

        if ($lead && ($this->user->getIdentity()->isAdmin() || $lead->owner_id === $this->user->getIdentity()->id)) {
            $lead->delete();
        }

        return $this->redirect($this->url('lead.index'));
    }

    public function upload(Request $request): Response
    {
        $id = $request->value('id');
        $lead = Lead::getOne($id);

        if (!$lead || (!$this->user->getIdentity()->isAdmin() && $lead->owner_id !== $this->user->getIdentity()->id)) {
             return $this->redirect($this->url('lead.index'));
        }

        if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['attachment'];
            $uploadDir = __DIR__ . '/../../public/uploads/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            
            $filename = uniqid() . '_' . basename($file['name']);
            $targetPath = $uploadDir . $filename;
            
            if (move_uploaded_file($file['tmp_name'], $targetPath)) {
                $attachment = new \App\Models\Attachment();
                $attachment->lead_id = $lead->id;
                $attachment->filename = $file['name']; // Original name
                $attachment->path = $filename; // Stored name
                $attachment->save();
            }
        }

        return $this->redirect($this->url('lead.edit', ['id' => $lead->id]));
    }

    public function deleteAttachment(Request $request): Response
    {
        $id = $request->value('id'); // Attachment ID
        $attachment = \App\Models\Attachment::getOne($id);

        if ($attachment) {
            $lead = Lead::getOne($attachment->lead_id);
            if ($lead && ($this->user->getIdentity()->isAdmin() || $lead->owner_id === $this->user->getIdentity()->id)) {
                $filePath = __DIR__ . '/../../public/uploads/' . $attachment->path;
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
                $attachment->delete();
                return $this->redirect($this->url('lead.edit', ['id' => $lead->id]));
            }
        }
        return $this->redirect($this->url('lead.index'));
    }
}
