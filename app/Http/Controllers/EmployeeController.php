<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use DB;
use App\Models\Employee;
use App\Models\Category;
use Validator;
use File;

class EmployeeController extends Controller
{
    function index()
    {
        $categories = Category::select('id', 'name')->get();
        return view("employee",[
            "categories" => $categories
        ]);
    }

    function list()
    {
        $data = Employee::with('category')->get();
        $categories = Category::select('id', 'name')->get();
        return view("list",[
            "data" => $data,
            "categories" => $categories
        ]);
    }

    function action(Request $request)
    {
        if($request->ajax())
        {
            if($request->action == 'edit')
            {
                $emp_obj = Employee::find($request['id']);

                $emp_obj->name = $request->name;
                $emp_obj->phone = $request->phone;
                $emp_obj->hobby = $request->hobby;
                $emp_obj->category_id = $request->category;

                $emp_obj->update();
            }
            if($request->action == 'delete')
            {
                DB::table('employees')
                    ->where('id', $request->id)
                    ->delete();
            }
            return response()->json($request);
        }
    }

    public function store(Request $request)
    {  
        request()->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'txtCategory' => 'required',
            'txtEname' => 'required',
            'txtCno' => 'required',
            'txtHobby' => 'required',
        ],
        [
            'image.mimes' => 'Please upload only image file',
            'txtCategory.required' => 'Please select category',
            'txtEname.required' => 'Employee name field is required',
            'txtCno.required' => 'Contact number field is required',
            'txtHobby.required' => 'Select at least 1 hobby',
        ]);
 
        $input = $request->all();
        $input['hobby'] = $request->input('hobby');
        $details = ['category_id' => $request->txtCategory, 'name' => $request->txtEname, 'phone' => $request->txtCno, 'hobby' => implode(', ',$request->txtHobby)];
 
        if ($files = $request->file('image')) {
            
           //delete old file
           //\File::delete('public/product/'.$request->hidden_image);
         
           //insert new file
           $destinationPath = 'public/images/employee/'; // upload path
           $profileImage = date('YmdHis') . "." . $files->getClientOriginalExtension();
           $files->move($destinationPath, $profileImage);
           $details['profile_pic'] = "$profileImage";
        }
         
        $employee = Employee::create($details);  
               
        // return Response::json($employee);
    }

    public function deleteMultiple(Request $request)
    {
        $ids = $request->ids;
        Employee::whereIn('id',explode(",",$ids))->delete();
        return response()->json(['status'=>true,'message'=>"Employee deleted successfully."]);
         
    }
}
