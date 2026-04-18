@extends('layouts.app')

@section('content')
<div class="profile-edit-modern">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-9">
                <!-- Page Header -->
                <div class="profile-edit-header mb-4">
                    <div class="d-flex align-items-center">
                        <div class="profile-edit-header-icon">
                            <i class="bi bi-person-gear"></i>
                        </div>
                        <div>
                            <h1 class="profile-edit-title mb-1">Edit Profile</h1>
                            <p class="profile-edit-subtitle mb-0">Update your personal information and preferences</p>
                        </div>
                    </div>
                </div>

                <div class="profile-edit-card">
                    <div class="profile-edit-card-body">
                    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')

                        <!-- Avatar Section -->
                        <div class="avatar-section mb-4">
                            <div class="avatar-wrapper">
                                <img src="{{ $user->avatar_url ?? asset('images/default-avatar.png') }}" class="avatar-image" alt="Avatar">
                                <div class="avatar-upload-btn">
                                    <label for="avatar" class="avatar-label">
                                        <i class="bi bi-camera-fill"></i>
                                    </label>
                                    <input type="file" name="avatar" id="avatar" class="d-none" accept="image/*">
                                </div>
                            </div>
                            @error('avatar')
                                <div class="text-danger small mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="name" class="form-label fw-medium">
                                <i class="bi bi-person me-1"></i> Name
                            </label>
                            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" class="form-control @error('name') is-invalid @enderror" required autofocus>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label fw-medium">
                                <i class="bi bi-envelope me-1"></i> Email
                            </label>
                            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" class="form-control @error('email') is-invalid @enderror" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label fw-medium">
                                <i class="bi bi-telephone me-1"></i> Phone <span style="color: #dc3545;">*</span>
                            </label>
                            <input type="tel" name="phone" id="phone" value="{{ old('phone', $user->phone) }}" class="form-control @error('phone') is-invalid @enderror" required>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Address Section -->
                        <div class="form-section">
                            <div class="form-section-header">
                                <div class="form-section-icon address-icon">
                                    <i class="bi bi-geo-alt"></i>
                                </div>
                                <h5 class="form-section-title">Address Information</h5>
                            </div>
                            <div class="form-section-body">
                                
                                <div class="mb-3">
                                    <label for="address_line_1" class="form-label fw-medium">
                                        <i class="bi bi-house me-1"></i> Address Line 1 <span style="color: #dc3545;">*</span>
                                    </label>
                                    <input type="text" name="address_line_1" id="address_line_1" value="{{ old('address_line_1', $user->address_line_1) }}" class="form-control @error('address_line_1') is-invalid @enderror" required>
                                    @error('address_line_1')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="address_line_2" class="form-label fw-medium">
                                        <i class="bi bi-house me-1"></i> Address Line 2
                                    </label>
                                    <input type="text" name="address_line_2" id="address_line_2" value="{{ old('address_line_2', $user->address_line_2) }}" class="form-control @error('address_line_2') is-invalid @enderror">
                                    @error('address_line_2')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="district" class="form-label fw-medium">
                                                <i class="bi bi-map me-1"></i> District <span style="color: #dc3545;">*</span>
                                            </label>
                                            <select name="district" id="district" class="form-select @error('district') is-invalid @enderror" required>
                                                <option value="">Select District *</option>
                                                @foreach(config('sri_lanka_districts.districts') as $district => $cities)
                                                    <option value="{{ $district }}" {{ old('district', $user->district) == $district ? 'selected' : '' }}>{{ $district }}</option>
                                                @endforeach
                                            </select>
                                            @error('district')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="city" class="form-label fw-medium">
                                                <i class="bi bi-geo-alt-fill me-1"></i> City <span style="color: #dc3545;">*</span>
                                            </label>
                                            <select name="city" id="city" class="form-select @error('city') is-invalid @enderror" required>
                                                <option value="">Select City *</option>
                                                @if(old('district', $user->district))
                                                    @foreach(config('sri_lanka_districts.districts.' . old('district', $user->district)) as $city)
                                                        <option value="{{ $city }}" {{ old('city', $user->city) == $city ? 'selected' : '' }}>{{ $city }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            @error('city')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="postal_code" class="form-label fw-medium">
                                        <i class="bi bi-envelope-paper me-1"></i> Postal Code <span style="color: #dc3545;">*</span>
                                    </label>
                                    <input type="text" name="postal_code" id="postal_code" value="{{ old('postal_code', $user->postal_code) }}" class="form-control @error('postal_code') is-invalid @enderror" pattern="[0-9]{5}" maxlength="5" required>
                                    @error('postal_code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Password Section -->
                        <div class="form-section">
                            <div class="form-section-header">
                                <div class="form-section-icon security-icon">
                                    <i class="bi bi-shield-lock"></i>
                                </div>
                                <h5 class="form-section-title">Change Password</h5>
                            </div>
                            <div class="form-section-body">
                                
                                <div class="mb-3">
                                    <label for="password" class="form-label fw-medium">
                                        <i class="bi bi-key me-1"></i> New Password
                                    </label>
                                    <div class="input-group">
                                        <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" autocomplete="new-password">
                                        <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password')">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </div>
                                    @error('password')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="password_confirmation" class="form-label fw-medium">
                                        <i class="bi bi-check-circle me-1"></i> Confirm New Password
                                    </label>
                                    <div class="input-group">
                                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" autocomplete="new-password">
                                        <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password_confirmation')">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="form-actions">
                            <a href="{{ route('dashboard') }}" class="btn-back">
                                <i class="bi bi-arrow-left me-2"></i>Back to Dashboard
                            </a>
                            <button type="submit" class="btn-submit">
                                <i class="bi bi-check-circle me-2"></i>Update Profile
                            </button>
                        </div>
                        
                        <script>
                            const districts = @json(config('sri_lanka_districts.districts'));
                            
                            function togglePassword(id) {
                                const input = document.getElementById(id);
                                const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                                input.setAttribute('type', type);
                                
                                const icon = event.currentTarget.querySelector('i');
                                icon.classList.toggle('bi-eye');
                                icon.classList.toggle('bi-eye-slash');
                            }
                            
                            // Handle district change to update cities
                            document.getElementById('district').addEventListener('change', function() {
                                const selectedDistrict = this.value;
                                const citySelect = document.getElementById('city');
                                const currentCityValue = citySelect.value;
                                
                                // Clear current cities
                                citySelect.innerHTML = '<option value="">Select City *</option>';
                                
                                if (selectedDistrict && districts[selectedDistrict]) {
                                    districts[selectedDistrict].forEach(function(city) {
                                        const option = document.createElement('option');
                                        option.value = city;
                                        option.textContent = city;
                                        citySelect.appendChild(option);
                                    });
                                    
                                    // Restore selected city if it exists in the new district
                                    if (currentCityValue && districts[selectedDistrict].includes(currentCityValue)) {
                                        citySelect.value = currentCityValue;
                                    }
                                }
                            });
                            
                            // Initialize cities on page load if district is already selected
                            document.addEventListener('DOMContentLoaded', function() {
                                const districtSelect = document.getElementById('district');
                                const citySelect = document.getElementById('city');
                                const initialCityValue = citySelect.value;
                                
                                if (districtSelect.value) {
                                    const event = new Event('change');
                                    districtSelect.dispatchEvent(event);
                                    
                                    // Set the initial city value after districts are loaded
                                    setTimeout(() => {
                                        if (initialCityValue) {
                                            citySelect.value = initialCityValue;
                                        }
                                    }, 100);
                                }
                            });
                        </script>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* Profile Edit Modern Styles */
.profile-edit-modern {
    background: #f8f9fa;
    min-height: calc(100vh - 200px);
    padding-top: 2rem;
    padding-bottom: 2rem;
}

/* Page Header */
.profile-edit-header {
    background: white;
    padding: 2rem;
    border-radius: 16px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    border: 1px solid #e9ecef;
}

.profile-edit-header-icon {
    width: 70px;
    height: 70px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 36px;
    margin-right: 1.5rem;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.25);
}

.profile-edit-title {
    font-size: 2rem;
    font-weight: 700;
    color: #2c3e50;
}

.profile-edit-subtitle {
    color: #6c757d;
    font-size: 1rem;
}

/* Main Card */
.profile-edit-card {
    background: white;
    border-radius: 16px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    border: 1px solid #e9ecef;
    overflow: hidden;
}

.profile-edit-card-body {
    padding: 2.5rem;
}

/* Avatar Section */
.avatar-section {
    text-align: center;
    padding: 2rem 0;
    border-bottom: 1px solid #e9ecef;
}

.avatar-wrapper {
    position: relative;
    display: inline-block;
}

.avatar-image {
    width: 140px;
    height: 140px;
    border-radius: 50%;
    border: 4px solid #e9ecef;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    object-fit: cover;
    transition: all 0.3s ease;
}

.avatar-wrapper:hover .avatar-image {
    border-color: #667eea;
    transform: scale(1.05);
}

.avatar-upload-btn {
    position: absolute;
    bottom: 5px;
    right: 5px;
}

.avatar-label {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    border: 3px solid white;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    transition: all 0.3s ease;
    margin: 0;
}

.avatar-label:hover {
    background: linear-gradient(135deg, #5568d3 0%, #63408b 100%);
    transform: scale(1.1);
}

.avatar-label i {
    font-size: 18px;
}

/* Form Sections */
.form-section {
    margin-top: 2rem;
    padding-top: 2rem;
    border-top: 1px solid #e9ecef;
}

.form-section-header {
    display: flex;
    align-items: center;
    margin-bottom: 1.5rem;
}

.form-section-icon {
    width: 45px;
    height: 45px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 22px;
    margin-right: 1rem;
}

.address-icon {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    box-shadow: 0 4px 12px rgba(40, 167, 69, 0.25);
}

.security-icon {
    background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
    box-shadow: 0 4px 12px rgba(23, 162, 184, 0.25);
}

.form-section-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: #2c3e50;
    margin: 0;
}

.form-section-body {
    padding-left: 0;
}

/* Form Controls */
.profile-edit-card-body .form-label {
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 0.5rem;
    font-size: 0.875rem;
}

.profile-edit-card-body .form-control,
.profile-edit-card-body .form-select {
    border-radius: 8px;
    border: 1px solid #dee2e6;
    padding: 0.75rem 1rem;
    font-size: 0.9375rem;
    transition: all 0.3s ease;
}

.profile-edit-card-body .form-control:focus,
.profile-edit-card-body .form-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.profile-edit-card-body .input-group {
    border-radius: 8px;
    overflow: hidden;
}

.profile-edit-card-body .input-group .btn {
    border-radius: 0;
    border-left: none;
}

.profile-edit-card-body .input-group .form-control {
    border-radius: 8px 0 0 8px;
}

.profile-edit-card-body .input-group .btn {
    border-radius: 0 8px 8px 0;
}

/* Action Buttons */
.form-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 2.5rem;
    padding-top: 2rem;
    border-top: 1px solid #e9ecef;
}

.btn-back {
    display: inline-flex;
    align-items: center;
    padding: 0.75rem 1.5rem;
    background: white;
    color: #6c757d;
    border: 2px solid #dee2e6;
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.9375rem;
    text-decoration: none;
    transition: all 0.3s ease;
}

.btn-back:hover {
    background: #f8f9fa;
    color: #495057;
    border-color: #adb5bd;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.btn-submit {
    display: inline-flex;
    align-items: center;
    padding: 0.75rem 2rem;
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.9375rem;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(40, 167, 69, 0.25);
}

.btn-submit:hover {
    background: linear-gradient(135deg, #218838 0%, #1aa179 100%);
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(40, 167, 69, 0.35);
    color: white;
}

/* Responsive Design */
@media (max-width: 768px) {
    .profile-edit-modern {
        padding-top: 1rem;
        padding-bottom: 1rem;
    }
    
    .profile-edit-header {
        padding: 1.5rem;
    }
    
    .profile-edit-header-icon {
        width: 60px;
        height: 60px;
        font-size: 30px;
        margin-right: 1rem;
    }
    
    .profile-edit-title {
        font-size: 1.5rem;
    }
    
    .profile-edit-card-body {
        padding: 1.5rem;
    }
    
    .avatar-image {
        width: 120px;
        height: 120px;
    }
    
    .avatar-label {
        width: 40px;
        height: 40px;
    }
    
    .form-actions {
        flex-direction: column;
        gap: 1rem;
    }
    
    .btn-back,
    .btn-submit {
        width: 100%;
        justify-content: center;
    }
}

/* Animation */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.profile-edit-header,
.profile-edit-card {
    animation: fadeInUp 0.5s ease-out;
}

.profile-edit-card {
    animation-delay: 0.1s;
}
</style>
@endpush
