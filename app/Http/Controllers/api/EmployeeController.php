<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\BaseController;
use App\Http\Requests\StoreEmployeeRequest;
use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $employee = Employee::all();
            return $employee;
        } catch (\Exception $e) {
            return $this->sendError('', 'some thing went wrong' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEmployeeRequest $request)
    {
        try {
            // Pass null or an empty value for id, as we're creating a new record
            $data = $this->updateOrCreateEmployee(null, $request);

            return $this->sendResponse($data, 'User successfully saved');

        } catch (\Exception $e) {
            return $this->sendError('', 'Something went wrong: ' . $e->getMessage());
        }
    }




    /**
     * Display the specified resource.
     */
    public function show(Employee $employee)
    {
        try {
            $employeeData = $employee->toArray();
            $employeeData['name'] = $employee->name;

            return $this->sendResponse($employeeData, 'Requested employee successfully displayed');
        } catch (\Exception $e) {
            return $this->sendError('Exception Error', 'Something went wrong: ' . $e->getMessage());
        }
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function updateOrCreateEmployee($id, $request)
    {
        $data = Employee::updateOrCreate(
            ['id' => $id], // This id comes from the URL
            [
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'contact_number' => $request->contact_number,
            ]);
        return $data;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreEmployeeRequest $request, $id)
    {
        try {
            $data = $this->updateOrCreateEmployee($id, $request); // Pass the id and the request

            return $this->sendResponse($data, 'User successfully updated');

        } catch (\Exception $e) {
            return $this->sendError('', 'Something went wrong: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee)
    {
        try {
            $employee = $employee->delete();
            return $this->sendResponse($employee, 'Employee successfully deleted');
        } catch (\Exception $e) {
            return $this->sendError('Exception Error', 'Something went wrong: ' . $e->getMessage());
        }
    }
}
