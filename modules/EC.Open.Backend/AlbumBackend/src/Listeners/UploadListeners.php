<?php
namespace iBrand\EC\Open\Backend\Album\Listeners;
use iBrand\EC\Open\Backend\Album\Models\Image;

class UploadListeners
{
    
    public function onUploaded($imgData,$category_id)
    {
        foreach ($imgData as $key=>$item)
        {
            Image::create([
                'url'=>$item['path'],
                'remote_url'=>$item['url'],
                'name'=>$item['name'],
                'category_id'=>$category_id
            ]);
        }
    }
    
    public function subscribe($events)
    {
        $events->listen(
            'image.uploaded',
            'iBrand\EC\Open\Backend\Album\Listeners\UploadListeners@onUploaded'
        );
    }
}