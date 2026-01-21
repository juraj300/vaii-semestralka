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
        $website = $request->value('website');
        $background_info = $request->value('background_info');

        $errors = [];
        if (empty($company)) $errors[] = "Company is required";
        if (empty($contact_name)) $errors[] = "Contact Name is required";
        if (empty($phone)) $errors[] = "Phone is required";
        
        if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email format";
        }
        
        // Phone validation (regex)
        if (!empty($phone) && !preg_match('/^[0-9+\-\s]{7,20}$/', $phone)) {
            $errors[] = "Invalid phone format (min 7 digits, plus/minus/space allowed)";
        }

        if (!empty($errors)) {
            return $this->html(['errors' => $errors], 'create');
        }

        $lead = new Lead();
        $lead->company = $company;
        $lead->contact_name = $contact_name;
        $lead->phone = $phone;
        $lead->email = $email;
        $lead->website = $website;
        $lead->background_info = $background_info;
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

        return $this->html(compact('lead'));
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
        $website = $request->value('website');
        $background_info = $request->value('background_info');
        $status = $request->value('status');

        $errors = [];
        if (empty($company)) $errors[] = "Company is required";
        if (empty($contact_name)) $errors[] = "Contact Name is required";
        
        if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email format";
        }

        if (!empty($phone) && !preg_match('/^[0-9+\-\s]{7,20}$/', $phone)) {
            $errors[] = "Invalid phone format";
        }
        if (!empty($errors)) {
             return $this->html(['lead' => $lead, 'errors' => $errors], 'edit');
        }

        $lead->company = $company;
        $lead->contact_name = $contact_name;
        $lead->phone = $phone;
        $lead->email = $email;
        $lead->website = $website;
        $lead->background_info = $background_info;
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

    public function import(Request $request): Response
    {
        $errors = [];
        $count = 0;

        if ($request->isPost()) {
            if (isset($_FILES['csv']) && $_FILES['csv']['error'] === UPLOAD_ERR_OK) {
                $file = $_FILES['csv'];
                $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                if (strtolower($ext) !== 'csv') {
                    $errors[] = "Only CSV files are allowed.";
                } else {
                    $handle = fopen($file['tmp_name'], 'r');
                    $headers = fgetcsv($handle);
                    $expected = ['company', 'contact_name', 'phone', 'email', 'website', 'background_info'];
                    
                    if (!$headers || count(array_intersect($expected, $headers)) < 3) {
                        $errors[] = "CSV must have headers: company, contact_name, phone. Optional: email, website, background_info.";
                    } else {
                        $headerMap = array_flip($headers);
                        while (($row = fgetcsv($handle)) !== false) {
                            if (count($row) < 3) continue;
                            
                            $lead = new \App\Models\Lead();
                            $lead->company = $row[$headerMap['company']] ?? '';
                            $lead->contact_name = $row[$headerMap['contact_name']] ?? '';
                            $lead->phone = $row[$headerMap['phone']] ?? '';
                            $lead->email = $row[$headerMap['email']] ?? null;
                            $lead->website = $row[$headerMap['website']] ?? null;
                            $lead->background_info = $row[$headerMap['background_info']] ?? null;
                            $lead->owner_id = $this->user->getIdentity()->id;
                            $lead->status = \App\Models\Lead::STATUS_NEW;

                            if (!empty($lead->company) && !empty($lead->contact_name)) {
                                $lead->save();
                                $count++;
                            }
                        }
                        fclose($handle);
                        if ($count > 0) {
                            return $this->redirect($this->url('lead.index', ['message' => "$count leads imported."]));
                        }
                        $errors[] = "No valid leads found.";
                    }
                }
            } else {
                $errors[] = "Please select a file.";
            }
        }
        return $this->html(compact('errors'));
    }

    public function search(Request $request): Response
    {
        $q = $request->value('q');
        $where = "company LIKE ? OR contact_name LIKE ?";
        $params = ["%$q%", "%$q%"];
        
        if (!$this->user->getIdentity()->isAdmin()) {
            $where = "($where) AND owner_id = ?";
            $params[] = $this->user->getIdentity()->id;
        }

        $leads = \App\Models\Lead::getAll($where, $params);

        return $this->html(['leads' => $leads, 'no_layout' => true], 'index_rows');
    }
}
