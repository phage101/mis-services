<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\RequestType;
use Illuminate\Database\Seeder;

class RequestTypeCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            '🔐 User & Access' => [
                'Account Creation',
                'Password Reset',
                'Role Assignment',
                'Account Deactivation',
                'Email Setup',
                'Folder Access',
            ],
            '📄 Data & Reports' => [
                'Data Extraction',
                'Report Generation',
                'Data Correction',
                'Database Access',
                'Backup Restore',
                'Data Migration',
            ],
            '🖨️ Document Services' => [
                'Bulk Printing',
                'Document Scanning',
                'PDF Conversion',
                'File Merging',
                'Document Formatting',
                'QR Generation',
            ],
            '🌐 Website Support' => [
                'Content Update',
                'File Upload',
                'Form Creation',
                'Page Update',
                'Compliance Update',
            ],
            '📊 Dashboards' => [
                'Dashboard Setup',
                'Report Scheduling',
                'KPI Reports',
                'Data Visualization',
            ],
            '🔐 Security Support' => [
                'Access Approval',
                'Access Audit',
                'Log Extraction',
                'Incident Support',
                'Privacy Support',
            ],
            '🎥 Multimedia' => [
                'Photo Coverage',
                'Video Coverage',
                'Photo Editing',
                'Video Editing',
                'Layout Design',
                'Infographic Design',
            ],
            '📅 Activity-Based' => [
                'Event Support',
                'Meeting Setup',
                'Hybrid Setup',
                'Livestream Support',
                'Presentation Support',
                'AV Coordination',
            ],
            '🏛️ E-Government' => [
                'HRIS Support',
                'DTR Support',
                'Payroll Support',
                'System Orientation',
                'UAT Support',
            ],
            '📚 Training' => [
                'User Training',
                'System Orientation',
                'Manual Creation',
                'SOP Creation',
            ],
            '🔄 Automation' => [
                'Form Digitization',
                'Workflow Setup',
                'Approval Routing',
                'Process Automation',
            ],
        ];

        foreach ($data as $typeName => $categories) {
            $type = RequestType::create(['name' => $typeName]);
            foreach ($categories as $categoryName) {
                Category::create([
                    'request_type_id' => $type->id,
                    'name' => $categoryName,
                ]);
            }
        }
    }
}
