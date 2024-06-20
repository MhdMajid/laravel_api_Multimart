<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\Property; 
use App\Models\Size; 
use App\Models\Color; 
class Products extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i=0; $i < 36; $i++) {
            $x=($i%6) +1;
            DB::table('items')->insert([
            'name'=>Str::random(8),
            'price'=>'99',
            'product_image'=>$x.'.jpg',
            'description'=>'Lorem ipsum dolor sit amet consectetur adipisicing elit.',
            'category_id'=>$x,
            'category_name'=>DB::table('categories')->where('id',$x)->first()->name,
            'is_discount'=>'1',
            'discount'=>'1'
        ]);
        $propety = Property::create([
            'item_id'=>$i+1,
            'quantity'=>20
        ]);
        $color = Color::create([
            'property_id' =>$propety->id,
            'color' => 'white'
        ]);
        $color = Color::create([
            'property_id' =>$propety->id,
            'color' => 'red'
        ]);
        $color = Color::create([
            'property_id' =>$propety->id,
            'color' => 'yellow'
        ]);
        $color = Color::create([
            'property_id' =>$propety->id,
            'color' => 'orange'
        ]);
        $size = Size::create([
            'property_id' =>$propety->id,
            'size' => 'large'
        ]);
        $size = Size::create([
            'property_id' =>$propety->id,
            'size' => 'Xlarge'
        ]);
        $size = Size::create([
            'property_id' =>$propety->id,
            'size' => 'XXlarge'
        ]);
        $size = Size::create([
            'property_id' =>$propety->id,
            'size' => 'small'
        ]);

    }
}
}