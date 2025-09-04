@if (setting('logo_icon'))
    <img src="{{ Storage::url(setting('logo_icon')) }}" alt="Logo" class="me-2" style="height: 32px;">
@else
    <i class="bi bi-rocket-takeoff me-2"></i>
@endif
