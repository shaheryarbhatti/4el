@extends('frontend.layouts.app')
@section('title', 'Contact Us — ' . config('app.name'))

@push('styles')
<style>
/* ── Hero ── */
.pg-hero {
    background: linear-gradient(135deg,#0f3460 0%,#16213e 50%,#1a1a2e 100%);
    padding: 72px 0 80px;
    text-align: center;
    position: relative;
    overflow: hidden;
}
.pg-hero::before {
    content:'';
    position:absolute; inset:0;
    background:radial-gradient(circle at 30% 50%,rgba(51,102,153,.3) 0%,transparent 60%),
               radial-gradient(circle at 70% 30%,rgba(91,155,213,.2) 0%,transparent 50%);
}
.pg-hero h1 { color:#fff; font-size:38px; font-weight:800; margin:0 0 12px; position:relative; }
.pg-hero p  { color:rgba(255,255,255,.7); font-size:16px; margin:0; position:relative; }

/* ── Info cards row ── */
.contact-info-grid {
    display:grid;
    grid-template-columns:repeat(3,1fr);
    gap:24px;
    margin:-44px auto 40px;
    position:relative;
    z-index:2;
}
@media(max-width:767px){ .contact-info-grid{grid-template-columns:1fr;margin-top:-30px;} }

.contact-info-card {
    background:#fff;
    border-radius:14px;
    padding:30px 24px;
    text-align:center;
    box-shadow:0 8px 30px rgba(0,0,0,.10);
    border:1px solid #f0f0f0;
    transition:transform .2s,box-shadow .2s;
}
.contact-info-card:hover { transform:translateY(-4px); box-shadow:0 16px 40px rgba(0,0,0,.12); }
.contact-info-card__icon {
    width:60px; height:60px;
    border-radius:50%;
    margin:0 auto 16px;
    display:flex; align-items:center; justify-content:center;
    font-size:24px;
}
.ci-icon--blue   { background:#dbeafe; color:#2563eb; }
.ci-icon--green  { background:#dcfce7; color:#16a34a; }
.ci-icon--orange { background:#ffedd5; color:#ea580c; }
.contact-info-card__title { font-size:15px; font-weight:700; color:#1a1a2e; margin-bottom:6px; }
.contact-info-card__val   { font-size:13.5px; color:#555; line-height:1.6; }

/* ── Form section ── */
.contact-form-wrap {
    background:#fff;
    border-radius:16px;
    padding:40px;
    box-shadow:0 4px 24px rgba(0,0,0,.07);
    border:1px solid #f0f0f0;
    margin-bottom:48px;
}
@media(max-width:575px){ .contact-form-wrap{padding:24px 18px;} }
.contact-form-title { font-size:22px; font-weight:800; color:#1a1a2e; margin-bottom:6px; }
.contact-form-sub   { color:#888; font-size:14px; margin-bottom:28px; }

.cf-label { font-size:13px; font-weight:700; color:#374151; margin-bottom:6px; display:block; }
.cf-input {
    width:100%;
    padding:11px 14px;
    border:1.5px solid #e2e8f0;
    border-radius:9px;
    font-size:14px;
    color:#1a1a2e;
    background:#fafafa;
    transition:border-color .2s,background .2s;
    outline:none;
}
.cf-input:focus { border-color:#336699; background:#fff; }
.cf-textarea { min-height:140px; resize:vertical; }

.cf-submit {
    background:linear-gradient(135deg,#336699,#1a1a2e);
    color:#fff;
    border:none;
    border-radius:10px;
    padding:13px 36px;
    font-size:15px;
    font-weight:700;
    cursor:pointer;
    transition:all .2s;
    width:100%;
    margin-top:6px;
}
.cf-submit:hover { transform:translateY(-2px); box-shadow:0 8px 24px rgba(51,102,153,.4); }

/* ── Map placeholder ── */
.map-placeholder {
    background: linear-gradient(135deg,#f8fafc,#eef2f7);
    border-radius:14px;
    height:220px;
    display:flex;
    align-items:center;
    justify-content:center;
    flex-direction:column;
    gap:10px;
    border:1px dashed #cbd5e1;
    margin-bottom:48px;
    color:#94a3b8;
}
.map-placeholder i { font-size:42px; color:#cbd5e1; }
.map-placeholder p { font-size:13px; margin:0; }
</style>
@endpush

@section('content')

{{-- Hero --}}
<div class="pg-hero">
    <h1><i class="fas fa-envelope" style="margin-right:10px;"></i>Contact Us</h1>
    <p>We're here to help. Reach out and we'll respond within 24 hours.</p>
</div>

<div class="container">

    {{-- Info cards --}}
    <div class="contact-info-grid">
        <div class="contact-info-card">
            <div class="contact-info-card__icon ci-icon--blue"><i class="fas fa-map-marker-alt"></i></div>
            <div class="contact-info-card__title">Our Address</div>
            <div class="contact-info-card__val">{{ setting('address','123 Market Street, New York, NY 10001') }}</div>
        </div>
        <div class="contact-info-card">
            <div class="contact-info-card__icon ci-icon--green"><i class="fas fa-envelope"></i></div>
            <div class="contact-info-card__title">Email Us</div>
            <div class="contact-info-card__val">
                <a href="mailto:{{ setting('contact_email','support@example.com') }}" style="color:#16a34a;text-decoration:none;">
                    {{ setting('contact_email','support@example.com') }}
                </a>
            </div>
        </div>
        <div class="contact-info-card">
            <div class="contact-info-card__icon ci-icon--orange"><i class="fas fa-phone-alt"></i></div>
            <div class="contact-info-card__title">Call Us</div>
            <div class="contact-info-card__val">
                <a href="tel:{{ setting('contact_phone','+1 800 000 0000') }}" style="color:#ea580c;text-decoration:none;">
                    {{ setting('contact_phone','+1 800 000 0000') }}
                </a>
            </div>
        </div>
    </div>

    {{-- Success flash --}}
    @if(session('contact_success'))
    <div style="background:#dcfce7;border:1px solid #bbf7d0;border-radius:12px;padding:16px 20px;margin-bottom:28px;display:flex;align-items:center;gap:12px;font-size:14px;color:#166534;font-weight:600;">
        <i class="fas fa-check-circle" style="font-size:20px;"></i>
        {{ session('contact_success') }}
    </div>
    @endif

    <div class="row">
        <div class="col-lg-7">
            {{-- Contact form --}}
            <div class="contact-form-wrap">
                <div class="contact-form-title">Send Us a Message</div>
                <div class="contact-form-sub">Fill in the form below and our team will get back to you shortly.</div>

                <form method="POST" action="{{ route('contact.submit') }}">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="cf-label">Your Name <span style="color:#ef4444;">*</span></label>
                                <input type="text" name="name" value="{{ old('name') }}"
                                       class="cf-input @error('name') border-danger @enderror"
                                       placeholder="John Smith" required>
                                @error('name')<span style="color:#ef4444;font-size:12px;margin-top:4px;display:block;">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="cf-label">Email Address <span style="color:#ef4444;">*</span></label>
                                <input type="email" name="email" value="{{ old('email') }}"
                                       class="cf-input @error('email') border-danger @enderror"
                                       placeholder="john@example.com" required>
                                @error('email')<span style="color:#ef4444;font-size:12px;margin-top:4px;display:block;">{{ $message }}</span>@enderror
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-4">
                        <label class="cf-label">Subject <span style="color:#ef4444;">*</span></label>
                        <input type="text" name="subject" value="{{ old('subject') }}"
                               class="cf-input @error('subject') border-danger @enderror"
                               placeholder="How can we help?" required>
                        @error('subject')<span style="color:#ef4444;font-size:12px;margin-top:4px;display:block;">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group mb-4">
                        <label class="cf-label">Message <span style="color:#ef4444;">*</span></label>
                        <textarea name="message"
                                  class="cf-input cf-textarea @error('message') border-danger @enderror"
                                  placeholder="Tell us more about your question or issue..." required>{{ old('message') }}</textarea>
                        @error('message')<span style="color:#ef4444;font-size:12px;margin-top:4px;display:block;">{{ $message }}</span>@enderror
                    </div>
                    <button type="submit" class="cf-submit">
                        <i class="fas fa-paper-plane" style="margin-right:8px;"></i> Send Message
                    </button>
                </form>
            </div>
        </div>

        <div class="col-lg-5">
            {{-- Map placeholder --}}
            <div class="map-placeholder">
                <i class="fas fa-map-marked-alt"></i>
                <p style="font-size:14px;font-weight:600;color:#64748b;">Our Location</p>
                <p>{{ setting('address','123 Market Street, New York, NY 10001') }}</p>
            </div>

            {{-- Extra info --}}
            <div style="background:#f8fafc;border-radius:14px;padding:24px;border:1px solid #e2e8f0;">
                <div style="font-size:15px;font-weight:700;color:#1a1a2e;margin-bottom:16px;">
                    <i class="fas fa-clock" style="color:#336699;margin-right:8px;"></i>Business Hours
                </div>
                <div style="font-size:13.5px;color:#555;line-height:2;">
                    <div><strong>Monday – Friday:</strong> 9:00 AM – 6:00 PM</div>
                    <div><strong>Saturday:</strong> 10:00 AM – 4:00 PM</div>
                    <div><strong>Sunday:</strong> Closed</div>
                </div>
                <hr style="border-color:#e2e8f0;margin:16px 0;">
                <div style="font-size:15px;font-weight:700;color:#1a1a2e;margin-bottom:12px;">
                    <i class="fas fa-headset" style="color:#336699;margin-right:8px;"></i>Support Channels
                </div>
                <div style="font-size:13px;color:#555;line-height:2;">
                    <div><i class="fas fa-envelope" style="width:18px;color:#336699;"></i> Email replies within 24h</div>
                    <div><i class="fas fa-phone" style="width:18px;color:#336699;"></i> Phone support (business hours)</div>
                    <div><i class="fas fa-comments" style="width:18px;color:#336699;"></i> Live chat (Mon–Fri, 9–5)</div>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection
