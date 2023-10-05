<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;
use App\Models\MainCategories;
use App\Models\Category;
use App\Models\StatuOptions;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $permissions = [
            Permission::create(['name' => 'manage admin', 'guard_name' => 'sanctum']),
            Permission::create(['name' => 'manage user', 'guard_name' => 'sanctum']),
            Permission::create(['name' => 'manage others', 'guard_name' => 'sanctum']),
        ];

        // MANAGER ADMIN
        $adminRole = Role::create(['name' => 'Admin', 'guard_name' => 'sanctum']);
        $adminRole->syncPermissions($permissions);

        $adminUser = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'username' => 'admin01',
            'password' => bcrypt('password')
        ]);
        $adminUser->assignRole($adminRole);
        // END MANAGER ADMIN

        // MANAGER USER
        $userRole = Role::create(['name' => 'User', 'guard_name' => 'sanctum']);
        $userRole->syncPermissions([$permissions[1]]);

        $userUser = User::factory()->create([
            'name' => 'User',
            'email' => 'user@user.com',
            'username' => 'user01',
            'password' => bcrypt('password')
        ]);
        $userUser->assignRole($userRole);
        // END MANAGER USER

        // OTHERS ROLES
        $othersRole = Role::create(['name' => 'Others', 'guard_name' => 'sanctum']);
        $othersRole->syncPermissions([$permissions[2]]);
        // END OTHERS ROLES


         // MAIN CATEGORIES
       $mainCategories = [
    'Income',
    'Expenses',
    // Agrega más valores aquí si es necesario
];

foreach ($mainCategories as $category) {
    MainCategories::create([
        'title' => $category,
        'description' => 'Descripción de ' . $category, // Puedes personalizar la descripción según tus necesidades
    ]);
}
        // END MAIN CATEGORIES

// INCOME CATEGORIES
// Obtén la categoría principal "Income"
        $incomeCategory = MainCategories::where('title', 'Income')->first();

        // Agrega las categorías de ingresos y asigna la categoría principal "Income"
       $incomeCategories = [
    ['category_name' => 'Remuneracion', 'category_description' => 'Ingresos mensuales por trabajo'],
    ['category_name' => 'Ingresos por Ventas y/o servicios', 'category_description' => 'Ingresos generados por ventas de productos o servicios'],
    ['category_name' => 'Alquiler', 'category_description' => 'Ingresos por alquiler de propiedades'],
    ['category_name' => 'Inversiones', 'category_description' => 'Ingresos de inversiones financieras'],
    ['category_name' => 'Regalías', 'category_description' => 'Ingresos por derechos de autor o patentes'],
    ['category_name' => 'Consultoría', 'category_description' => 'Ingresos por servicios de consultoría'],
    ['category_name' => 'Capacitacion', 'category_description' => 'Ingresos por servicios de capacitación'],
    ['category_name' => 'Préstamos', 'category_description' => 'Ingresos por préstamos otorgados'],
    ['category_name' => 'Bonos', 'category_description' => 'Ingresos por bonos o premios'],
    ['category_name' => 'Otros', 'category_description' => 'Otras fuentes de ingresos'],
                            ];

        foreach ($incomeCategories as $incomeCategoryData) {
            Category::create([
                'category_name' => $incomeCategoryData['category_name'],
                'category_description' => $incomeCategoryData['category_description'],
                'main_category_id' => $incomeCategory->id,
                'user_id' => 1,
            ]);
        }
        // END INCOME CATEGORIES
        
        //EXPENSES CATEGORY
         $expensesCategory = MainCategories::where('title', 'Expenses')->first();
    $expensesCategories = [
    ['category_name' => 'Alquiler/Hipoteca', 'category_description' => 'Gastos relacionados con la vivienda'],
    ['category_name' => 'Alimentación', 'category_description' => 'Gastos en alimentos y comestibles'],
    ['category_name' => 'Transporte', 'category_description' => 'Gastos en transporte (combustible, transporte público, mantenimiento del vehículo)'],
    ['category_name' => 'Servicios Públicos', 'category_description' => 'Gastos en servicios públicos (agua, electricidad, gas, etc.)'],
    ['category_name' => 'Educación', 'category_description' => 'Gastos en educación (matrícula, libros, cursos)'],
    ['category_name' => 'Salud', 'category_description' => 'Gastos médicos y de atención médica'],
    ['category_name' => 'Entretenimiento', 'category_description' => 'Gastos en entretenimiento (cine, conciertos, eventos deportivos)'],
    ['category_name' => 'Compras', 'category_description' => 'Gastos en compras personales o comerciales'],
    ['category_name' => 'Impuestos', 'category_description' => 'Gastos en impuestos (ingresos, propiedad, ventas)'],
    ['category_name' => 'Préstamos', 'category_description' => 'Pagos de préstamos y deudas'],
    ['category_name' => 'Seguros', 'category_description' => 'Pagos de seguros (auto, hogar, vida, salud)'],
    ['category_name' => 'Viajes', 'category_description' => 'Gastos relacionados con viajes'],
    ['category_name' => 'Mantenimiento', 'category_description' => 'Gastos de mantenimiento de propiedades o equipo'],
    ['category_name' => 'Marketing y Publicidad', 'category_description' => 'Gastos en marketing y publicidad'],
    ['category_name' => 'Honorarios', 'category_description' => 'Gastos relacionados con honorarios'],
    ['category_name' => 'Otros', 'category_description' => 'Otros gastos no categorizados'],
    ];
          foreach ($expensesCategories as $expensesCategoryData) {
            Category::create([
                'category_name' => $expensesCategoryData['category_name'],
                'category_description' => $expensesCategoryData['category_description'],
                'main_category_id' => $expensesCategory->id,
                'user_id' => 1,
            ]);
        }
        // END EXPENSES CATEGORY

         // MAIN CATEGORIES
       $statusOptions = [
    'Paid',
    'Pending',
    'Unpaid',
    // Agrega más valores aquí si es necesario
];

foreach ($statusOptions as $statusDescription) {
    StatuOptions::create([
        'status_description' => $statusDescription,
       
    ]);
}
        // END MAIN CATEGORIES
    }
}