<?php

namespace App\Models;

trait GetOrPaginate
{
    /**
     * @param $query
     * @param $request
     * @param bool $forcePagination
     */
    public function scopeGetOrPaginate($query, $request, bool $forcePagination = false)
    {
        $pagination = $forcePagination ?: false;
        if ($request->has('page')) {
            $pagination = $request->page ? true : false;
        }

        if ($pagination) {
            return $query->paginate($request->limit ?: 50);
        } else {
            return $query->get();
        }
    }
}
