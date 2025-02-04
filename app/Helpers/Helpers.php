<?php

if (!function_exists('getTenantSyncDataJob')) {
    function getTenantSyncDataJob(\Illuminate\Support\Collection|\Illuminate\Database\Eloquent\Model $data): array
    {
        $id = ['id' => $data->id];
        $data = collect($data->toArray())->except('id')->toArray();
        return ['id' => $id, 'data' => $data];
    }
}
