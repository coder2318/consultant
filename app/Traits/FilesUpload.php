<?php

namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Support\Facades\File;

trait FilesUpload
{
    public function fileUpload($params, $path, $model = null)
    {
        $pathFile = $path.'/'.Carbon::now()->format('Y-m');
        $folder = storage_path().'/app/public/'. $pathFile;
        if (!File::exists($folder)) {
            File::makeDirectory($folder, 0775, true, true);
        }

        if (isset($params['file']) && $params['file']) {
            if ($model) {
                if (isset($model->file)){
                    unlink($model->file);
                }
            }
            $fileName = time() . '.' . $params['file']->extension();
            $params['file']->storeAs('public/'.$pathFile, $fileName);
            $params['file'] ='storage/'.$pathFile.'/'.$fileName;

        }

        if (isset($params['icon']) && $params['icon']) {
            if ($model) {
                if (isset($model->icon)){
                    unlink($model->icon);
                }
            }
            $fileName = time() . '.' . $params['icon']->extension();
            $params['icon']->storeAs('public/'.$pathFile, $fileName);
            $params['icon'] ='storage/'.$pathFile.'/'.$fileName;

        }


        if (isset($params['files']) && count($params['files'])) {
            if ($model) {
                if ($model->files) {
                    $i = explode(',', $model->getOriginal('files'));
                    foreach ($i as $item) {
                        unlink($item);
                    }
                }
            }
            $fileNames = [];
            foreach ($params['files'] as $key => $item) {
                $fileName = time() . '_' . $key . '.' . $item->extension();
                $item->storeAs('public/'.$pathFile, $fileName);
                $fileNames[] = 'storage/'.$pathFile.'/'.$fileName;
            }

            $fileString = implode(',', $fileNames);
            $params['files'] = $fileString;
        }

        return $params;
    }
}
