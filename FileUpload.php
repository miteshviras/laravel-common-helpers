<?php
function uploadFile($file, $path)
{

    if (isset($file)) {
        if (!file_exists(public_path('storage'))) {
            Artisan::call('storage:link');
        }
        $file_name = time() . rand(1000, 9999) . "_" . $file->getClientOriginalName();
        $explode = explode('.', $file_name);
        $ext = "." . last($explode);
        array_pop($explode);
        $file_name = implode('_', $explode);
        $file_name = $path . "/" . strtolower(preg_replace('/[^a-zA-Z0-9_]/', '', $file_name)) . $ext;
        Storage::put($file_name, File::get($file));
        return $file_name;
    }
    return null;
}

function getFileUrl($file)
{

    if ($file != null) {

        if (in_array(config('filesystems.default'), ['local', 'public'])) {
            return Storage::url($file);
        } else {
            return Storage::url($file);
        }
    }
    return null;
}
