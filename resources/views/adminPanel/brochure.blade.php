@extends('adminPanel.include')
@section('calculasTitle') Project Brochure @endsection

<style>
    .brochure-hero {
        position: relative;
        border-radius: 1.25rem;
        padding: 2rem;
        background:
            radial-gradient(circle at 85% 10%, rgba(255, 255, 255, 0.32), rgba(255, 255, 255, 0) 45%),
            linear-gradient(135deg, #0f3d61 0%, #1e6a8d 55%, #48a6c9 100%);
        color: #ffffff;
        overflow: hidden;
    }

    .brochure-chip {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        border-radius: 999px;
        padding: 0.3rem 0.7rem;
        background: rgba(255, 255, 255, 0.2);
        font-size: 0.82rem;
        font-weight: 600;
        margin-bottom: 0.9rem;
    }

    .brochure-hero h1 {
        margin-bottom: 0.55rem;
        font-weight: 800;
    }

    .brochure-hero p {
        margin-bottom: 0;
        max-width: 48rem;
        opacity: 0.95;
    }

    .brochure-card {
        border: 1px solid #dfe5ef;
        border-radius: 1rem;
        background: #ffffff;
        box-shadow: 0 0.45rem 1.25rem rgba(15, 61, 97, 0.08);
    }

    .brochure-card .card-header {
        border-bottom: 1px solid #e8edf3;
        border-radius: 1rem 1rem 0 0;
        background: #f8fbff;
        font-weight: 700;
    }

    .feature-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 1rem;
    }

    .feature-item {
        border: 1px solid #e2e8f0;
        border-radius: 0.85rem;
        padding: 1rem;
        background: #fbfdff;
        height: 100%;
    }

    .feature-item h6 {
        margin-bottom: 0.35rem;
        color: #0f3554;
        font-weight: 700;
    }

    .feature-item p {
        margin-bottom: 0;
        color: #334155;
    }

    .brochure-metrics {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 0.85rem;
    }

    .metric-box {
        border-radius: 0.85rem;
        background: #0f3554;
        color: #ffffff;
        padding: 1rem;
        text-align: center;
    }

    .metric-box .count {
        font-size: 1.45rem;
        font-weight: 800;
        line-height: 1;
        margin-bottom: 0.25rem;
    }

    .brochure-list li {
        margin-bottom: 0.45rem;
        color: #334155;
    }

    .brochure-footer {
        border-radius: 1rem;
        border: 1px dashed #9fb8cc;
        padding: 1rem 1.25rem;
        background: #f5faff;
        color: #0f3554;
    }

    @media print {
        .noprint {
            display: none !important;
        }

        .brochure-hero,
        .brochure-card,
        .feature-item,
        .metric-box,
        .brochure-footer {
            background: #ffffff !important;
            color: #111827 !important;
            box-shadow: none !important;
            border-color: #cbd5e1 !important;
        }

        .brochure-chip {
            background: #ffffff !important;
            border: 1px solid #cbd5e1 !important;
            color: #111827 !important;
        }

        .feature-item h6,
        .brochure-footer {
            color: #111827 !important;
        }

        .app-sidebar,
        .app-topbar,
        .action-toolbar {
            display: none !important;
        }

        .app-main,
        .app-content {
            padding: 0 !important;
            margin: 0 !important;
        }
    }
</style>

@section('calculasBody')
<div class="page-header noprint">
    <div>
        <div class="page-kicker">Marketing Material</div>
        <h1 class="page-title">Project Brochure</h1>
        <p class="page-copy">A one-page overview of features, security controls, and operational value of the Bank Manager platform.</p>
    </div>
    <div class="action-toolbar">
        <button class="btn btn-warning" onclick="window.print()"><i class="fa-solid fa-print"></i> Print Brochure</button>
    </div>
</div>

<div class="brochure-hero mb-4">
    <span class="brochure-chip"><i class="fa-solid fa-shield"></i> Banking Operations Platform</span>
    <h1>Bank Manager</h1>
    <p>
        Bank Manager streamlines branch operations with account workflows, debit and credit posting,
        daily reporting, employee controls, and branch-level branding in one secure Laravel application.
    </p>
</div>

<div class="brochure-card mb-4">
    <div class="card-header">Platform Snapshot</div>
    <div class="card-body">
        <div class="brochure-metrics">
            <div class="metric-box">
                <div class="count">4</div>
                <div>Role Levels</div>
            </div>
            <div class="metric-box">
                <div class="count">6+</div>
                <div>Core Modules</div>
            </div>
            <div class="metric-box">
                <div class="count">24/7</div>
                <div>Operations Visibility</div>
            </div>
            <div class="metric-box">
                <div class="count">100%</div>
                <div>Web-Based Access</div>
            </div>
        </div>
    </div>
</div>

<div class="brochure-card mb-4">
    <div class="card-header">Core Features</div>
    <div class="card-body">
        <div class="feature-grid">
            <div class="feature-item">
                <h6><i class="fa-solid fa-gauge-high me-2"></i>Daily Cash Dashboard</h6>
                <p>Track and update daily branch cash calculations with quick edit flows for operations teams.</p>
            </div>
            <div class="feature-item">
                <h6><i class="fa-solid fa-users me-2"></i>Account Management</h6>
                <p>Create, edit, view, list, and print account details with customer profile information and account type support.</p>
            </div>
            <div class="feature-item">
                <h6><i class="fa-solid fa-calculator me-2"></i>Debit and Credit Posting</h6>
                <p>Record daily debit and credit entries with editable records to keep running balances current and auditable.</p>
            </div>
            <div class="feature-item">
                <h6><i class="fa-solid fa-chart-column me-2"></i>Report Generation</h6>
                <p>Generate operational reports from transaction data for daily reconciliation and management review.</p>
            </div>
            <div class="feature-item">
                <h6><i class="fa-solid fa-user-tie me-2"></i>Employee Control</h6>
                <p>Manage branch employees with role-specific access for Super Admin, General Admin, Manager, and Cashier.</p>
            </div>
            <div class="feature-item">
                <h6><i class="fa-solid fa-gear me-2"></i>Server and Branch Settings</h6>
                <p>Configure branch identity, logos, banking metadata, and communication details from a central settings panel.</p>
            </div>
            <div class="feature-item">
                <h6><i class="fa-solid fa-id-card me-2"></i>Account Card Output</h6>
                <p>Generate compact account detail cards with printable layout support for branch desk operations.</p>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-12 col-lg-6">
        <div class="brochure-card h-100">
            <div class="card-header">Security and Access</div>
            <div class="card-body">
                <ul class="brochure-list mb-0">
                    <li>Session-based login workflows for authorized users.</li>
                    <li>Middleware-protected route groups by user role and responsibility.</li>
                    <li>Profile and password management for all signed-in employees.</li>
                    <li>Separation of high-privilege configuration features for admin roles.</li>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-6">
        <div class="brochure-card h-100">
            <div class="card-header">Technology Stack</div>
            <div class="card-body">
                <ul class="brochure-list mb-0">
                    <li>Laravel 11 and PHP 8.2+ backend foundation.</li>
                    <li>Bootstrap 5 based interface with DataTables integration.</li>
                    <li>Vite powered frontend build workflow.</li>
                    <li>MySQL-ready migration and seeding flow for deployment readiness.</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="brochure-footer">
    <strong>Business Value:</strong>
    Bank Manager reduces manual branch operations by combining transaction handling,
    account lifecycle management, employee governance, and report preparation in a single platform.
</div>
@endsection
