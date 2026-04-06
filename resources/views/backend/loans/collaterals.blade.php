@extends('backend.layout.master')

@push('styles')
<style>
/* ── Collateral Management — Page Styles ──────────────────────────────── */
.collateral-hero {
    background: linear-gradient(135deg, #1a3a6b 0%, #2e5ba8 100%);
    border-radius: 12px;
    padding: 28px 32px;
    color: #fff;
    margin-bottom: 24px;
    box-shadow: 0 6px 24px rgba(26,58,107,0.18);
}
.collateral-hero h2 { font-size: 1.6rem; font-weight: 700; margin: 0 0 4px; }
.collateral-hero .subtitle { opacity: 0.85; font-size: 0.95rem; }

.coverage-bar-wrap { background: rgba(255,255,255,0.15); border-radius: 8px; height: 10px; margin-top: 12px; overflow: hidden; }
.coverage-bar      { height: 10px; border-radius: 8px; background: #d4a843; transition: width 0.6s ease; }
.coverage-bar.met  { background: #28a745; }

.badge-sufficient   { background: #28a745; color: #fff; font-size: 0.82rem; padding: 5px 12px; border-radius: 20px; }
.badge-insufficient { background: #dc3545; color: #fff; font-size: 0.82rem; padding: 5px 12px; border-radius: 20px; }

.section-card { border: none; border-radius: 12px; box-shadow: 0 2px 16px rgba(0,0,0,0.06); margin-bottom: 20px; }
.section-card .card-header {
    background: #f7f8fc;
    border-bottom: 1px solid #e8eaf0;
    border-radius: 12px 12px 0 0;
    padding: 14px 20px;
    font-weight: 600;
    font-size: 0.97rem;
}

/* Table tweaks */
.collateral-table th { background: #f0f2f8; font-size:0.82rem; text-transform:uppercase; letter-spacing:0.04em; border:none; }
.collateral-table td { vertical-align: middle; }
.collateral-table .badge { font-size: 0.78rem; padding: 4px 10px; border-radius: 20px; }

/* Doc row */
.doc-row { background: #f7f8fc; }
.doc-row td { padding: 12px 20px; }
.doc-item { display:flex; align-items:center; gap:8px; padding: 6px 0; border-bottom: 1px solid #e8eaf0; }
.doc-item:last-child { border-bottom: none; }
.doc-icon { width: 32px; height: 32px; display:flex; align-items:center; justify-content:center;
            background:#e8edf8; border-radius: 6px; }

/* Upload mini-form */
.upload-mini { background:#fff; border: 1px dashed #b0bcd4; border-radius: 8px; padding: 12px 14px; margin-top: 10px; }

/* Info banner */
.collateral-info-banner {
    background: linear-gradient(90deg, #e8f0fe 0%, #dbeafe 100%);
    border-left: 4px solid #2e5ba8;
    border-radius: 0 8px 8px 0;
    padding: 12px 18px;
    margin-bottom: 16px;
    font-size: 0.92rem;
}

/* Action buttons */
.btn-action { border-radius: 6px; padding: 4px 10px; font-size: 0.8rem; }
</style>
@endpush

@section('contents')
<div class="page-wrapper">

    {{-- ── BREADCRUMB ─────────────────────────────────────────────────── --}}
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-7 align-self-center">
                <h3 class="page-title text-truncate text-dark font-weight-medium mb-1 pt-2">
                    Collateral Management
                </h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">ផ្ទាំងគ្រប់គ្រង</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('loans.index') }}">កម្ចី</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('loans.show', $loan->id) }}">{{ $loan->loan_code }}</a></li>
                        <li class="breadcrumb-item active">Collateral</li>
                    </ol>
                </nav>
            </div>
            <div class="col-5 align-self-center text-right">
                <a href="{{ route('loans.show', $loan->id) }}" class="btn btn-secondary btn-sm">
                    <i data-feather="arrow-left"></i> Back to Loan
                </a>
            </div>
        </div>
    </div>

    <div class="container-fluid pb-5">

        {{-- ── FLASH MESSAGES ─────────────────────────────────────────── --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            </div>
        @endif
        @if(session('coverage_warning'))
            <div class="alert alert-warning alert-dismissible fade show">
                <i data-feather="alert-triangle" class="mr-2"></i>
                {{ session('coverage_warning') }}
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            </div>
        @endif

        {{-- ── HERO HEADER ─────────────────────────────────────────────── --}}
        <div class="collateral-hero">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h2><i data-feather="shield" style="width:28px;height:28px;margin-right:10px;"></i> Collateral Management</h2>
                    <div class="subtitle">
                        <strong>{{ $loan->customer->name ?? 'N/A' }}</strong>
                        &nbsp;·&nbsp; {{ $loan->loan_code }}
                        &nbsp;·&nbsp; Principal: <strong>${{ number_format($principal, 2) }}</strong>
                    </div>

                    @if($requiresCollateral)
                    <div class="mt-3">
                        @php
                            $pct = $requiredValue > 0 ? min(100, round(($totalValue / $requiredValue) * 100)) : 0;
                        @endphp
                        <small style="opacity:0.85;">
                            Coverage: ${{ number_format($totalValue, 2) }} / ${{ number_format($requiredValue, 2) }} required
                            — <strong>{{ $pct }}%</strong>
                        </small>
                        <div class="coverage-bar-wrap">
                            <div class="coverage-bar {{ $isSufficient ? 'met' : '' }}" style="width: {{ $pct }}%;"></div>
                        </div>
                    </div>
                    @endif
                </div>
                <div class="col-md-4 text-right mt-3 mt-md-0">
                    @if($requiresCollateral)
                        @if($isSufficient)
                            <span class="badge-sufficient">
                                <i data-feather="check-circle" style="width:14px;height:14px;"></i>
                                Collateral Sufficient
                            </span>
                        @else
                            <span class="badge-insufficient">
                                <i data-feather="alert-triangle" style="width:14px;height:14px;"></i>
                                Insufficient Collateral
                            </span>
                        @endif
                    @else
                        <span style="background:rgba(255,255,255,0.15);color:#fff;padding:5px 12px;border-radius:20px;font-size:0.82rem;">
                            No collateral requirement
                        </span>
                    @endif
                </div>
            </div>
        </div>

        {{-- ── INFO BANNER (principal > 5000) ─────────────────────────── --}}
        @if($requiresCollateral)
        <div class="collateral-info-banner">
            <i data-feather="info" class="mr-2" style="width:16px;height:16px;color:#2e5ba8;"></i>
            <strong>Collateral Required:</strong>
            This loan requires collateral valued at minimum <strong>120%</strong> of the principal amount.
            Required value: <strong>${{ number_format($requiredValue, 2) }}</strong>.
            Current active collateral: <strong>${{ number_format($totalValue, 2) }}</strong>.
        </div>
        @endif

        {{-- ── ADD COLLATERAL FORM ──────────────────────────────────────── --}}
        <div class="card section-card">
            <div class="card-header">
                <i data-feather="plus-circle" class="mr-2" style="width:16px;height:16px;color:#2e5ba8;"></i>
                Add New Collateral
            </div>
            <div class="card-body">
                <form action="{{ route('loans.collaterals.store', $loan->id) }}" method="POST" id="addCollateralForm">
                    @csrf
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="font-weight-600 text-dark" for="collateral_type">Collateral Type <span class="text-danger">*</span></label>
                                <input type="text" id="collateral_type" name="collateral_type"
                                       class="form-control @error('collateral_type') is-invalid @enderror"
                                       placeholder="e.g. Land Title, Vehicle, Equipment"
                                       value="{{ old('collateral_type') }}" required>
                                @error('collateral_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="font-weight-600 text-dark" for="estimated_value">Estimated Value ($) <span class="text-danger">*</span></label>
                                <input type="number" id="estimated_value" name="estimated_value"
                                       class="form-control @error('estimated_value') is-invalid @enderror"
                                       placeholder="0.00" min="0.01" step="0.01"
                                       value="{{ old('estimated_value') }}" required>
                                @error('estimated_value')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="font-weight-600 text-dark" for="valuation_date">Valuation Date <span class="text-danger">*</span></label>
                                <input type="date" id="valuation_date" name="valuation_date"
                                       class="form-control @error('valuation_date') is-invalid @enderror"
                                       value="{{ old('valuation_date', date('Y-m-d')) }}" required>
                                @error('valuation_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="font-weight-600 text-dark" for="description">Description <span class="text-danger">*</span></label>
                                <textarea id="description" name="description" rows="1"
                                          class="form-control @error('description') is-invalid @enderror"
                                          placeholder="Brief description of the collateral item"
                                          required>{{ old('description') }}</textarea>
                                @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                    <div class="text-right">
                        <button type="submit" class="btn btn-primary px-4">
                            <i data-feather="plus" style="width:14px;height:14px;"></i>
                            Add Collateral
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ── COLLATERAL LIST TABLE ────────────────────────────────────── --}}
        <div class="card section-card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <span>
                    <i data-feather="list" class="mr-2" style="width:16px;height:16px;color:#2e5ba8;"></i>
                    Collateral Records
                    <span class="badge badge-secondary ml-2">{{ $collaterals->count() }}</span>
                </span>
                <small class="text-muted">Click the document count to expand files.</small>
            </div>
            <div class="card-body p-0">
                @if($collaterals->isEmpty())
                    <div class="text-center py-5 text-muted">
                        <i data-feather="shield" style="width:48px;height:48px;opacity:0.2;"></i>
                        <p class="mt-3">No collateral records yet. Add one above.</p>
                    </div>
                @else
                <div class="table-responsive">
                    <table class="table collateral-table mb-0">
                        <thead>
                            <tr>
                                <th style="width:50px;">#</th>
                                <th>Type</th>
                                <th>Description</th>
                                <th>Estimated Value</th>
                                <th>Valuation Date</th>
                                <th>Status</th>
                                <th>Docs</th>
                                <th style="width:150px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($collaterals as $index => $col)
                            {{-- Main row --}}
                            <tr id="col-row-{{ $col->id }}">
                                <td class="text-muted">{{ $index + 1 }}</td>
                                <td class="font-weight-bold">{{ $col->collateral_type }}</td>
                                <td class="text-muted" style="max-width:220px;">
                                    <span class="d-inline-block text-truncate" style="max-width:200px;" title="{{ $col->description }}">
                                        {{ $col->description }}
                                    </span>
                                </td>
                                <td class="font-weight-bold text-success">${{ number_format($col->estimated_value, 2) }}</td>
                                <td>{{ $col->valuation_date ? $col->valuation_date->format('d/m/Y') : '—' }}</td>
                                <td>
                                    <span class="badge {{ $col->statusBadgeClass() }}">
                                        {{ ucfirst($col->status) }}
                                    </span>
                                </td>
                                <td>
                                    <button type="button"
                                            class="btn btn-link p-0 toggle-docs"
                                            data-target="docs-row-{{ $col->id }}"
                                            title="Toggle documents">
                                        <i data-feather="paperclip" style="width:14px;height:14px;"></i>
                                        <span class="badge badge-secondary ml-1">{{ $col->docs->count() }}</span>
                                    </button>
                                </td>
                                <td>
                                    {{-- Edit button --}}
                                    <button type="button" class="btn btn-warning btn-action mr-1"
                                            data-toggle="modal" data-target="#editModal"
                                            data-id="{{ $col->id }}"
                                            data-loan="{{ $loan->id }}"
                                            data-type="{{ $col->collateral_type }}"
                                            data-desc="{{ $col->description }}"
                                            data-value="{{ $col->estimated_value }}"
                                            data-date="{{ $col->valuation_date ? $col->valuation_date->format('Y-m-d') : '' }}"
                                            data-status="{{ $col->status }}">
                                        <i data-feather="edit-2" style="width:12px;height:12px;"></i> Edit
                                    </button>

                                    {{-- Delete button --}}
                                    <button type="button" class="btn btn-danger btn-action"
                                            data-toggle="modal" data-target="#deleteCollateralModal"
                                            data-id="{{ $col->id }}"
                                            data-loan="{{ $loan->id }}"
                                            data-type="{{ $col->collateral_type }}">
                                        <i data-feather="trash-2" style="width:12px;height:12px;"></i>
                                    </button>
                                </td>
                            </tr>

                            {{-- Documents expandable row --}}
                            <tr id="docs-row-{{ $col->id }}" class="doc-row" style="display:none;">
                                <td colspan="8">
                                    <div class="px-2">

                                        {{-- Existing docs list --}}
                                        @if($col->docs->count())
                                            <p class="font-weight-bold text-muted mb-2" style="font-size:0.82rem;">
                                                <i data-feather="folder" style="width:13px;height:13px;"></i>
                                                Uploaded Documents
                                            </p>
                                            @foreach($col->docs as $doc)
                                            <div class="doc-item">
                                                <div class="doc-icon">
                                                    @php
                                                        $icon = str_ends_with(strtolower($doc->file_name), '.pdf') ? 'file-text' : 'image';
                                                    @endphp
                                                    <i data-feather="{{ $icon }}" style="width:16px;height:16px;color:#2e5ba8;"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <strong class="d-block" style="font-size:0.87rem;">{{ $doc->file_name }}</strong>
                                                    <small class="text-muted">
                                                        {{ ucwords(str_replace('_', ' ', $doc->document_type)) }}
                                                        &nbsp;·&nbsp; {{ $doc->fileSizeHuman() }}
                                                        &nbsp;·&nbsp; Uploaded by {{ $doc->uploader->name ?? 'Unknown' }}
                                                        &nbsp;·&nbsp; {{ $doc->uploaded_at ? $doc->uploaded_at->format('d/m/Y H:i') : '—' }}
                                                    </small>
                                                </div>
                                                <a href="{{ route('loans.collaterals.docs.download', $doc->id) }}"
                                                   class="btn btn-sm btn-outline-primary btn-action mr-1">
                                                    <i data-feather="download" style="width:12px;height:12px;"></i> Download
                                                </a>
                                                <form action="{{ route('loans.collaterals.docs.delete', $doc->id) }}"
                                                      method="POST" class="d-inline"
                                                      onsubmit="return confirm('Delete this document?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger btn-action">
                                                        <i data-feather="trash-2" style="width:12px;height:12px;"></i>
                                                    </button>
                                                </form>
                                            </div>
                                            @endforeach
                                        @else
                                            <p class="text-muted mb-2" style="font-size:0.85rem;">No documents uploaded yet.</p>
                                        @endif

                                        {{-- Upload mini-form --}}
                                        <div class="upload-mini mt-3">
                                            <p class="font-weight-bold mb-2" style="font-size:0.83rem; color:#2e5ba8;">
                                                <i data-feather="upload" style="width:13px;height:13px;"></i>
                                                Upload Document
                                            </p>
                                            <form action="{{ route('loans.collaterals.docs.upload', $col->id) }}"
                                                  method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <div class="row align-items-end">
                                                    <div class="col-md-4">
                                                        <div class="form-group mb-0">
                                                            <label class="text-muted" style="font-size:0.8rem;">Document Type</label>
                                                            <select name="document_type" class="form-control form-control-sm" required>
                                                                <option value="">— Select —</option>
                                                                <option value="title_deed">Title Deed</option>
                                                                <option value="vehicle_registration">Vehicle Registration</option>
                                                                <option value="insurance">Insurance</option>
                                                                <option value="valuation_report">Valuation Report</option>
                                                                <option value="other">Other</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <div class="form-group mb-0">
                                                            <label class="text-muted" style="font-size:0.8rem;">File (PDF, JPG, PNG — max 5MB)</label>
                                                            <input type="file" name="file" class="form-control form-control-sm"
                                                                   accept=".pdf,.jpg,.jpeg,.png" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <button type="submit" class="btn btn-primary btn-sm btn-block">
                                                            <i data-feather="upload" style="width:12px;height:12px;"></i>
                                                            Upload
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>

                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="table-light">
                                <td colspan="3" class="text-right font-weight-bold">Total Active Collateral Value:</td>
                                <td class="font-weight-bold {{ $isSufficient ? 'text-success' : 'text-danger' }}">
                                    ${{ number_format($totalValue, 2) }}
                                    @if($requiresCollateral)
                                        <small class="d-block text-muted font-weight-normal">
                                            Required: ${{ number_format($requiredValue, 2) }}
                                        </small>
                                    @endif
                                </td>
                                <td colspan="4">
                                    @if($requiresCollateral)
                                        @if($isSufficient)
                                            <span class="badge-sufficient">
                                                <i data-feather="check" style="width:12px;height:12px;"></i>
                                                120% Threshold Met ✓
                                            </span>
                                        @else
                                            <span class="badge-insufficient">
                                                <i data-feather="x" style="width:12px;height:12px;"></i>
                                                Below Required Threshold
                                            </span>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                @endif
            </div>
        </div>

    </div>{{-- /container-fluid --}}
</div>{{-- /page-wrapper --}}

{{-- ════════════════════════════════════════════════════════════════════════
     EDIT COLLATERAL MODAL
════════════════════════════════════════════════════════════════════════ --}}
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header" style="background:linear-gradient(135deg,#1a3a6b,#2e5ba8); color:#fff;">
                <h5 class="modal-title font-weight-bold">
                    <i data-feather="edit-2" class="mr-2" style="width:18px;height:18px;"></i>
                    Edit Collateral
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <form id="editCollateralForm" method="POST" action="">
                @csrf @method('PUT')
                <div class="modal-body p-4">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Collateral Type <span class="text-danger">*</span></label>
                                <input type="text" name="collateral_type" id="edit_type"
                                       class="form-control" placeholder="e.g. Land Title" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="font-weight-bold">Estimated Value ($) <span class="text-danger">*</span></label>
                                <input type="number" name="estimated_value" id="edit_value"
                                       class="form-control" min="0.01" step="0.01" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="font-weight-bold">Valuation Date <span class="text-danger">*</span></label>
                                <input type="date" name="valuation_date" id="edit_date"
                                       class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label class="font-weight-bold">Description <span class="text-danger">*</span></label>
                                <textarea name="description" id="edit_desc" rows="2"
                                          class="form-control" required></textarea>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="font-weight-bold">Status <span class="text-danger">*</span></label>
                                <select name="status" id="edit_status" class="form-control">
                                    <option value="active">Active</option>
                                    <option value="released">Released</option>
                                    <option value="seized">Seized</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4">
                        <i data-feather="save" style="width:14px;height:14px;"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ════════════════════════════════════════════════════════════════════════
     DELETE COLLATERAL MODAL
════════════════════════════════════════════════════════════════════════ --}}
<div class="modal fade" id="deleteCollateralModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-danger text-white border-0">
                <h5 class="modal-title font-weight-bold">
                    <i data-feather="trash-2" class="mr-2" style="width:18px;height:18px;"></i>
                    Remove Collateral
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body p-4">
                <div class="alert alert-warning border-0">
                    <i data-feather="alert-triangle" class="mr-2"></i>
                    <strong>Warning:</strong> This will permanently remove the collateral record
                    and all its associated documents.
                </div>
                <p>Are you sure you want to delete <strong id="deleteCollateralType"></strong>?</p>
            </div>
            <div class="modal-footer border-0 d-flex justify-content-between">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <form id="deleteCollateralForm" method="POST" action="" class="d-inline">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger px-4">
                        <i data-feather="trash-2" style="width:14px;height:14px;"></i>
                        Yes, Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function () {
    "use strict";
    $(".preloader").fadeOut();
    if (typeof feather !== "undefined") feather.replace();

    // ── Toggle document rows ──────────────────────────────────────────
    $(".toggle-docs").on("click", function () {
        var targetId = $(this).data("target");
        var $row = $("#" + targetId);
        $row.toggle();
        if (typeof feather !== "undefined") feather.replace();
    });

    // ── Populate Edit Modal ───────────────────────────────────────────
    $("#editModal").on("show.bs.modal", function (e) {
        var btn    = $(e.relatedTarget);
        var collId = btn.data("id");
        var loanId = btn.data("loan");

        $("#edit_type").val(btn.data("type"));
        $("#edit_desc").val(btn.data("desc"));
        $("#edit_value").val(btn.data("value"));
        $("#edit_date").val(btn.data("date"));
        $("#edit_status").val(btn.data("status"));

        var actionUrl = "/admin/v1/loans/" + loanId + "/collaterals/" + collId;
        $("#editCollateralForm").attr("action", actionUrl);
    });

    // ── Populate Delete Modal ─────────────────────────────────────────
    $("#deleteCollateralModal").on("show.bs.modal", function (e) {
        var btn    = $(e.relatedTarget);
        var collId = btn.data("id");
        var loanId = btn.data("loan");
        var type   = btn.data("type");

        $("#deleteCollateralType").text(type);
        var actionUrl = "/admin/v1/loans/" + loanId + "/collaterals/" + collId;
        $("#deleteCollateralForm").attr("action", actionUrl);
    });

    // Re-init feather after modals open
    $(".modal").on("shown.bs.modal", function () {
        if (typeof feather !== "undefined") feather.replace();
    });
});
</script>
@endpush
