<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'view_any_page', 'view_page', 'create_page', 'update_page', 'delete_page',
            'view_any_document', 'view_document', 'create_document', 'update_document', 'delete_document',
            'view_any_vacancy', 'view_vacancy', 'create_vacancy', 'update_vacancy', 'delete_vacancy',
            'view_any_team_member', 'view_team_member', 'create_team_member', 'update_team_member', 'delete_team_member',
            'view_any_service_category', 'view_service_category', 'create_service_category', 'update_service_category', 'delete_service_category',
            'view_any_service', 'view_service', 'create_service', 'update_service', 'delete_service',
            'view_any_product', 'view_product', 'create_product', 'update_product', 'delete_product',
            'view_any_quotation', 'view_quotation', 'create_quotation', 'update_quotation', 'delete_quotation',
            'view_any_quotation_request', 'view_quotation_request', 'update_quotation_request',
            'approve_quotation_request', 'reject_quotation_request', 'send_quotation_request_to_erp', 'mark_quotation_request_spam',
            'view_any_order', 'view_order', 'create_order', 'update_order', 'delete_order',
            'view_any_print_job', 'view_print_job', 'create_print_job', 'update_print_job', 'delete_print_job',
            'view_any_artwork_file', 'view_artwork_file', 'create_artwork_file', 'update_artwork_file', 'delete_artwork_file',
            'view_any_organization', 'view_organization', 'create_organization', 'update_organization', 'delete_organization',
            'view_any_customer_profile', 'view_customer_profile', 'create_customer_profile', 'update_customer_profile', 'delete_customer_profile',
            'view_any_user', 'view_user', 'create_user', 'update_user', 'delete_user',
            'view_any_role', 'view_role', 'create_role', 'update_role', 'delete_role',
            'view_any_registration_period', 'view_registration_period', 'create_registration_period', 'update_registration_period', 'delete_registration_period',
            'view_any_student_enrollment', 'view_student_enrollment', 'update_student_enrollment',
            'approve_student_enrollment', 'reject_student_enrollment',
            'page_site_settings', 'page_homepage_editor', 'page_footer_editor', 'page_erp_dashboard',
            'push_quotation_to_erp', 'push_order_to_erp', 'sync_erp_catalog',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        $rolePermissions = [
            'super_admin' => $permissions,
            'content_manager' => [
                'view_any_page', 'view_page', 'create_page', 'update_page', 'delete_page',
                'view_any_document', 'view_document', 'create_document', 'update_document', 'delete_document',
                'view_any_vacancy', 'view_vacancy', 'create_vacancy', 'update_vacancy', 'delete_vacancy',
                'view_any_team_member', 'view_team_member', 'create_team_member', 'update_team_member', 'delete_team_member',
                'page_site_settings',
                'page_homepage_editor',
                'page_footer_editor',
                'view_any_service', 'view_service', 'update_service',
                'view_any_product', 'view_product', 'update_product',
                'view_any_registration_period', 'view_registration_period', 'create_registration_period', 'update_registration_period', 'delete_registration_period',
                'view_any_student_enrollment', 'view_student_enrollment', 'update_student_enrollment',
                'approve_student_enrollment', 'reject_student_enrollment',
            ],
            'training_admin' => [
                'view_any_registration_period', 'view_registration_period', 'create_registration_period', 'update_registration_period', 'delete_registration_period',
                'view_any_student_enrollment', 'view_student_enrollment', 'update_student_enrollment',
                'approve_student_enrollment', 'reject_student_enrollment',
            ],
            'quotation_admin' => [
                'view_any_quotation_request', 'view_quotation_request', 'update_quotation_request',
                'approve_quotation_request', 'reject_quotation_request', 'send_quotation_request_to_erp', 'mark_quotation_request_spam',
            ],
            'sales_officer' => [
                'view_any_quotation', 'view_quotation', 'create_quotation', 'update_quotation',
                'view_any_quotation_request', 'view_quotation_request', 'update_quotation_request',
                'approve_quotation_request', 'reject_quotation_request', 'send_quotation_request_to_erp', 'mark_quotation_request_spam',
                'view_any_customer_profile', 'view_customer_profile', 'create_customer_profile', 'update_customer_profile',
                'view_any_organization', 'view_organization', 'create_organization', 'update_organization',
                'view_any_service', 'view_service',
                'view_any_order', 'view_order', 'update_order',
            ],
            'production_manager' => [
                'view_any_print_job', 'view_print_job', 'create_print_job', 'update_print_job',
                'view_any_quotation', 'view_quotation',
                'view_any_artwork_file', 'view_artwork_file', 'create_artwork_file', 'update_artwork_file',
                'view_any_customer_profile', 'view_customer_profile',
            ],
            'finance_officer' => [
                'view_any_quotation', 'view_quotation', 'update_quotation',
                'view_any_quotation_request', 'view_quotation_request', 'update_quotation_request',
                'send_quotation_request_to_erp',
                'view_any_order', 'view_order', 'update_order',
                'push_quotation_to_erp', 'push_order_to_erp', 'sync_erp_catalog',
                'page_erp_dashboard',
            ],
            'customer' => [],
        ];

        foreach ($rolePermissions as $roleName => $perms) {
            $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
            $role->syncPermissions($perms);
        }
    }
}
