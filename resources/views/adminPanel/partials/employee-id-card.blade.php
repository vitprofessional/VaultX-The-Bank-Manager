@php
    $departmentText = strtoupper($card['class'] ?? 'MANAGEMENT');
    $employeePhone = $card['guardianPhone'] ?? 'N/A';
    $employeeEmail = $card['guardianName'] ?? 'N/A';
    $companyPhone = $branding['phone'] ?? 'N/A';
    $companyEmail = $branding['email'] ?? 'N/A';
    $companyWebsite = $branding['website'] ?? 'company.com';
    $companyLocation = $branding['outlet'] ?? ($branding['location'] ?? 'N/A');
@endphp

<div class="idm-card-sheet">
    <section class="idm-card idm-front">
        <div class="idm-side-ribbon">
            <span>{{ $departmentText }}</span>
        </div>

        <div class="idm-content">
            <div class="idm-brand">
                <div class="idm-brand-copy">
                    <h5>{{ $branding['name'] ?? 'Brand Name' }}</h5>
                    <p>{{ $branding['outlet'] ?? 'Tagline Space' }}</p>
                </div>
            </div>

            <div class="idm-avatar-wrap">
                <img src="{{ $card['photoUrl'] }}" alt="{{ $card['studentId'] }}">
            </div>

            <h4 class="idm-name">{{ $card['name'] }}</h4>
            <p class="idm-role">{{ $card['section'] ?? 'Employee' }}</p>
            <p class="idm-blood">Employee ID: {{ $card['studentId'] }}</p>

            <div class="idm-contact">
                <div><span>Phone</span><strong>{{ $employeePhone }}</strong></div>
                <div><span>Email</span><strong>{{ $employeeEmail }}</strong></div>
                <div><span>Join Date</span><strong>{{ $card['joinedAt'] ?? '-' }}</strong></div>
            </div>

            <div class="idm-barcode"></div>
        </div>
    </section>

    <section class="idm-card idm-back">
        <div class="idm-back-content">
            <h6>Terms and Conditions</h6>
            <p>This card is organizational property. Carry it during office hours and present on request. Return immediately upon resignation or transfer.</p>

            <div class="idm-dates">
                <div><strong>Issued On:</strong> {{ $card['issueDate'] ?? '-' }}</div>
                <div><strong>Valid Thru:</strong> {{ $card['validity'] ?? '-' }}</div>
            </div>

            <div class="idm-company">
                <div><strong>Company Name:</strong> {{ $branding['name'] ?? 'Company Name' }}</div>
                <div><strong>Location:</strong> {{ $companyLocation }}</div>
                <div><strong>Phone:</strong> {{ $companyPhone }}</div>
                <div><strong>Email:</strong> {{ $companyEmail }}</div>
            </div>

            <div class="idm-sign">
                <span>Authorized Signature</span>
            </div>
        </div>

        <div class="idm-geo-shape">
            <span class="s1"></span>
            <span class="s2"></span>
            <span class="s3"></span>
        </div>
    </section>
</div>
