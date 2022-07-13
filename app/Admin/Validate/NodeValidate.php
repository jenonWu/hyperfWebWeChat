<?php

declare(strict_types=1);

namespace App\Admin\Validate;

use Hyperf\Validation\Request\FormRequest;
use Hyperf\Validation\Rule;

class NodeValidate extends FormRequest
{

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
            'name' => 'required',
            'controller' => 'required',
            'action' => 'required',
            'sort' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => '节点名称不能为空',
            'controller.required' => '控制器不能为空',
            'action.required' => '方法不能为空',
            'sort.required' => '排序不能为空',
        ];
    }


}
