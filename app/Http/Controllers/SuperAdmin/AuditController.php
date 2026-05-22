<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Services\AuditService;
use Illuminate\Http\Request;

class AuditController extends Controller
{
    public function index(Request $request)
    {
        $page   = max(1, (int) $request->get('page', 1));
        $search = $request->get('q', '');
        $action = $request->get('action', '');

        $all = AuditService::all();

        if ($search) {
            $all = array_filter($all, fn($e) => str_contains(strtolower($e['description'] ?? ''), strtolower($search))
                || str_contains(strtolower($e['user_name'] ?? ''), strtolower($search))
                || str_contains(strtolower($e['user_email'] ?? ''), strtolower($search)));
            $all = array_values($all);
        }

        if ($action) {
            $all = array_filter($all, fn($e) => ($e['action'] ?? '') === $action);
            $all = array_values($all);
        }

        $perPage  = 50;
        $total    = count($all);
        $lastPage = max(1, (int) ceil($total / $perPage));
        $page     = min($page, $lastPage);
        $items    = array_slice($all, ($page - 1) * $perPage, $perPage);

        $actionTypes = array_values(array_unique(array_column(AuditService::all(), 'action')));
        sort($actionTypes);

        return view('super-admin.audit.index', compact(
            'items', 'total', 'page', 'lastPage', 'perPage',
            'search', 'action', 'actionTypes'
        ));
    }
}
