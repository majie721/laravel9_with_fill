<?php

namespace Majie\Fills\Fill\Traits;

use JetBrains\PhpStorm\ArrayShape;
use Majie\Fills\Fill\AttributeClass\Doc;

trait EnumTrait
{
    public static function values():array{
        return array_column(self::cases(), 'value');
    }

    #[ArrayShape([['name'=>'string','value'=>'mixed']])]
    public static function labelData():array{
       $data = [];
       $reflectedObj =  new \ReflectionEnum(self::class);
       if(!$reflectedObj->isBacked()){
            return  $data;
       }

        foreach (self::cases() as $case){
            $data[] = [
               'label'=>$case->label(),
                'value'=>$case->value
           ];
        }

       return $data;
    }


    public function label(): string
    {
        return $this->name;
    }



}