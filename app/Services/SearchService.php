<?php

namespace App\Services;

class SearchService
{
    protected $allowedOperator = ['LIKE', '->', '=', 'IN', '>', '<', '>=', '=<', 'OR'];

    public function search($query, array $searchConfig, $request)
    {
        foreach ($searchConfig as $field => $config) {
            if (is_array($config) && isset($config['relationship'])) {
                $this->applyRelationshipFilter($query, $field, $config, $request);
                continue;
            }

            $column = $field;
            $operator = 'LIKE';
            if (is_numeric($column)) {
                $column = $config;
            } else {
                $operator = $config;
            }

            $this->applyFieldFilter($query, $column, $operator, $request);
        }

        return $query;
    }

    protected function applyFieldFilter($query, $column, $operator, $request)
    {
        if ($request->has($column) && !empty($request->input($column)) && in_array($operator, $this->allowedOperator)) {
            $value = $request->input($column);

            if (str_contains($column, '->')) {
                $query->whereJsonContains($column, $value);
            } elseif ($operator === '=') {
                $query->where($column, $value);
            } elseif ($operator === 'IN') {
                $query->whereIn($column, (array)$value);
            } elseif ($operator === 'LIKE') {
                $query->where($column, 'LIKE', "%{$value}%");
            } else {
                $query->where($column, $operator, $value);
            }
        }
    }

    protected function applyRelationshipFilter($query, $relationship, $config, $request)
    {
        $field = $config['field'] ?? 'id';
        $operator = $config['operator'] ?? '=';
        $requestKey = $config['request_key'] ?? $relationship;

        if ($request->filled($requestKey)) {
            $value = $request->input($requestKey);

            $query->whereHas($relationship, function ($q) use ($field, $operator, $value) {
                if ($operator === 'IN') {
                    $q->whereIn($field, (array)$value);
                } else {
                    $q->where($field, $operator, $value);
                }
            });
        }
    }
}
