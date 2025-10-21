<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function bussinessSearchByRelatation($query, $search)
    {
        $query->where(function ($q) use ($search) {
            $q->where('name', 'LIKE', "%{$search}%"); //mencari by name studio
            $q->orWhereHas('user', function ($query) use ($search) {
                $query->where('name', 'LIKE', "%{$search}%"); // by owner studio
            });
            $q->orWhereHas('circle', function ($query) use ($search) {
                $query->where('name', 'LIKE', "%{$search}%"); // by circle studio
                $query->orWhereHas('ecosystem', function ($subQuery) use ($search) {
                    $subQuery->where('name', 'LIKE', "%{$search}%"); // by ecosystem studio
                    $subQuery->orWhereHas('user', function ($subSubQuery) use ($search) {
                        $subSubQuery->where('name', 'LIKE', "%{$search}%"); // by user ecosystem studio
                    });
                });
                $query->orWhereHas('user', function ($subQuery) use ($search) {
                    $subQuery->where('name', 'LIKE', "%{$search}%"); // by owner circle
                });
            });
            $q->orWhereHas('businessMember', function ($query) use ($search) {
                $query->whereHas('position', function ($subQuery) {
                    $subQuery->whereIn('name', ['Koordinator', 'Mentor']); 
                })
                ->whereHas('user', function ($subsubQuery) use ($search) {
                    $subsubQuery->where('name', 'LIKE', "%{$search}%"); 
                });
            });            
        });
    }
}
