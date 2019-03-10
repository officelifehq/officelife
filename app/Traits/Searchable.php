<?php

namespace App\Traits;

use App\Helpers\SearchHelper;
use Illuminate\Database\Eloquent\Builder;

trait Searchable
{
    /**
     * Search for needle in the columns defined by $searchable_columns.
     *
     * @param  Builder $builder query builder
     * @param  string $needle
     * @param  int $companyId
     * @param  int $limitPerPage
     * @param  string $sortOrder
     * @param  string $whereCondition
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|null
     */
    public function scopeSearch(Builder $builder, $needle, $companyId, $limitPerPage, $sortOrder, $whereCondition = null)
    {
        if ($this->searchableColumns == null) {
            return;
        }

        $queryString = SearchHelper::buildQuery($this->searchableColumns, $needle);

        $builder->whereRaw('company_id = '.$companyId.' and ('.$queryString.') '.$whereCondition);
        $builder->orderByRaw($sortOrder);
        $builder->select($this->returnFromSearch);

        return $builder->paginate($limitPerPage);
    }
}
