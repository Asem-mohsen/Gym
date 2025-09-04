<style>
.edit-profile-section {
    background-color: #151515;
    min-height: 100vh;
    padding: 60px 0;
}

.edit-profile-header {
    background: linear-gradient(135deg, #0c0806 0%, #f36001 86%);
    color: white;
    padding: 30px 40px;
    border-radius: 15px;
    margin-bottom: 40px;
    text-align: center;
}

.edit-profile-header h3 {
    margin: 0;
    font-size: 24px;
    font-weight: 600;
    color: white;
}

.edit-profile-form {
    background: rgba(255, 255, 255, 0.05);
    padding: 40px;
    border-radius: 15px;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.form-group {
    margin-bottom: 25px;
}

.form-group label {
    font-weight: 600;
    color: white;
    margin-bottom: 10px;
    display: block;
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.form-control {
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 10px;
    padding: 5px 11px;
    transition: all 0.3s ease;
    color: white;
    height: 50px;
}

.form-control:focus {
    background: rgba(255, 255, 255, 0.15);
    border-color: #f36001;
    box-shadow: 0 0 0 0.2rem rgba(243, 96, 1, 0.25);
    color: white;
}

.form-control::placeholder {
    color: rgba(255, 255, 255, 0.6);
}

.form-control option {
    background: #151515;
    color: white;
}

.form-control.is-invalid {
    border-color: #dc3545;
}

.invalid-feedback {
    display: block;
    color: #dc3545;
    font-size: 14px;
    margin-top: 8px;
}

.form-text {
    font-size: 12px;
    color: rgba(255, 255, 255, 0.6);
    margin-top: 8px;
}

.form-control-file {
    background: rgba(255, 255, 255, 0.1);
    border: 2px dashed rgba(255, 255, 255, 0.3);
    border-radius: 10px;
    padding: 20px;
    text-align: center;
    color: rgba(255, 255, 255, 0.8);
    transition: all 0.3s ease;
}

.form-control-file:hover {
    border-color: #f36001;
    background: rgba(255, 255, 255, 0.15);
}

.form-actions {
    margin-top: 40px;
    padding-top: 30px;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
}

.btn {
    padding: 15px 25px;
    border-radius: 10px;
    font-weight: 600;
    transition: all 0.3s ease;
    border: none;
    font-size: 16px;
}

.btn-primary {
    background: linear-gradient(135deg, #0c0806 0%, #f36001 86%);
    color: white;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(243, 96, 1, 0.4);
    color: white;
}

.btn-secondary {
    background: rgba(255, 255, 255, 0.1);
    color: white;
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.btn-secondary:hover {
    background: rgba(255, 255, 255, 0.2);
    transform: translateY(-2px);
    color: white;
}

.btn-block {
    width: 100%;
}

.alert {
    border-radius: 10px;
    border: none;
    margin-bottom: 25px;
    padding: 15px 20px;
}

.alert-success {
    background: rgba(40, 167, 69, 0.2);
    color: #28a745;
    border: 1px solid rgba(40, 167, 69, 0.3);
}

.alert-danger {
    background: rgba(220, 53, 69, 0.2);
    color: #dc3545;
    border: 1px solid rgba(220, 53, 69, 0.3);
}

.close {
    background: none;
    border: none;
    font-size: 20px;
    font-weight: bold;
    opacity: 0.7;
    color: inherit;
}

.close:hover {
    opacity: 1;
}

.text-danger {
    color: #dc3545 !important;
}

/* Photo Gallery Styles */
.photo-gallery-upload {
    margin-top: 15px;
}

.upload-area {
    background: rgba(255, 255, 255, 0.05);
    border: 2px dashed rgba(255, 255, 255, 0.3);
    border-radius: 15px;
    padding: 40px 20px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
    position: relative;
}

.upload-area:hover {
    border-color: #f36001;
    background: rgba(255, 255, 255, 0.1);
}

.upload-area.dragover {
    border-color: #f36001;
    background: rgba(243, 96, 1, 0.1);
}

.upload-content i {
    font-size: 48px;
    color: #f36001;
    margin-bottom: 15px;
    display: block;
}

.upload-content p {
    color: white;
    font-size: 16px;
    margin: 0 0 10px 0;
    font-weight: 500;
}

.upload-content small {
    color: rgba(255, 255, 255, 0.6);
    font-size: 14px;
}

/* Upload Button Styles */
.btn-outline-primary {
    background: transparent;
    border: 2px solid #f36001;
    color: #f36001;
    padding: 8px 20px;
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-outline-primary:hover {
    background: #f36001;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(243, 96, 1, 0.4);
}

.photos-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    gap: 20px;
    margin-top: 20px;
}

.photo-item {
    position: relative;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 10px;
    overflow: hidden;
    border: 1px solid rgba(255, 255, 255, 0.1);
    transition: all 0.3s ease;
}

.photo-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
}

.photo-thumbnail {
    width: 100%;
    height: 150px;
    object-fit: cover;
    display: block;
}

.photo-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 150px; /* Only cover the image height, not the input area */
    background: rgba(0, 0, 0, 0.7);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: all 0.3s ease;
}

.photo-item:hover .photo-overlay {
    opacity: 1;
}

.photo-info {
    padding: 10px;
    background: rgba(255, 255, 255, 0.05);
    position: relative;
    z-index: 10; /* Ensure input area is above overlay */
}

.photo-info input {
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 5px;
    padding: 5px 10px;
    color: white;
    font-size: 12px;
    width: 100%;
}

.photo-info input::placeholder {
    color: rgba(255, 255, 255, 0.5);
}

.photo-info input:focus {
    background: rgba(255, 255, 255, 0.2);
    border-color: #f36001;
    outline: none;
    box-shadow: 0 0 0 2px rgba(243, 96, 1, 0.3);
}

.current-photos h6,
.photos-preview h6 {
    color: #f36001;
    font-weight: 600;
    margin-bottom: 15px;
    font-size: 16px;
}

.delete-photo {
    background: rgba(220, 53, 69, 0.8);
    border: none;
    border-radius: 50%;
    width: 35px;
    height: 35px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 14px;
    transition: all 0.3s ease;
}

.delete-photo:hover {
    background: #dc3545;
    transform: scale(1.1);
}

.photos-preview {
    background: rgba(255, 255, 255, 0.05);
    border-radius: 10px;
    padding: 20px;
    border: 1px solid rgba(255, 255, 255, 0.1);
}

@media (max-width: 768px) {
    .edit-profile-form {
        padding: 25px 20px;
    }
    
    .edit-profile-header {
        padding: 25px 20px;
    }
    
    .form-actions .row {
        margin: 0;
    }
    
    .form-actions .col-md-6 {
        padding: 0 5px;
    }
    
    .btn {
        padding: 12px 20px;
        font-size: 14px;
    }
    
    .photos-grid {
        grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
        gap: 15px;
    }
    
    .upload-area {
        padding: 30px 15px;
    }
    
    .upload-content i {
        font-size: 36px;
    }
}
</style>