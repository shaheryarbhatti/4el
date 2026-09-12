@extends('admin.layouts.app')
@section('title', 'Email / SMTP Settings')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.settings.index') }}">Settings</a></li>
    <li class="breadcrumb-item active">Email / SMTP</li>
@endsection

@section('content')
@include('admin.settings._nav')

<form action="{{ route('admin.settings.update', 'smtp') }}" method="POST">
@csrf

{{-- Mail Server Config --}}
<div class="st-card">
    <div class="st-card-header">
        <div class="st-card-header-stripe" style="background:linear-gradient(180deg,#8b5cf6,#7c3aed)"></div>
        <div class="st-card-header-inner">
            <div class="st-card-header-icon" style="background:linear-gradient(135deg,#8b5cf6,#7c3aed)">
                <i class="bx bx-server"></i>
            </div>
            <div class="st-card-header-text">
                <h6>Mail Server (SMTP)</h6>
                <p>Connection details for your outgoing mail server</p>
            </div>
        </div>
    </div>
    <div class="st-card-body">
        <div class="row gy-4">
            <div class="col-md-4">
                <div class="st-field">
                    <label class="st-label">Mailer</label>
                    <div class="st-input-wrap">
                        <i class="bx bx-mail-send st-input-icon"></i>
                        <select name="mail_mailer" class="form-select">
                            @foreach (['smtp'=>'SMTP', 'sendmail'=>'Sendmail', 'mailgun'=>'Mailgun', 'ses'=>'Amazon SES', 'log'=>'Log (testing)'] as $v => $l)
                            <option value="{{ $v }}" {{ ($values['mail_mailer'] ?? 'smtp') === $v ? 'selected' : '' }}>{{ $l }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="st-hint"><i class="bx bx-info-circle"></i> Use "Log" to test emails without actually sending</div>
                </div>
            </div>
            <div class="col-md-5">
                <div class="st-field">
                    <label class="st-label">SMTP Host</label>
                    <div class="st-input-wrap">
                        <i class="bx bx-server st-input-icon"></i>
                        <input type="text" name="mail_host" class="form-control"
                               value="{{ old('mail_host', $values['mail_host'] ?? '') }}"
                               placeholder="smtp.gmail.com">
                    </div>
                    <div class="st-hint"><i class="bx bx-info-circle"></i> e.g. smtp.gmail.com, smtp.mailgun.org</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="st-field">
                    <label class="st-label">Port</label>
                    <div class="st-input-wrap">
                        <i class="bx bx-plug st-input-icon"></i>
                        <input type="number" name="mail_port" class="form-control"
                               value="{{ old('mail_port', $values['mail_port'] ?? '587') }}"
                               placeholder="587">
                    </div>
                    <div class="st-hint"><i class="bx bx-info-circle"></i> TLS=587, SSL=465</div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="st-field">
                    <label class="st-label">Username</label>
                    <div class="st-input-wrap">
                        <i class="bx bx-user st-input-icon"></i>
                        <input type="text" name="mail_username" class="form-control"
                               value="{{ old('mail_username', $values['mail_username'] ?? '') }}"
                               placeholder="your@email.com">
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="st-field">
                    <label class="st-label">Password / App Password</label>
                    <div class="st-input-wrap">
                        <i class="bx bx-lock st-input-icon"></i>
                        <input type="password" name="mail_password" class="form-control"
                               value="{{ old('mail_password', $values['mail_password'] ?? '') }}"
                               placeholder="••••••••">
                    </div>
                    <div class="st-hint" style="color:#ef4444"><i class="bx bx-shield"></i> For Gmail — use an App Password, not your account password</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="st-field" style="margin-bottom:0">
                    <label class="st-label">Encryption</label>
                    <div class="st-input-wrap">
                        <i class="bx bx-shield-quarter st-input-icon"></i>
                        <select name="mail_encryption" class="form-select">
                            <option value="tls"  {{ ($values['mail_encryption'] ?? 'tls') === 'tls'  ? 'selected' : '' }}>TLS (recommended)</option>
                            <option value="ssl"  {{ ($values['mail_encryption'] ?? '') === 'ssl'  ? 'selected' : '' }}>SSL</option>
                            <option value="null" {{ ($values['mail_encryption'] ?? '') === 'null' ? 'selected' : '' }}>None</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- From Identity --}}
<div class="st-card">
    <div class="st-card-header">
        <div class="st-card-header-stripe" style="background:linear-gradient(180deg,#6366f1,#4f46e5)"></div>
        <div class="st-card-header-inner">
            <div class="st-card-header-icon" style="background:linear-gradient(135deg,#6366f1,#4f46e5)">
                <i class="bx bx-id-card"></i>
            </div>
            <div class="st-card-header-text">
                <h6>From Identity</h6>
                <p>The sender name and address buyers see on all outgoing emails</p>
            </div>
        </div>
    </div>
    <div class="st-card-body">
        <div class="row gy-4">
            <div class="col-md-6">
                <div class="st-field" style="margin-bottom:0">
                    <label class="st-label">From Address</label>
                    <div class="st-input-wrap">
                        <i class="bx bx-envelope st-input-icon"></i>
                        <input type="email" name="mail_from_address" class="form-control"
                               value="{{ old('mail_from_address', $values['mail_from_address'] ?? '') }}"
                               placeholder="noreply@yourstore.com">
                    </div>
                    <div class="st-hint"><i class="bx bx-info-circle"></i> Must match your SMTP username (or be an alias) to avoid spam filters</div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="st-field" style="margin-bottom:0">
                    <label class="st-label">From Name</label>
                    <div class="st-input-wrap">
                        <i class="bx bx-store st-input-icon"></i>
                        <input type="text" name="mail_from_name" class="form-control"
                               value="{{ old('mail_from_name', $values['mail_from_name'] ?? '') }}"
                               placeholder="{{ setting('site_name','eBay Clone') }}">
                    </div>
                    <div class="st-hint"><i class="bx bx-info-circle"></i> Shown as the sender name in email clients</div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Info note --}}
<div class="st-card" style="border-color:#dbeafe;background:linear-gradient(135deg,#eff6ff,#f0f9ff)">
    <div class="st-card-body" style="padding:16px 20px">
        <div class="d-flex align-items-start gap-3">
            <div style="width:34px;height:34px;border-radius:9px;background:linear-gradient(135deg,#3b82f6,#2563eb);display:flex;align-items:center;justify-content:center;color:#fff;font-size:15px;flex-shrink:0">
                <i class="bx bx-info-circle"></i>
            </div>
            <div style="font-size:12.5px;color:#1e40af">
                <strong>Applied at runtime.</strong> These credentials are loaded via <code>AppServiceProvider</code> so email works without editing <code>.env</code>.
                To test, set mailer to <strong>Log</strong> and check <code>storage/logs/laravel.log</code> for the email content.
            </div>
        </div>
    </div>
</div>

<div class="st-save-bar">
    <div class="st-save-bar-text">
        <i class="bx bx-shield-check"></i>
        SMTP credentials are stored securely and never shown to users.
    </div>
    <button type="submit" class="st-btn-save" style="background:linear-gradient(135deg,#8b5cf6,#7c3aed)">
        <i class="bx bx-save"></i> Save Email Settings
    </button>
</div>

</form>

@include('admin.settings._nav_end')
@endsection
