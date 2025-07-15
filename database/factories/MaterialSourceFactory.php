<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MaterialSource>
 */
class MaterialSourceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $renewable = $this->faker->randomElement(['yes', 'no']);
         $status = $this->faker->randomElement(['Approved', 'Disapproved', null]);
        return [
            // Basic Info
            'material_source_name' => $this->faker->company . ' Source',
            'access_road' => $this->faker->streetName,
            'directional_flow' => $this->faker->randomElement(['North', 'South', 'East', 'West']),
            'source_type' => $this->faker->randomElement(['River', 'Mountain']),
            'potential_uses' => $this->faker->words(3, true),
            'future_use_recommendation' => $this->faker->sentence,
            'province' => $this->faker->state,
            'municipality' => $this->faker->city,
            'barangay' => $this->faker->streetName,

            // Renewability
            'renewability' => $renewable,

            // Site & Equipment
            'processing_plant_info' => $this->faker->sentence,
            'observations' => $this->faker->paragraph,

            // Permit & Test
            'quarry_permit' => 'permits/' . $this->faker->uuid . '.pdf',
            'quarry_permit_date' => $this->faker->date(),
            'permittee_name' => $this->faker->name,
            'quality_test_attachment' => 'tests/' . $this->faker->uuid . '.pdf',
            'quality_test_date' => $this->faker->date(),
            'quality_test_result' => $this->faker->randomElement(['Passed', 'Failed']),

            // Metadata
            'prepared_by' => $this->faker->name,
            'user_id' => $this->faker->numberBetween(1, 10), // or auth users
            'user_id_validation' =>  $this->faker->randomElement([1, 2, 3]), // fixed to 3
            'reason_status' => $status === 'Disapproved' ? $this->faker->sentence() : null,
            'status' => $this->faker->randomElement(['Approved', 'Disapproved', null]),
                    'region' => $this->faker->randomElement([
            'NCR', 'NIR', 'CAR'
                    ]),

            'latitude' => $this->faker->randomFloat(7, 4.6, 21.3),    // 7 decimal places
            'longitude' => $this->faker->randomFloat(7, 116.9, 126.6),

        ];
    }
}
