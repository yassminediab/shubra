<?php

namespace App\Transformers;


use App\Slider;
use Saad\Fractal\Transformers\TransformerAbstract;

class SliderTransformer extends TransformerAbstract
{
    protected $defaultIncludes = ['id','title', 'button_title', 'image','url'];

    public function includeId(Slider $slider)
    {
        return $this->primitive($slider->id);
    }

    public function includeTitle(Slider $slider)
    {
        return $this->primitive($slider->getTranslatedAttribute('title', app()->getLocale()));
    }

    public function includeImage(Slider $slider)
    {
        return $this->primitive($slider->image);
    }

    public function includeButtonTitle(Slider $slider)
    {
        return $this->primitive($slider->getTranslatedAttribute('button_title', app()->getLocale()));
    }

    public function includeUrl(Slider $slider)
    {
        return $this->primitive($slider->url);
    }

}
