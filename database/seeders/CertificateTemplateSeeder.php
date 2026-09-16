<?php

namespace Database\Seeders;

use App\Models\CertificateTemplate;
use Illuminate\Database\Seeder;

class CertificateTemplateSeeder extends Seeder
{
    public function run(): void
    {
        $defaultLandscape = CertificateTemplate::getDefaultPlaceholdersConfig('landscape');

        $templates = [
            [
                'name' => 'Official Certificate of Membership',
                'code' => 'CERT-MEM-STD',
                'certificate_type' => 'membership',
                'orientation' => 'landscape',
                'placeholders_config' => $defaultLandscape,
                'is_active' => true,
            ],
            [
                'name' => 'Certificate of Training Completion',
                'code' => 'CERT-TRN-COMP',
                'certificate_type' => 'training_completion',
                'orientation' => 'landscape',
                'placeholders_config' => $defaultLandscape,
                'is_active' => true,
            ],
            [
                'name' => 'Certificate of Participation',
                'code' => 'CERT-PARTICIPATION',
                'certificate_type' => 'participation',
                'orientation' => 'landscape',
                'placeholders_config' => $defaultLandscape,
                'is_active' => true,
            ],
            [
                'name' => 'Professional Electric Vehicle Certification',
                'code' => 'CERT-PRO-EV',
                'certificate_type' => 'professional_certification',
                'orientation' => 'landscape',
                'placeholders_config' => $defaultLandscape,
                'is_active' => true,
            ],
            [
                'name' => 'Certificate of Recognition & Appreciation',
                'code' => 'CERT-RECOGNITION',
                'certificate_type' => 'recognition_appreciation',
                'orientation' => 'landscape',
                'placeholders_config' => $defaultLandscape,
                'is_active' => true,
            ],
        ];

        foreach ($templates as $t) {
            CertificateTemplate::updateOrCreate(['code' => $t['code']], $t);
        }
    }
}
