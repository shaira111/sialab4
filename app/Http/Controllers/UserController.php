<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;

Class UserController extends Controller {
    use ApiResponser;

    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }


    public function getUsers()
    {
        $users = User::all();
        return response()->json(['data' => $users], 200);
        
        //return $this->successResponse($users);
    }

    public function index()
    {
        $users = User::all();
        
        return $this->successResponse($users);
    }



    public function add(Request $request)
    {
        
        $rules = [
            'username' => 'required|max:20',
            'password' => 'required|max:20',
            'gender' => 'required|in:Male,Female',
        ];

        $this->validate($request,$rules);

        $user = User::create($request->all());
        return $this->successResponse($user, Response::HTTP_CREATED);
        //return response()->json($user, 200);
    }


    public function show($id)
    {
        //$user =  User::findOrFail($id);
        $user = User::where('userid', $id)->first();

        if($user){
            return $this->successResponse($user);
        }
        else{
            return $this->errorResponse('User ID Does Not Exists', Response::HTTP_NOT_FOUND);
        }
        
    }

    public function update(Request $request, $id) { //UPDATE USER
        $rules = [
            'username' => 'max:20',
            'password' => 'max:20',
            'gender' => 'in:Male,Female',
        ];
    
        $this->validate($request, $rules);
    
        $User =  User::findOrFail($id);
    
        $User->fill($request->all());
    
        if ($User->isClean()) {
            return response()->json("At least one value must change", 403);
        } else {
            $User->save();
            return response()->json($User, 200);
        }
    }

    public function delete($id) { // DELETE USER
         $user =  User::find($id);
         $user->delete();

         return response()->json('User Successfully Deleted!');
    }
}