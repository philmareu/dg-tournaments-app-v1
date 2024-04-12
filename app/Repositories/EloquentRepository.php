<?php

namespace DGTournaments\Repositories;

abstract class EloquentRepository {

    protected $model;

    protected $checkboxes = [];

    public function __construct($model = null)
    {
        $this->model = $model;
    }

    public function getById($id)
    {
        return $this->model->find($id);
    }

    public function getAll()
    {
        return $this->model->all();
    }

    public function create($attr)
    {
        return $this->model->create($attr);
    }

    public function delete()
    {
        return $this->model->delete();
    }
}