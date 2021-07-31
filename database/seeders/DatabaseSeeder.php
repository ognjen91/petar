<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use App\Models\ExcursionType;
use App\Models\Excursion;
use App\Models\Reservation;
use App\Models\Station;

class DatabaseSeeder extends Seeder
{
    private $booker1;
    private $booker2;
    private $stations;

    private $reservationSeatsSum;

    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $superAdminRole = Role::create(['name' => 'Super Admin']);
        $adminRole = Role::create(['name' => 'Admin']);
        $bookerRole = Role::create(['name' => 'Booker']);
        
        $manageExcursionsPermission = Permission::create(['name' => 'manage excursions']);
        $bookExcursionsPermission = Permission::create(['name' => 'book excursions']);
        
        $adminRole->givePermissionTo($manageExcursionsPermission);
        $bookerRole->givePermissionTo($bookExcursionsPermission);
        
        
        $superAdmin = User::factory()->create(
            [
                'name' => config('app.superadmin_name'),
                'email' => config('app.superadmin_email'),
                'password' => config('app.superadmin_password')
            ]
        );
            
        $admin = User::factory()->create(
            [
                'name' => config('app.admin_name'),
                'email' => config('app.admin_email'),
                'password' => config('app.admin_password'),
            ]
        );
            
        $superAdmin->assignRole('Super Admin');
        $admin->assignRole('Admin');

        $booker1 = User::factory()->create(
            [
                'name' => 'John Doe 1',
                'email' => 'petarbooker1@gmail.com',
                'password' => bcrypt(12345678)
            ]
        );

        $booker2 = User::factory()->create(
            [
                'name' => 'John Doe 2',
                'email' => 'petarbooker2@gmail.com',
                'password' => bcrypt(12345678)
            ]
        );

        $superAdmin->assignRole('Super Admin');
        $admin->assignRole('Admin');

        $booker1 = User::factory()->create(
            [
                'name' => 'John Doe 1',
                'email' => 'petarbooker1@gmail.com',
                'password' => bcrypt(12345678)
            ]
        );

        $booker2 = User::factory()->create(
            [
                'name' => 'John Doe 2',
                'email' => 'petarbooker2@gmail.com',
                'password' => bcrypt(12345678)
            ]
        );

        $booker1->assignRole('Booker');
        $booker2->assignRole('Booker');

        $this->booker1 = $booker1;
        $this->booker2 = $booker2;

        //create stations
        $this->stations = Station::factory(20)->create();

        //create excursion types
        ExcursionType::factory(8)->create()->each(function($excType){
            $excursions = Excursion::factory(5)->create([
                ])->each(function($excursion){
                    $reservations = Reservation::factory(5)->create([
                        'booker_id' => [$this->booker1->id, $this->booker2->id][rand(0,1)],
                        'excursion_id' => $excursion->id,
                        'station_id' => $this->stations->random(1)[0]
                    ]);

                    // $excursion->total_seats -= $reservations->sum('seats');
                    $excursion->save();
            });


            $excType->excursions()->saveMany($excursions);
        });



    }
}
