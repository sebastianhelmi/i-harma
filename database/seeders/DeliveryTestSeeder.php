<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Project;
use App\Models\ItemCategory;
use App\Models\Inventory;
use App\Models\Spb;
use App\Models\SiteSpb;
use App\Models\Po;
use App\Models\PoItem;
use App\Models\ProcurementHistory;
use App\Models\Supplier;
use App\Models\Task;
use App\Models\WorkshopOutput;
use App\Models\WorkshopSpb;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DeliveryTestSeeder extends Seeder
{
    public function run(): void
    {
        // Get default supplier
        $defaultSupplier = Supplier::first();
        if (!$defaultSupplier) {
            throw new \Exception('No supplier found. Please run supplier seeder first.');
        }


        // 1. Create Delivery Staff User
        $deliveryStaff = User::firstOrCreate(
            ['email' => 'delivery@example.com'],
            [
                'name' => 'Staff Delivery',
                'password' => Hash::make('password'),
                'role' => 'Delivery'
            ]
        );

        // 2. Create Item Category
        $category = ItemCategory::firstOrCreate(
            ['name' => 'Material Konstruksi'],
            ['description' => 'Material untuk konstruksi bangunan']
        );

        // 3. Create Projects
        $projects = [
            [
                'name' => 'Proyek Site A Jakarta',
                'location' => 'Jakarta Utara',
                'status' => 'ongoing'
            ],
            [
                'name' => 'Proyek Site B Bekasi',
                'location' => 'Bekasi',
                'status' => 'ongoing'
            ]
        ];

        $projectTasks = [
            [
                'name' => 'Pembuatan Rangka Besi',
                'spb_type' => 'workshop'
            ],
            [
                'name' => 'Instalasi Pintu Security',
                'spb_type' => 'workshop'
            ],
            [
                'name' => 'Pemasangan Railing',
                'spb_type' => 'workshop'
            ],
            [
                'name' => 'Material Site',
                'spb_type' => 'site'
            ]
        ];

        // Create Workshop Items and Outputs
        $workshopItems = [
            [
                'explanation_items' => 'Rangka Besi Custom',
                'unit' => 'Set',
                'quantity' => 5,
                'task_name' => 'Pembuatan Rangka Besi'
            ],
            [
                'explanation_items' => 'Pintu Besi Security',
                'unit' => 'Unit',
                'quantity' => 3,
                'task_name' => 'Instalasi Pintu Security'
            ],
            [
                'explanation_items' => 'Railing Tangga',
                'unit' => 'Meter',
                'quantity' => 10,
                'task_name' => 'Pemasangan Railing'
            ]
        ];

        $createdProjects = [];
        foreach ($projects as $project) {
            // Create Project
            $createdProject = Project::firstOrCreate(
                ['name' => $project['name']],
                [
                    'description' => 'Proyek konstruksi di ' . $project['location'],
                    'start_date' => now(),
                    'end_date' => now()->addMonths(6),
                    'status' => $project['status'],
                    'manager_id' => 1
                ]
            );

            // Create Tasks for each project
            foreach ($projectTasks as $taskData) {
                Task::create([
                    'name' => $taskData['name'],
                    'description' => 'Task ' . $taskData['spb_type'] . ' untuk ' . $taskData['name'],
                    'project_id' => $createdProject->id,
                    'assigned_to' => $deliveryStaff->id,
                    'due_date' => now()->addDays(30),
                    'status' => 'completed'
                ]);
            }

            $createdProjects[] = $createdProject;
        }


        // 4. Create Sample Inventory Items
        $items = [
            [
                'name' => 'Semen',
                'unit' => 'Sak',
                'initial_stock' => 1000
            ],
            [
                'name' => 'Besi Beton',
                'unit' => 'Batang',
                'initial_stock' => 500
            ],
            [
                'name' => 'Bata Merah',
                'unit' => 'Pcs',
                'initial_stock' => 5000
            ]
        ];

        $inventoryItems = [];
        foreach ($items as $item) {
            $inventoryItems[] = Inventory::firstOrCreate(
                ['item_name' => $item['name']],
                [
                    'category' => 'Material',
                    'quantity' => $item['initial_stock'],
                    'initial_stock' => $item['initial_stock'],
                    'item_category_id' => $category->id,
                    'unit' => $item['unit'],
                    'unit_price' => rand(10000, 100000),
                    'added_by' => $deliveryStaff->id
                ]
            );
        }
        foreach ($createdProjects as $project) {
            // Get project tasks
            $projectTasks = Task::where('project_id', $project->id)->get();

            // Create Site SPB with task
            $siteTask = $projectTasks->first(fn($task) => str_contains($task->name, 'Material Site'));
            $siteSPB = Spb::create([
                'spb_number' => Spb::generateSpbNumber(),
                'spb_date' => now(),
                'project_id' => $project->id,
                'task_id' => $siteTask->id,
                'requested_by' => $deliveryStaff->id,
                'item_category_id' => $category->id,
                'category_entry' => 'site',
                'status' => 'approved',
                'approved_at' => now(),
                'approved_by' => $deliveryStaff->id
            ]);

            // Create Workshop SPBs for each workshop task
            $workshopTasks = $projectTasks->filter(
                fn($task) =>
                str_contains($task->name, 'Rangka') ||
                    str_contains($task->name, 'Pintu') ||
                    str_contains($task->name, 'Railing')
            );

            foreach ($workshopTasks as $task) {
                $workshopSPB = Spb::create([
                    'spb_number' => Spb::generateSpbNumber(),
                    'spb_date' => now(),
                    'project_id' => $project->id,
                    'task_id' => $task->id,
                    'requested_by' => $deliveryStaff->id,
                    'item_category_id' => $category->id,
                    'category_entry' => 'workshop',
                    'status' => 'approved',
                    'approved_at' => now(),
                    'approved_by' => $deliveryStaff->id
                ]);

                // Workshop items for this specific task
                $matchingItem = collect($workshopItems)->first(function ($item) use ($task) {
                    return str_contains($task->name, $item['task_name']) ||
                        str_contains($item['task_name'], $task->name);
                });

                if ($matchingItem) {
                    // Create Workshop SPB Item
                    $workshopSpbItem = WorkshopSpb::create([
                        'spb_id' => $workshopSPB->id,
                        'explanation_items' => $matchingItem['explanation_items'],
                        'unit' => $matchingItem['unit'],
                        'quantity' => $matchingItem['quantity']
                    ]);

                    // Create Workshop Output
                    WorkshopOutput::create([
                        'task_id' => $task->id,
                        'spb_id' => $workshopSPB->id,
                        'workshop_spb_id' => $workshopSpbItem->id,
                        'quantity_produced' => $matchingItem['quantity'],
                        'status' => 'completed',
                        'notes' => 'Hasil produksi workshop untuk testing',
                        'created_by' => $deliveryStaff->id,
                        'completed_at' => now()
                    ]);
                }
            }

            // Create Site Items for SPB
            foreach ($inventoryItems as $index => $item) {
                SiteSpb::create([
                    'spb_id' => $siteSPB->id,
                    'item_name' => $item->item_name,
                    'unit' => $item->unit,
                    'quantity' => ($index + 1) * 10,
                    'information' => 'Diperlukan untuk konstruksi'
                ]);
                ProcurementHistory::create([
                    'spb_id' => $siteSPB->id,
                    'document_type' => 'spb',
                    'document_number' => $siteSPB->spb_number,
                    'status' => 'approved',
                    'actor' => $deliveryStaff->id,
                    'description' => "SPB Site untuk {$project->name} telah dibuat dan disetujui"
                ]);
            }
        }

        // Create PO for site SPB
        $po = Po::create([
            'po_number' => 'PO' . now()->format('Ymd') . sprintf('%03d', rand(1, 999)),
            'order_date' => now(),
            'supplier_id' => $defaultSupplier->id,
            'company_name' => $defaultSupplier->name,
            'spb_id' => $siteSPB->id,
            'created_by' => $deliveryStaff->id,
            'status' => 'completed',
            'total_amount' => 0 // Will be calculated later
        ]);

        // Create PO Items and update total
        $totalAmount = 0;
        foreach ($inventoryItems as $item) {
            $amount = $item->unit_price * (($index + 1) * 10);
            $totalAmount += $amount;

            PoItem::create([
                'po_id' => $po->id,
                'item_name' => $item->item_name,
                'spb_id' => $siteSPB->id,
                'quantity' => ($index + 1) * 10,
                'unit' => $item->unit,
                'unit_price' => $item->unit_price,
                'total_price' => $amount
            ]);
        }

        // Update PO total
        $po->update(['total_amount' => $totalAmount]);

        // Create procurement history for PO
        ProcurementHistory::create([
            'po_id' => $po->id,
            'document_type' => 'po',
            'document_number' => $po->po_number,
            'status' => 'ordered',
            'actor' => $deliveryStaff->id,
            'description' => "PO untuk SPB {$siteSPB->spb_number} telah dibuat"
        ]);

        // Create various status histories
        $statuses = [
            ['status' => 'pending', 'desc' => 'SPB menunggu persetujuan'],
            ['status' => 'approved', 'desc' => 'SPB telah disetujui'],
            ['status' => 'ordered', 'desc' => 'PO telah dibuat'],
            ['status' => 'completed', 'desc' => 'Barang telah diterima']
        ];

        foreach ($statuses as $status) {
            ProcurementHistory::create([
                'spb_id' => $siteSPB->id,
                'document_type' => 'spb',
                'document_number' => $siteSPB->spb_number,
                'status' => $status['status'],
                'actor' => $deliveryStaff->id,
                'description' => $status['desc'],
                'created_at' => now()->subHours(rand(1, 24)) // Random time within last 24 hours
            ]);
        }


        // Output success message
        $this->command->info('Test data berhasil dibuat:');
        $this->command->line('- Login Delivery Staff:');
        $this->command->line('  Email: delivery@example.com');
        $this->command->line('  Password: password');
        $this->command->line('- ' . count($createdProjects) . ' Projects dibuat');
        $this->command->line('- Task workshop untuk setiap project dibuat');
        $this->command->line('- ' . count($inventoryItems) . ' Inventory Items dibuat');
        $this->command->line('- SPB dan PO sudah dibuat untuk setiap project');
    }
}
