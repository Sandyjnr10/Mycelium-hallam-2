<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('submissions', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('user_id')->constrained('users')->index('submissions_user_id_index');
            $table->foreignId('team_id')->constrained('teams')->index('submissions_team_id_index');
            $table->year('year');
            $table->string('document')->nullable();
            $table->string('document_name')->nullable();  //needed? possibly useful for data scraping purposes, its a value we get our human scrapers to colect

            $table->foreignId('auditor_id')->constrained('teams')->nullable()->index('submissions_auditor_id_index');
            $table->string('auditor_confirmation')->nullable();  //what is this?should be 'approved or confirmed by carbon accountant' - this is when a compnay states who their carbon accountant was and we are able to confirm that with their carbon accountant that their numbers are correct

            $table->foreignId('carbon_accountant_id')->constrained('teams')->nullable()->index('submissions_carbon_accountant_id_index');
            
            $table->foreignId('sector_id')->nullable()->index();
            $table->foreignId('industry_id')->nullable()->index();
            $table->decimal('annual_revenue', 14, 2)->nullable()->index();
            $table->bigInteger('fulltime_employees')->nullable()->index();

            //scope 1
            $table->decimal('scope1', 14, 2)->nullable();

            //scope 2
            $table->decimal('scope2_market_based', 14, 2)->nullable();
            $table->decimal('scope2_location_based', 14, 2)->nullable();
            $table->decimal('scope2_not_defined', 14, 2)->nullable();
            
            //scope 3 (upstream)
            $table->decimal('scope3_purchased_goods_and_services', 14, 2)->nullable();
            $table->decimal('scope3_capital_goods', 14, 2)->nullable();
            $table->decimal('scope3_fuel_and_energy_use', 14, 2)->nullable();
            $table->decimal('scope3_upstream_transport_and_distribution', 14, 2)->nullable();
            $table->decimal('scope3_waste_generated_in_company_operations')->nullable();
            $table->decimal('scope3_business_travel', 14, 2)->nullable();
            $table->decimal('scope3_employee_commuting', 14, 2)->nullable();
            $table->decimal('scope3_upstream_leased_assets', 14, 2)->nullable();
            //scope 3 (downstream)
            $table->decimal('scope3_downstream_transport_and_distribution', 14, 2)->nullable();
            $table->decimal('scope3_processing_of_sold_products', 14, 2)->nullable();
            $table->decimal('scope3_end_use_of_sold_goods', 14, 2)->nullable();
            $table->decimal('scope3_waste_disposal_and_treatment_of_products', 14, 2)->nullable();
            $table->decimal('scope3_downstream_leased_assets', 14, 2)->nullable();
            $table->decimal('scope3_operation_of_franchises', 14, 2)->nullable();
            $table->decimal('scope3_operation_of_investment', 14, 2)->nullable();

            $table->decimal('other', 14, 2)->nullable(); //what is this? a hangover from the scraping process really - sometimes on a sustainability report the S3 category 
            $table->decimal('total_market_based', 14, 2)->nullable();
            $table->decimal('total_location_based', 14, 2)->nullable();
            $table->decimal('total_scope2_not_defined', 14, 2)->nullable();
            $table->decimal('total_scope2', 14, 2)->nullable();
            $table->decimal('total_scope3', 14, 2)->nullable();
            $table->decimal('total_all_scope', 14, 2)->nullable();
            $table->string('company_claim')->nullable(); //what is this?this is whether a company has signed up and took ownership of a scraped listing

            $table->bigInteger('intensity_per_annual_revenue')->nullable()->index();
            $table->bigInteger('intensity_per_fulltime_employees')->nullable()->index();
            $table->bigInteger('bbid')->nullable()->index(); //what is this? The Bloomberg Company ID is an 8-digit number that uniquely identifies a company on the Bloomberg system
            $table->bigInteger('isins')->nullable()->index(); //what is this? An ISIN is a 12-digit alphanumeric code that uniquely identifies a specific security.
            $table->bigInteger('mycelium_score')->nullable()->index();
            $table->bigInteger('confidence_score')->nullable()->index(); //same as above? confidence score only looks at transparency and completeness, aka the conf score cheat sheet calculation. We havent sorted the mycelium score algorithm but this will consider how carbon intensive that compnay or product is vs it peers. It will be a function of the conf score as well

            $table->bigInteger('standard_id')->nullable(); //what is this? these are carbon emission standards that the calculation was produced to. most common is GHG protocol

            $table->foreignId('checked_by')->constrained('users')->nullable()->index('submissions_checked_by_index');
            $table->dateTime('checked_date_time')->nullable();
            $table->string('approved_by_company')->nullable();  //what is this? not sure tbh, it could be that the figures have been directly API'd by the carbon accountant which is best data performance

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('submissions');
    }
};
