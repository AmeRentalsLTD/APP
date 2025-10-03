<?php

namespace Database\Factories;

use App\Models\Vehicle;
use App\Models\VehicleInspection;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<VehicleInspection>
 */
class VehicleInspectionFactory extends Factory
{
    protected $model = VehicleInspection::class;

    public function definition(): array
    {
        return [
            'vehicle_id' => Vehicle::factory(),
            'type' => $this->faker->randomElement(VehicleInspection::TYPES),
            'inspected_at' => $this->faker->date(),
            'notes' => $this->faker->optional()->sentence(),
            'front_image_path' => 'vehicle-inspections/front/' . $this->faker->uuid . '.jpg',
            'rear_image_path' => 'vehicle-inspections/rear/' . $this->faker->uuid . '.jpg',
            'left_image_path' => 'vehicle-inspections/left/' . $this->faker->uuid . '.jpg',
            'right_image_path' => 'vehicle-inspections/right/' . $this->faker->uuid . '.jpg',
            'tyres_image_path' => 'vehicle-inspections/tyres/' . $this->faker->uuid . '.jpg',
            'windscreen_image_path' => 'vehicle-inspections/windscreen/' . $this->faker->uuid . '.jpg',
            'mirrors_image_path' => 'vehicle-inspections/mirrors/' . $this->faker->uuid . '.jpg',
        ];
    }
}
