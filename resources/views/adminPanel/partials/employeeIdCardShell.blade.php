<div class="id-card-shell">
    <div class="id-face id-front">
        <div class="id-front-top">
            <div class="id-top-row">
                <div class="id-brand">
                    <div class="id-logo">
                        @if(!empty($branding['logoUrl']))
                            <img src="{{ $branding['logoUrl'] }}" alt="Logo">
                        @else
                            <div class="logo-fallback">{{ strtoupper(substr($branding['name'] ?? 'BM', 0, 2)) }}</div>
                        @endif
                    </div>
                    <div>
                        <div class="id-school-name">{{ $branding['name'] ?? 'Bank Manager' }}</div>
                        <div class="id-school-tag">{{ $branding['tagline'] ?? 'Employee Identity Card' }}</div>
                    </div>
                </div>
                @if(($format ?? 'landscape') === 'landscape')
                    <div class="id-card-label">Employee ID Card</div>
                @endif
            </div>
        </div>

        <div class="id-front-body {{ ($format ?? 'landscape') === 'portrait' ? 'portrait' : 'landscape' }}">
            @if(($format ?? 'landscape') === 'portrait')
                <div class="id-card-label">Employee ID Card</div>
            @endif

            <div class="id-photo-wrap {{ ($format ?? 'landscape') === 'portrait' ? 'portrait' : '' }}">
                <div class="id-photo-placeholder">
                    {{ strtoupper(substr($card['name'] ?? 'EM', 0, 2)) }}
                </div>
            </div>

            <div class="id-info">
                <div class="id-name">{{ $card['name'] ?? '-' }}</div>
                <div class="id-number">ID {{ $card['employeeCode'] ?? '-' }}</div>

                @if(($format ?? 'landscape') === 'portrait')
                    <div class="id-portrait-grid">
                        <div>Dept <span>{{ $card['department'] ?? '-' }}</span></div>
                        <div>Role <span>{{ $card['designation'] ?? '-' }}</span></div>
                        <div>Status <span>{{ $card['status'] ?? '-' }}</span></div>
                    </div>
                @else
                    <div class="id-row">Department: <span>{{ $card['department'] ?? '-' }}</span></div>
                    <div class="id-row">Designation: <span>{{ $card['designation'] ?? '-' }}</span></div>
                    <div class="id-row">Mobile: <span>{{ $card['mobile'] ?? '-' }}</span></div>
                    <div class="id-row">Status: <span>{{ $card['status'] ?? '-' }}</span></div>
                @endif
            </div>
        </div>

        <div class="id-front-footer">
            <div>Web: {{ $branding['website'] ?? 'localhost' }}</div>
        </div>
    </div>

    <div class="id-face id-back">
        <div class="id-back-top"></div>
        <div class="id-back-body {{ ($format ?? 'landscape') === 'portrait' ? '' : 'landscape' }}">
            <div class="id-back-left">
                <div class="id-back-title">Instructions:</div>
                <ol class="id-back-list">
                    <li>This card is property of the organization.</li>
                    <li>Carry this ID during office hours.</li>
                </ol>

                <div class="id-guardian-box">
                    <div class="id-back-title">Employee Details:</div>
                    <div class="id-guardian-row">Joined <span>{{ $card['joinedAt'] ?? '-' }}</span></div>
                    <div class="id-guardian-row">Email <span>{{ $card['email'] ?? '-' }}</span></div>
                </div>

                @if(($format ?? 'landscape') === 'landscape')
                    <div class="id-landscape-back-note">Valid until: <span>{{ $card['validity'] ?? '-' }}</span></div>
                @else
                    <div class="id-back-note">VALID: <span>{{ $card['validity'] ?? '-' }}</span></div>
                @endif
            </div>

            <div class="id-barcode-box {{ ($format ?? 'landscape') === 'landscape' ? 'id-back-landscape-qr' : 'id-back-qr' }}">
                <div class="id-back-qr-img">
                    @if(!empty($qrSvg))
                        {!! $qrSvg !!}
                    @else
                        <div class="qr-fallback">QR unavailable</div>
                    @endif
                </div>
                <div class="id-card-no">Card No {{ $card['employeeCode'] ?? '-' }}</div>
                <div class="id-sign">Authorized Signature</div>
            </div>
        </div>

        <div class="id-back-footer-bar">
            <div>If found, contact: <span>{{ $branding['phone'] ?? 'N/A' }}</span></div>
        </div>
    </div>
</div>
