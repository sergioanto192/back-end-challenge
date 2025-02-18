<?php
namespace App\Services;

use App\Services\Interfaces\TagServiceInterface;
use App\Models\Tag;
use App\Handlers\ErrorHandler;
use App\Handlers\SuccessHandler;

class TagService implements TagServiceInterface{
  
    public function getAll() {
        return Tag::getAll();
    }

    public function findById($id) {
        $tag = Tag::findById($id);
        if (!$tag) {
            return ErrorHandler::notFound('Tag não encontrada');
        }
        return $tag;
    }

    public function create($data) {
        if (!isset($data['name']) || empty(trim($data['name']))) {
            return ErrorHandler::badRequest('O campo Nome é obrigatório');
        }
        $tag_id = Tag::create($data['name']);
        return ['id' => $tag_id];
    }

    public function update($id, $data) {
        $tag = Tag::findById($id);
        if (!$tag) {
            return ErrorHandler::notFound('Tag não encontrada');
        }

        if (!isset($data['name']) || empty(trim($data['name']))) {
            return ErrorHandler::badRequest('O campo Nome é obrigatório');
        }

        Tag::update($id, $data['name']);
        return SuccessHandler::UpdateOperationSuccess($id);
    }

    public function delete($id) {
        $tag = Tag::findById($id);
        if (!$tag) {
            return ErrorHandler::notFound('Tag não encontrada');
        }

        Tag::delete($id);
        return SuccessHandler::DeleteOperationSuccess($id);
    }
}
