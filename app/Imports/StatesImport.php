<?php

namespace App\Imports;

use App\Models\CityState;
use Maatwebsite\Excel\Concerns\ToModel;
use App\Models\State;
class StatesImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new State([
            'name' => $row[0],
            'city_id' => $row[1],
            'status' => 1,
        ]);
    }
}
