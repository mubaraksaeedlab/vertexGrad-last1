<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class StoreInvestorRequest extends FormRequest
{
    public function authorize() { return true; }
    public function rules() {
        return [
            'name'=>'required|string|max:150',
            'email' => 'required|email|unique:users,email',
            'phone'=>'nullable|string|max:50',
            'company'=>'nullable|string|max:150',
            'position'=>'nullable|string|max:150',
            'investment_type'=>'nullable|string|max:100',
            'budget'=>'nullable|numeric',
            'source'=>'nullable|string|max:100',
            
        ];
    }
}
