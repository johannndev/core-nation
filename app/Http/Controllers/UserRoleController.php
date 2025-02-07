<?php

namespace App\Http\Controllers;

use App\Exceptions\ModelException;
use App\Models\Location;
use App\Models\User;
use App\Models\UserSetting;
use GuzzleHttp\Psr7\Response;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserRoleController extends Controller
{
    

    public function userList(Request $request){
        $dataList = User::with('roles','location');

        if($request->name){
			$dataList = $dataList->where('username', 'like', '%'.$request->name.'%');
		}

		if($request->role){
			$dataList = $dataList->role($request->role);
		}

		if($request->status == 'sb'){
            $dataList = $dataList->withoutRole(['superadmin']);
        }elseif($request->status == 'bo'){
			$dataList = $dataList->role(['ban']);
		}else{
            $dataList = $dataList->withoutRole(['superadmin','ban']);
        }

        $allRolesInDatabase = Role::whereNotIn('name', ['superadmin','ban'])->get()->pluck('name');

        $dataList = $dataList->paginate(20)->withQueryString();

        // dd($allRolesInDatabase);

        return view('user-role.user',compact('dataList','allRolesInDatabase'));
    }

    public function userCreate(){

        $roleList = Role::whereNotIn('name',['ban','superadmin'])->get();
        $lokalList = Location::all();

        return view('user-role.user-create',compact('roleList','lokalList'));
    }

    public function userStore(Request $request){

        try
		{
            DB::beginTransaction();

            $role = Role::where('name',$request->role)->first();

            $user = new User;
            $user->username = $request->name;
            $user->location_id = $request->city ?? 0;
            $user->role_id = $role->id;
    
            $password = User::generatePassword();
            $user->password = Hash::make($password);
            $user->active = 1;
            if(!$user->save())
                throw new \Exception('cannot save user', 1);

            
            $user->syncRoles($request->role);

            //create app settings
            foreach(UserSetting::$list as $name => $val)
            {
                $a = new UserSetting;
                $a->user_id = $user->id;
                $a->name = $name;
                $a->value = 0;
                if(!$a->save())
                    throw new \Exception('cannot save user settings', 1);
            }

            DB::commit();

            return redirect()->route('user.list')->with('success',  'User '.$user->username.' created, Password: '.$password);

            
		} catch(ModelException $e) {
			DB::rollBack();
			return redirect()->back()->withInput()->with('errorMessage',$e->getErrors()['error'][0]);
		} catch(\Exception $e) {
			DB::rollBack();
			return redirect()->back()->withInput()->with('errorMessage',$e->getMessage());
		}
	}

    

    public function userEdit($id){
        $data = User::with('roles')->findOrFail($id);

        $roleList = Role::whereNotIn('name',['ban','superadmin'])->get();

        $lokalList = Location::all();

        // dd(count($data->roles));

        if(count($data->roles) > 0){
            $userRole = $data->getRoleNames()[0];
        }else{
            $userRole = '';
        }

        return view('user-role.user-edit',compact('data','roleList','lokalList','userRole'));
    }

    public function userUpdate(Request $request, $id){

      

        try
		{
            $role = Role::where('name',$request->role)->first();

            

            DB::beginTransaction();

            $user = User::find($id);


            if($user->hasRole('ban')){
                $idRole = $user->role_id;
            }else{

                if($role){
                    $idRole = $role->id;

                    $user->syncRoles($request->role);
                }else{
                    $idRole = $user->role_id;
                }

            }

            

            $user->username = $request->name;
            $user->role_id = $idRole;
            $user->location_id = $request->city;

            $password = '';
            if($request->update_password)
            {
                
               
                $password = User::generatePassword();
                $user->password = Hash::make($password);
                $password = 'Password: '.$password;
            }
    
    
            if(!$user->save())
                throw new \Exception('cannot save user', 1);


           

            //create app settings
            foreach(UserSetting::$list as $name => $val)
            {
                $a = new UserSetting;
                $a->user_id = $user->id;
                $a->name = $name;
                $a->value = 0;
                if(!$a->save())
                    throw new \Exception('cannot save user settings', 1);
            }

            DB::commit();

            return redirect()->route('user.list')->with('success', 'User '.$user->username.' edited. '.$password);

            
		} catch(ModelException $e) {
			DB::rollBack();
            
			return redirect()->back()->withInput()->with('errorMessage',$e->getErrors()['error'][0]);
		} catch(\Exception $e) {
			DB::rollBack();
            
			return redirect()->back()->withInput()->with('errorMessage',$e->getMessage());
		}
	


    }
    

    public function indexRole(){

        $dataList = Role::whereNotIn('name',['ban','superadmin'])->get();

        return view('user-role.role',compact('dataList'));
    }

    public function createRole(){

        return view('user-role.role-create');
    }

    public function storeRole(Request $request){

    
        try{
            DB::beginTransaction();

            $role = Role::create(['name' => $request->name]);

            $role->syncPermissions($request->permissions);

            DB::commit();

            return redirect()->route('role.indexRole')->with('success',  'Role '.$role->name.' created');

        

        } catch(ModelException $e) {
            DB::rollBack();
			return redirect()->back()->withInput()->with('errorMessage',$e->getErrors()['error'][0]);
        } catch(\Exception $e) {
            DB::rollBack();
			return redirect()->back()->withInput()->with('errorMessage',$e->getMessage());
        }
    }

    public function editRole($id){

        $data = Role::with('permissions')->findOrFail($id);


        $permissionsArray = $data->permissions->pluck('id','name')->toArray();

        // dd($data->permissions->pluck('id','name')->toArray());

        return view('user-role.role-edit',compact('data','permissionsArray'));
   }

  

   public function roleUpdate(Request $request,$id){

        try{
            DB::beginTransaction();

            $role = Role::find($id);

            $role->syncPermissions($request->permissions);

            DB::commit();

            return redirect()->route('role.indexRole')->with('success',  'Role '.$role->name.' edited');

        

        } catch(ModelException $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('errorMessage',$e->getErrors()['error'][0]);
        } catch(\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('errorMessage',$e->getMessage());
        }

    }

    public function deleteRole($id){

        $data = Role::findOrFail($id);


        $data->delete();

        // dd($data->permissions->pluck('id','name')->toArray());

        return redirect()->route('role.indexRole')->with('success',  'Role '.$data->name.' deleted');
   }

    public function ban($id, Request $request)
	{
		$user = User::find($id);
        $role = Role::find($user->role_id);
        

       
        if($request->userStatus == 'ban'){

            if($role){

                $status = 'activated';
                $roleName = $role->name;
                $user->syncRoles($roleName);
            }else{
                $status = 'activated';
                $user->removeRole('ban');

            }

          

      
          
          
        }else{

            $roleName = 'ban';
            $status = 'banned';

            $user->syncRoles($roleName);
           
            
        }

       

        return redirect()->route('user.list')->with('success', $user->username.' '.$status);
        
        
	}
}

