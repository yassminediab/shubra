<?php
use TCG\Voyager\Facades\Voyager;

function getImageUrl($image) {
    if(str_contains($image,'https://shubra.online')) {
        return $image;
    }
    return Voyager::image($image);
}
