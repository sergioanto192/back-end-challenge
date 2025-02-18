<?php
namespace App\Controllers;

use App\Services\Interfaces\TagServiceInterface;
use App\Services\Interfaces\AuthServiceInterface;

class TagController {
    protected $tagService;
    protected $authService;
    protected $authError;

    public function __construct(TagServiceInterface $tagService, AuthServiceInterface $authService) {
        $this->tagService = $tagService;
        $this->authService = $authService;

        $this->authError = $this->authService->authenticate();
    }

    public function index() {
        if ($this->authError) return $this->authError;

        return $this->tagService->getAll();
    }

    public function show($id) {
        if ($this->authError) return $this->authError;

        return $this->tagService->findById($id);
    }

    public function store($data) {
        if ($this->authError) return $this->authError;

        return $this->tagService->create($data);
    }

    public function update($id, $data) {
        if ($this->authError) return $this->authError;

        return $this->tagService->update($id, $data);
    }

    public function destroy($id) {
        if ($this->authError) return $this->authError;

        return $this->tagService->delete($id);
    }
}

