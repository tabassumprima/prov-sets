<?php

namespace Database\Factories;

use App\Models\Country;
use App\Models\Currency;
use App\Models\DatabaseConfig;
use App\Models\Organization;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrganizationFactory extends Factory
{

     /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Organization::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->unique()->company,
            'shortcode' => strtoupper(substr($this->faker->lexify('???'), 0, 3)),
            'sales_tax_number' => $this->faker->numerify('#########'),
            'ntn_number' => $this->faker->numerify('#########'),
            'tenant_id' => $this->faker->unique()->uuid,
            'date_format' => $this->faker->randomElement(['d M, Y', 'M d, Y', 'Y-m-d', 'm/d/Y']),
            'financial_year' => $this->faker->monthName . ' - ' . $this->faker->monthName,
            'type' => $this->faker->randomElement(['Life', 'General', 'Health']),
            'country_id' => CountryFactory::new()->create()->id,
            'currency_id' => CurrencyFactory::new()->create()->id,
            'address' => $this->faker->address,
            'database_config_id' => null,
            'agent_config' => null
        ];
    }
}
