<?php

namespace App\Controllers;

use App\Models\Script;
use Framework\Core\BaseController;
use Framework\Http\Request;
use Framework\Http\Responses\Response;

class ScriptController extends BaseController
{
    public function authorize(Request $request, string $action): bool
    {
        // Only Admin can manage scripts
        return $this->user->getIdentity()->isAdmin();
    }

    public function index(Request $request): Response
    {
        $scripts = Script::getAll(null, [], "id DESC");
        return $this->html(compact('scripts'));
    }

    public function create(Request $request): Response
    {
        return $this->html(null, 'create');
    }

    public function store(Request $request): Response
    {
        $name = $request->value('name');
        $body = $request->value('body');
        $is_default = $request->value('is_default') ? 1 : 0;

        $errors = [];
        if (empty($name)) $errors[] = "Name is required";
        if (empty($body)) $errors[] = "Body is required";

        if (!empty($errors)) {
            return $this->view('script.create', ['errors' => $errors]);
        }

        if ($is_default) {
            // Unset other defaults? Logic could be added here but simple for now.
        }

        $script = new Script();
        $script->name = $name;
        $script->body = $body;
        $script->is_default = $is_default;
        $script->save();

        return $this->redirect($this->url('script.index'));
    }

    public function edit(Request $request): Response
    {
        $id = $request->value('id');
        $script = Script::getOne($id);

        if (!$script) {
            return $this->redirect($this->url('script.index'));
        }

        return $this->html(compact('script'));
    }

    public function update(Request $request): Response
    {
        $id = $request->value('id');
        $script = Script::getOne($id);

        if (!$script) {
            return $this->redirect($this->url('script.index'));
        }

        $name = $request->value('name');
        $body = $request->value('body');
        $is_default = $request->value('is_default') ? 1 : 0;

        $script->name = $name;
        $script->body = $body;
        $script->is_default = $is_default;
        $script->save();

        return $this->redirect($this->url('script.index'));
    }

    public function delete(Request $request): Response
    {
        $id = $request->value('id');
        $script = Script::getOne($id);
        if ($script) {
            $script->delete();
        }
        return $this->redirect($this->url('script.index'));
    }
}
