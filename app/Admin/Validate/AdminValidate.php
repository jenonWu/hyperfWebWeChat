<?php

declare(strict_types=1);

namespace App\Admin\Validate;

use Hyperf\Validation\Request\FormRequest;
use Hyperf\Validation\Rule;

class AdminValidate extends FormRequest
{
    protected $scenes = [
        'add' => ['username', 'password','role_id'],
        'edit' => ['username', 'role_id'],
    ];

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $data = $this->getInputData();
        if(empty($data['id'])){
            $ignoreId = '';
        }else{
            $ignoreId = ','.$data['id'];
        }

        return [
            'username' => 'required|unique:admin,username'.$ignoreId,
            'password' => 'required|digits_between:6,16|confirmed',
            'role_id'  => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'username.required' => '用户不能为空',
            'username.unique'  => '用户名已存在',
            'password.required'  => '密码不能为空',
            'password.digits_between'  => '密码必须6到16位',
            'password.confirmed'  => '两次密码不一致',
            'role_id.required'  => '请选择角色',
        ];
    }


}
