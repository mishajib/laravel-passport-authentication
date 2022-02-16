<?php

namespace App\Http\Controllers;

use App\Http\Resources\EmployeeResource;
use App\Models\Employee;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $employees = Employee::all();

        return response()->json([
            'success'   => true,
            'message'   => 'Data found.',
            'employees' => EmployeeResource::collection($employees),
        ], Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'   => 'bail|required|max:50|unique:employees',
            'age'    => 'bail|required|max:50',
            'job'    => 'bail|required|max:50',
            'salary' => 'bail|required|numeric'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $employee = Employee::create($validator->validated());

        return response()->json([
            'success'  => true,
            'message'  => 'Employee created successfully.',
            'employee' => new EmployeeResource($employee),
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param Employee $employee
     * @return JsonResponse
     */
    public function show($id)
    {
        $employee = Employee::find($id);
        if (!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'Employee not found.',
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'success'  => true,
            'message'  => 'Data found.',
            'employee' => new EmployeeResource($employee)
        ], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function update(Request $request, $id)
    {
        $employee = Employee::find($id);
        if (!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'Employee not found.',
            ], Response::HTTP_NOT_FOUND);
        }

        $validator = Validator::make($request->all(), [
            'name'   => 'bail|required|max:50|unique:employees,id,:id',
            'age'    => 'bail|required|max:50',
            'job'    => 'bail|required|max:50',
            'salary' => 'bail|required|numeric'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $employee->update($validator->validated());

        return response()->json([
            'success'  => true,
            'message'  => 'Employee updated successfully.',
            'employee' => new EmployeeResource($employee),
        ], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        $employee = Employee::find($id);

        if(!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'Employee not found.',
            ], Response::HTTP_NOT_FOUND);
        }

        $employee->delete();

        return response()->json([
            'success' => true,
            'message' => 'Employee deleted successfully.'
        ], Response::HTTP_OK);
    }
}
