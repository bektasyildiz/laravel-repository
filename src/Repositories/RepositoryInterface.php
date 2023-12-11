<?php

namespace Bektasyildiz\LaravelRepository\Repositories;

interface RepositoryInterface
{

    public function findById(int $id);

    public function findBy(array $credentials, $orderByColumn = null, string $orderByDirection = null);

    public function getAll(int $paginateBy = null);

    public function deleteById(int $id);

    public function save(array $data);

    public function updateById(int $id, array $data);
}
