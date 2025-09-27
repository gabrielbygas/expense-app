<?php

namespace Modules\Expenses\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Modules\Expenses\Http\Requests\StoreExpenseRequest;
use Modules\Expenses\Http\Requests\UpdateExpenseRequest;
use Modules\Expenses\Http\Resources\ExpenseResource;
use Modules\Expenses\Models\Expense;
use Modules\Expenses\Services\ExpenseService;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Routing\Controller;


class ExpenseController extends Controller
{
    public function __construct(private ExpenseService $service) {}

    public function index(): JsonResponse
    {
        $expenses = $this->service->list();
        return ExpenseResource::collection($expenses)->response();
    }

    public function store(StoreExpenseRequest $request): JsonResponse
    {
        $expense = $this->service->create($request->validated());
        return ExpenseResource::make($expense)
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Expense $expense): JsonResponse
    {
        return ExpenseResource::make($expense)->response();
    }

    public function update(UpdateExpenseRequest $request, Expense $expense): JsonResponse
    {
        $expense = $this->service->update($expense, $request->validated());
        return ExpenseResource::make($expense)->response();
    }

    public function destroy(Expense $expense): JsonResponse
    {
        $this->service->delete($expense);
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    // Bonus: Filtre
    public function filter(): JsonResponse
    {
        $expenses = $this->service->filter(request()->all());
        return ExpenseResource::collection($expenses)->response();
    }
}