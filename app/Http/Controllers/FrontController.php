<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AccountList;
use App\Models\BankEmployee;
use Session;

class FrontController extends Controller
{
    public function accountCreation(){
        return view('adminPanel.accountCreation');
    }
    public function aclist(){
        return view('adminPanel.accountList');
    }

    public function saveAccount(Request $requ){
        $chk = AccountList::where(['acNumber'=>$requ->acNo])->get();
        if(!$chk->isEmpty()):
            return back()->with('error','Sorry! User already exist');
        endif;

        $data = new AccountList();

        $data->acName       = $requ->acName;
        $data->acNumber     = $requ->acNo;
        $data->acType       = $requ->acType;
        $data->acMobile     = $requ->acMobile;
        $data->acFinger     = $requ->acFinger;
        $data->employee_id  = $requ->employeeId;

        if($data->save()):
            return back()->with('success','Success! Account creation successfully');
        else:
            return back()->with('error','Opps! Account creation failed. Please try later');
        endif;
        
    }

    public function acView($id){
        $acData = AccountList::find($id);
        return view('adminPanel.accountGenerate',['data'=>$acData]);
    }

    public function acEdit($id){
        $acData = AccountList::find($id);
        
        // Authorization check
        if(!$this->canEditAccount($acData)):
            return back()->with('error','Sorry! You do not have permission to edit this account');
        endif;
        
        return view('adminPanel.accountUpdate',['data'=>$acData]);
    }

    public function acUpdate(Request $requ){
        $data = AccountList::find($requ->acId);
        if(isset($data)):
            // Authorization check
            if(!$this->canEditAccount($data)):
                return back()->with('error','Sorry! You do not have permission to edit this account');
            endif;
            
            $data->acName       = $requ->acName;
            $data->acNumber     = $requ->acNo;
            $data->acType       = $requ->acType;
            $data->acMobile     = trim((string) $requ->acMobile);
            $data->acFinger     = $requ->acFinger;
            $data->employee_id  = $requ->employeeId ?? $data->employee_id;

            if($data->save()):
                return back()->with('success','Success! Account update successfully');
            else:
                return back()->with('error','Opps! Account failed to update. Please try later');
            endif;
        else:
            return back()->with('error','Sorry! no data found');
        endif;
        
    }

    public function acDel($id){
        $acData = AccountList::find($id);
        if(!empty($acData)):
            // Authorization check
            if(!$this->canEditAccount($acData)):
                return back()->with('error','Sorry! You do not have permission to delete this account');
            endif;
            
            if($acData->delete()):
                return back()->with('success','Records successfully deleted');
            endif;
        endif;

        return back()->with('error','No records found for deleted');
    }
    
    /**
     * Check if current user can edit/delete an account
     * Super admin can edit all accounts, others can only edit their own
     */
    private function canEditAccount($account): bool
    {
        $currentAdminId = $this->getCurrentAdminId();
        $currentAdmin = BankEmployee::find($currentAdminId);
        
        // Super admin can edit/delete any account
        if($currentAdmin && $currentAdmin->profileType == 1):
            return true;
        endif;
        
        // Others can only edit their own accounts
        return $account->employee_id == $currentAdminId;
    }
    
    /**
     * Get current admin ID from session
     */
    private function getCurrentAdminId(): ?int
    {
        if (Session::has('superAdmin')) {
            return BankEmployee::find(Session::get('superAdmin'))?->id;
        }

        if (Session::has('generalAdmin')) {
            return BankEmployee::find(Session::get('generalAdmin'))?->id;
        }

        if (Session::has('manager')) {
            return BankEmployee::find(Session::get('manager'))?->id;
        }

        if (Session::has('cashier')) {
            return BankEmployee::find(Session::get('cashier'))?->id;
        }

        return null;
    }

    public function acDelete($id){
        return $this->acDel($id);
    }

    public function editEmployee($id){
        $bankEmployee = BankEmployee::find($id);
        return view('adminPanel.createEmployee',['profile'=>$bankEmployee]);
    }

    public function delEmployee($id){
        $bankEmployee = BankEmployee::find($id);
        if($bankEmployee->delete()):
            return back()->with('success','Records successfully deleted');
        else:
            return back()->with('error','No records found for deleted');
        endif;
    }

}
