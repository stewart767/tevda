<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Seeder;

class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            // Membership
            ['name' => 'View Members', 'slug' => 'members.view', 'group' => 'membership'],
            ['name' => 'Review Membership Applications', 'slug' => 'members.review', 'group' => 'membership'],
            ['name' => 'Approve/Reject Members', 'slug' => 'members.approve', 'group' => 'membership'],
            ['name' => 'Manage Member Documents', 'slug' => 'members.documents', 'group' => 'membership'],
            ['name' => 'Manage Membership Categories', 'slug' => 'members.categories', 'group' => 'membership'],
            
            // Finance
            ['name' => 'View Payments & Invoices', 'slug' => 'finance.view', 'group' => 'finance'],
            ['name' => 'Verify Payments', 'slug' => 'finance.verify', 'group' => 'finance'],
            ['name' => 'Manage Fees', 'slug' => 'finance.fees', 'group' => 'finance'],
            ['name' => 'Generate Receipts', 'slug' => 'finance.receipts', 'group' => 'finance'],
            
            // Training
            ['name' => 'Manage Training Programmes', 'slug' => 'training.programmes', 'group' => 'training'],
            ['name' => 'Manage Training Sessions', 'slug' => 'training.sessions', 'group' => 'training'],
            ['name' => 'Record Attendance', 'slug' => 'training.attendance', 'group' => 'training'],
            ['name' => 'Manage Assessments & Results', 'slug' => 'training.assessments', 'group' => 'training'],
            
            // Certificates
            ['name' => 'Manage Certificate Templates', 'slug' => 'certificates.templates', 'group' => 'certificates'],
            ['name' => 'Issue Certificates', 'slug' => 'certificates.issue', 'group' => 'certificates'],
            ['name' => 'Revoke/Reissue Certificates', 'slug' => 'certificates.manage', 'group' => 'certificates'],
            
            // Opportunities & Projects
            ['name' => 'Manage Opportunities', 'slug' => 'opportunities.manage', 'group' => 'opportunities'],
            ['name' => 'Review Opportunity Applications', 'slug' => 'opportunities.review', 'group' => 'opportunities'],
            ['name' => 'Manage Projects', 'slug' => 'projects.manage', 'group' => 'projects'],
            ['name' => 'Manage Beneficiaries', 'slug' => 'projects.beneficiaries', 'group' => 'projects'],
            
            // Governance & Partners
            ['name' => 'Manage Leadership & Governance', 'slug' => 'governance.manage', 'group' => 'governance'],
            ['name' => 'Manage Branches', 'slug' => 'branches.manage', 'group' => 'governance'],
            ['name' => 'Manage Partners', 'slug' => 'partners.manage', 'group' => 'partners'],
            
            // CMS & Communications
            ['name' => 'Manage News & Events', 'slug' => 'cms.news', 'group' => 'cms'],
            ['name' => 'Manage Resources & Documents', 'slug' => 'cms.resources', 'group' => 'cms'],
            ['name' => 'Manage Contact & Enquiries', 'slug' => 'cms.contact', 'group' => 'cms'],
            ['name' => 'Manage Complaints & Whistleblower', 'slug' => 'complaints.manage', 'group' => 'complaints'],
            
            // System & Audit
            ['name' => 'Manage Users & Roles', 'slug' => 'users.manage', 'group' => 'system'],
            ['name' => 'Manage Site Settings', 'slug' => 'settings.manage', 'group' => 'system'],
            ['name' => 'View Audit Logs & Reports', 'slug' => 'audit.view', 'group' => 'audit'],
        ];

        foreach ($permissions as $perm) {
            Permission::updateOrCreate(['slug' => $perm['slug']], $perm);
        }

        $allPermissionSlugs = array_column($permissions, 'slug');

        $roles = [
            [
                'name' => 'Super Administrator',
                'slug' => 'super-admin',
                'description' => 'Unrestricted full access across all platform modules and system configuration.',
                'permissions' => array_merge(['*'], $allPermissionSlugs),
                'is_system' => true,
            ],
            [
                'name' => 'Membership Officer',
                'slug' => 'membership-officer',
                'description' => 'Manages member applications, documents, verification, and membership cards.',
                'permissions' => ['members.view', 'members.review', 'members.approve', 'members.documents', 'certificates.issue', 'finance.view'],
                'is_system' => true,
            ],
            [
                'name' => 'Finance Officer',
                'slug' => 'finance-officer',
                'description' => 'Manages membership and training fee configurations, invoices, and payment verifications.',
                'permissions' => ['finance.view', 'finance.verify', 'finance.fees', 'finance.receipts', 'members.view'],
                'is_system' => true,
            ],
            [
                'name' => 'Training Officer',
                'slug' => 'training-officer',
                'description' => 'Manages programmes, course modules, training sessions, attendance, assessments, and training certificates.',
                'permissions' => ['training.programmes', 'training.sessions', 'training.attendance', 'training.assessments', 'certificates.issue', 'members.view'],
                'is_system' => true,
            ],
            [
                'name' => 'Project Officer',
                'slug' => 'project-officer',
                'description' => 'Manages EV projects, beneficiary intakes, shortlisting, and asset tracking.',
                'permissions' => ['projects.manage', 'projects.beneficiaries', 'members.view', 'partners.manage'],
                'is_system' => true,
            ],
            [
                'name' => 'Opportunity Officer',
                'slug' => 'opportunity-officer',
                'description' => 'Manages grants, finance, employment, and business opportunities and applicant review.',
                'permissions' => ['opportunities.manage', 'opportunities.review', 'members.view'],
                'is_system' => true,
            ],
            [
                'name' => 'Communications Officer',
                'slug' => 'communications-officer',
                'description' => 'Manages news announcements, events, resources, website CMS, and public inquiries.',
                'permissions' => ['cms.news', 'cms.resources', 'cms.contact', 'partners.manage'],
                'is_system' => true,
            ],
            [
                'name' => 'Governance Officer',
                'slug' => 'governance-officer',
                'description' => 'Manages leadership profiles, committee structures, branch hierarchy, and confidential complaints.',
                'permissions' => ['governance.manage', 'branches.manage', 'complaints.manage', 'cms.resources'],
                'is_system' => true,
            ],
            [
                'name' => 'Auditor',
                'slug' => 'auditor',
                'description' => 'Read-only access to audit logs, compliance records, and administrative reports.',
                'permissions' => ['audit.view', 'members.view', 'finance.view'],
                'is_system' => true,
            ],
            [
                'name' => 'Member',
                'slug' => 'member',
                'description' => 'Registered driver, associate, honorary, or institutional member accessing the Member Portal.',
                'permissions' => [],
                'is_system' => true,
            ],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(['slug' => $role['slug']], $role);
        }
    }
}
