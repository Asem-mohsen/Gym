<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Service\UpdateRequest;
use App\Http\Requests\Service\AddRequest;
use App\Traits\ApiResponse;
use App\Models\User;
use App\Models\Booking;
use App\Models\Service;

class ServicesController extends Controller
{
    use ApiResponse;

    // works
    public function index()
    {
        $services = Service::all();

        return $this->data(compact('services') , 'data retrieved successfully');
    }

    // works
    public function store(AddRequest $request)
    {
        $data = $request->except('_method' , 'token');

        $service = Service::create($data);

        return $this->data(compact('service') , $service->name . ' created successfully');
    }

    // works
    public function edit(Service $service)
    {
        return $this->data(compact('service') , $service->name . ' retrieved successfully');
    }

    // works
    public function update(UpdateRequest $request , Service $service)
    {
        $data = $request->except('_method' , 'token');

        Service::where('id' , $service->id)->update($data);

        return $this->success($service->name . ' updated successfully');
    }

    // works
    public function destroy(Request $request , Service $service)
    {
        Service::where('id' ,  $service->id)->delete();

        return $this->success('Service deleted successfully');
    }
}
