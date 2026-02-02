@php
    $tenantData = tenant('data');
    $tenantConfig = [
        'schoolName' => $tenantData['school_name'] ?? 'Smart Pathshala',
        'schoolId' => tenant('id'),
        'primaryColor' => $tenantData['primary_color'] ?? '#2563EB',
        'isLocked' => tenant('is_locked'),
        'domain' => tenant('domains')->first()->domain ?? ''
    ];
@endphp

<script>
    window.TenantConfig = @json($tenantConfig);
</script>
