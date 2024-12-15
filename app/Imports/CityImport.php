<?php

namespace App\Imports;

use App\Models\City;
use Maatwebsite\Excel\Concerns\ToModel;
use App\Models\State;
class CityImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {

        // Create the city with the associated state
        return new City([
            'name' => $row[0],
            'country' => $row[1],
            'status' => 1,
        ]);
    }
}
