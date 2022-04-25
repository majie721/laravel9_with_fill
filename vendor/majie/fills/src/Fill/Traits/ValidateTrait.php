<?php

namespace Majie\Fills\Fill\Traits;

use Majie\Fills\Fill\Exceptions\ExceptionConstCode;
use Majie\Fills\Fill\Exceptions\ValidateException;
use Majie\Fills\Fill\Validate\ValidatorFactory;

trait ValidateTrait
{


    protected function validateAction(array $data,bool $stopOnFirstFailure)
    {
        $rules = $this->rules();
        if (empty($rules)) {
            return $this;
        }
        $messages = $this->messages();
        $customAttributes = $this->attributes();

        $validator = ValidatorFactory::getValidator($data,$rules,$messages,$customAttributes);
        if($validator->stopOnFirstFailure($stopOnFirstFailure)->fails()){
            $errors = $stopOnFirstFailure ? [$validator->errors()->first()] :$validator->errors()->all();
            $messages = sprintf("Some parameter verification errors,The error information as below:%s.",json_encode($errors));
            throw new ValidateException($errors,$messages,ExceptionConstCode::VALIDATE_FAILURE);
        }

        return $this;
    }


    /**
     * 获取应用于该请求的验证规则。
     *
     * @return array
     */
    public function rules()
    {
        return [];
    }


    /**
     * 获取已定义验证规则的错误消息。
     *
     * @return array
     */
    public function messages()
    {
        return [];
    }

    /**
     * 获取验证错误的自定义属性
     *
     * @return array
     */
    public function attributes()
    {
        return [];
    }



}
