<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserCollection;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $where = [];
        if ($request->name) {
            $where[] = ['name', 'like', '%' . $request->name . '%'];
        }
        if ($request->email) {
            $where[] = ['email', 'like', '%' . $request->email . '%'];
        }
        $users = User::orderBy('id', 'desc');
        if (!empty($where)) {
            $users = $users->where($where);
        }
        $users = $users->paginate();
        if ($users->count() > 0) {
            $statusCode = 200;
            $statusText = 'success';
        } else {
            $statusCode = 204;
            $statusText = 'No Data';
        }
        // $users = UserResource::collection($users);
        $users = new UserCollection($users, $statusCode, $statusText);

        // $response = [
        //     'status' => 'success',
        //     'data' => $users
        // ];
        return $users;
    }
    public function detail($id)
    {
        // $user = User::with('posts')->find($id);
        $user = User::find($id);
        if (!$user) {
            $statusCode = 204;
            $statusText = 'No Data';
        } else {
            $statusCode = 200;
            $statusText = 'success';
            $user = new UserResource($user);
        }

        $response = [
            'status' => $statusCode,
            'title' => $statusText,
            'data' => $user
        ];
        return $response;
    }
    public function create(Request $request)
    {
        $this->validation($request);
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();
        if ($user->id) {
            $response = [
                'status' => 201,
                'title' => 'created',
                'data' => $user
            ];
        } else {
            $response = [
                'status' => 500,
                'title' => 'error',

            ];
        }
        return $response;
    }
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        $this->validation($request, $id);
        if (!$user) {
            $response = [
                'status' => 'no_data',
                'data' => $user,
            ];
        } else {
            $method = $request->method();
            if ($method === 'PUT') {
                $user->name = $request->name;
                $user->email = $request->email;
                if (!empty($request->password)) {
                    $user->password = Hash::make($request->password);
                } else {
                    $user->password = null;
                }
                $user->save();
                $response = [
                    'status' => 200,
                    'title' => 'success',
                    'data' => $user,
                ];
                return $response;
            } else {
                if ($request->name) {
                    $user->name = $request->name;
                }
                if ($request->email) {
                    $user->email = $request->email;
                }
                if ($request->password) {
                    $user->password = Hash::make($request->password);
                }
                $user->save();
                $response = [
                    'status' => 200,
                    'title' => 'success',
                    'data' => $user,
                ];
            }
        }
        return $response;
    }
    public function delete(User $user)
    {
        if (User::delete($user->id)) {
            $response = [
                'status' => 'success',
            ];
        } else {
            $response = [
                'status' => 'error',
            ];
        }
        return $response;
    }
    public function validation(Request $request, $id = 0)
    {
        $emailValidation = 'required|email|unique:users';
        if (!empty($id)) {
            $emailValidation .= ",email,$id";
        }
        $rule = [
            'name' => 'required|min:5',
            'email' =>  $emailValidation,
            'password' => 'required|min:6',
        ];
        $message = [
            'name.required' => 'Tên bắt buộc phải nhập',
            'name.min' => 'Tên phải nhiều hơn 5 ký tự',
            'email.required' => 'Email bắt buộc phải nhập',
            'email.email' => 'Email không đúng định dạng',
            'password.required' => 'Password bắt buộc phải nhập',
            'password.min' => 'Password phải nhiều hơn 6 ký tự'
        ];
        $request->validate($rule, $message);
    }
}
