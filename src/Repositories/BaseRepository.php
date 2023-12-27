<?php

namespace Bektasyildiz\LaravelRepository\Repositories;

use Closure;
use Bektasyildiz\LaravelRepository\Exceptions\LaravelRepositoryException;
use Illuminate\Database\Eloquent\Model;

class BaseRepository implements RepositoryInterface {

    protected $model;

    /**
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function findById(int $id)
    {
        return $this->model->find($id);
    }

    /**
     * @param array $credentials
     * @param $orderByColumn
     * @param string|null $orderByDirection
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function findBy(array $condition, $orderByColumn = null, string $orderByDirection = null, int $perPage = null)
    {
        $this->model = $this->model->query();
        $this->applyConditions($condition);
        if ($orderByColumn) {
            $this->model = $this->model->orderBy($orderByColumn, $orderByDirection ?? 'ASC');
        }
        if (!$perPage) {
            $data = $this->model->get();
        } else {
            $data = $this->model->paginate($perPage);
        }
        return $data;
    }

    /**
     * @param int|null $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getAll(int $perPage = null)
    {
        return $perPage ? $this->model->query()->paginate($perPage) : $this->model->query()->get();
    }

    /**
     * @param int $id
     * @return int
     */
    public function deleteById(int $id)
    {
        return $this->model->query()->find($id)->delete();
    }

    /**
     * @param array $data
     * @return bool
     */
    public function save(array $data)
    {
        return $this->model->fill($data)->save();
    }

    /**
     * @param int $id
     * @param array $data
     * @return bool|int
     */
    public function updateById(int $id, array $data)
    {
        return $this->model->query()->find($id)->fill($data)->update();
    }

    /**
     * @param array $condition
     * @return void
     */
    protected function applyConditions(array $condition)
    {
        foreach ($condition as $field => $value) {
            if (is_array($value)) {
                list($field, $condition, $val) = $value;

                $condition = preg_replace('/\s\s+/', ' ', trim($condition));

                $operator = explode(' ', $condition);
                if (count($operator) > 1) {
                    $condition = $operator[0];
                    $operator = $operator[1];
                } else $operator = null;
                switch (strtoupper($condition)) {
                    case 'IN':
                        if (!is_array($val)) throw new LaravelRepositoryException("Input {$val} mus be an array");
                        $this->model = $this->model->whereIn($field, $val);
                        break;
                    case 'NOTIN':
                        if (!is_array($val)) throw new LaravelRepositoryException("Input {$val} mus be an array");
                        $this->model = $this->model->whereNotIn($field, $val);
                        break;
                    case 'DATE':
                        if (!$operator) $operator = '=';
                        $this->model = $this->model->whereDate($field, $operator, $val);
                        break;
                    case 'DAY':
                        if (!$operator) $operator = '=';
                        $this->model = $this->model->whereDay($field, $operator, $val);
                        break;
                    case 'MONTH':
                        if (!$operator) $operator = '=';
                        $this->model = $this->model->whereMonth($field, $operator, $val);
                        break;
                    case 'YEAR':
                        if (!$operator) $operator = '=';
                        $this->model = $this->model->whereYear($field, $operator, $val);
                        break;
                    case 'EXISTS':
                        if (!($val instanceof Closure)) throw new LaravelRepositoryException("Input {$val} must be closure function");
                        $this->model = $this->model->whereExists($val);
                        break;
                    case 'HAS':
                        if (!($val instanceof Closure)) throw new LaravelRepositoryException("Input {$val} must be closure function");
                        $this->model = $this->model->whereHas($field, $val);
                        break;
                    case 'HASMORPH':
                        if (!($val instanceof Closure)) throw new LaravelRepositoryException("Input {$val} must be closure function");
                        $this->model = $this->model->whereHasMorph($field, $val);
                        break;
                    case 'DOESNTHAVE':
                        if (!($val instanceof Closure)) throw new LaravelRepositoryException("Input {$val} must be closure function");
                        $this->model = $this->model->whereDoesntHave($field, $val);
                        break;
                    case 'DOESNTHAVEMORPH':
                        if (!($val instanceof Closure)) throw new LaravelRepositoryException("Input {$val} must be closure function");
                        $this->model = $this->model->whereDoesntHaveMorph($field, $val);
                        break;
                    case 'BETWEEN':
                        if (!is_array($val)) throw new LaravelRepositoryException("Input {$val} mus be an array");
                        $this->model = $this->model->whereBetween($field, $val);
                        break;
                    case 'BETWEENCOLUMNS':
                        if (!is_array($val)) throw new LaravelRepositoryException("Input {$val} mus be an array");
                        $this->model = $this->model->whereBetweenColumns($field, $val);
                        break;
                    case 'NOTBETWEEN':
                        if (!is_array($val)) throw new LaravelRepositoryException("Input {$val} mus be an array");
                        $this->model = $this->model->whereNotBetween($field, $val);
                        break;
                    case 'NOTBETWEENCOLUMNS':
                        if (!is_array($val)) throw new LaravelRepositoryException("Input {$val} mus be an array");
                        $this->model = $this->model->whereNotBetweenColumns($field, $val);
                        break;
                    case 'RAW':
                        $this->model = $this->model->whereRaw($val);
                        break;
                    default:
                    $this->model = $this->model->where($field, $condition, $val);
                }
            } else {
                $this->model = $this->model->where($field, '=', $value);
            }
        }
    }

}
