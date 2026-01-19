<?php

namespace App\Controllers;

use App\Models\Attachment;
use App\Core\SecureController;
use Framework\Http\Request;
use Framework\Http\Responses\Response;

class FileController extends SecureController
{
    public function authorize(Request $request, string $action): bool
    {
        return $this->user->isLoggedIn();
    }

    public function index(Request $request): Response
    {
        $files = Attachment::getAll("user_id = ? AND lead_id IS NULL", [$this->user->getIdentity()->id], "created_at DESC");
        return $this->html(compact('files'));
    }

    public function upload(Request $request): Response
    {
        if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['file'];
            $uploadDir = __DIR__ . '/../../public/uploads/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            
            $filename = uniqid() . '_' . basename($file['name']);
            $targetPath = $uploadDir . $filename;
            
            if (move_uploaded_file($file['tmp_name'], $targetPath)) {
                $attachment = new Attachment();
                $attachment->user_id = $this->user->getIdentity()->id;
                $attachment->lead_id = null;
                $attachment->filename = $file['name'];
                $attachment->path = $filename;
                $attachment->save();
            }
        }

        return $this->redirect($this->url('file.index'));
    }

    public function delete(Request $request): Response
    {
        $id = $request->value('id');
        $file = Attachment::getOne($id);

        if ($file && $file->user_id === $this->user->getIdentity()->id) {
            $path = __DIR__ . '/../../public/uploads/' . $file->path;
            if (file_exists($path)) {
                unlink($path);
            }
            $file->delete();
        }

        return $this->redirect($this->url('file.index'));
    }
}
