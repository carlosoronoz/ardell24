<?php

namespace Database\Seeders;

use App\Models\Tag;
use App\Models\User;
use App\Models\Brand;
use App\Models\Gender;
use App\Models\Company;
use App\Models\Country;
use App\Models\Location;
use App\Models\Department;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DataSeeder extends Seeder
{
    public function run()
    {
        $this->permissions();
        $this->company();        
        $this->userAdmin();
    }

    

    public function permissions()
    {
        $permissions = [            
            'Panel',
            'Cliente Básico',
            'Cliente Profesional',
            'Empresa',
            'Banner',
            'Usuarios',
            'Reseñas',
            'Artículos',
            'Ventas',
            'Promociones'
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }

    public  function company()
    {
        Company::create([
            'business_name' => 'Ardell - Uruguay',
            'email' => 'hola@ardell.com.uy',
            'phone' => '(598) 19-920-920',
            'address' => 'Av. Doctor Francisco Soca 1319',
            'logo' => 'image-company/logo.png',
            'instagram' => 'https://www.instagram.com/ardelluruguay/?hl=es-la',
            'credential' => '',
            'integrator_id' => 'dev_3473303deb0c11eb92c30242ac130004',
            'access_token_whatsapp' => '',
            'mobile_id' => '',
            'business_id' => '',
            'catalog_id' => '',
            'wa_business_id' => '',
            'graph_version' => 'v19.0',
            'production_mode' => false,
            'notes' => ''
        ]);
    }

    public function userAdmin()
    {
        $user = User::create([
            'type_passport' => 'Cédula',
            'passport' => '1111111-1',
            'name' => 'Carlos',
            'surname' => 'Oronoz',
            'email' => 'carlos@gmail.com',
            'password' => bcrypt('larry__88'),
            'image' => 'image-users/user.jpg',
            'phone' => '097540680'
        ]);

        $role = Role::create(['name' => 'Admin']);

        $permissions = Permission::pluck('id', 'id')->all();

        $role->syncPermissions($permissions);

        $user->assignRole([$role->id]);
    }
}
