<style>
    /* Breadcrumb section overlay */
    .breadcrumb-section {
        position: relative;
    }
    
    .breadcrumb-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.6);
        z-index: 1;
    }
    
    .breadcrumb-text {
        position: relative;
        z-index: 2;
    }
    
    .breadcrumb-text h2 {
        color: white !important;
    }
    
    .breadcrumb-text .bt-option a {
        color: white !important;
    }
    
    .breadcrumb-text .bt-option span {
        color: #ccc !important;
    }
    
    .bh-info .badge {
        color: white !important;
    }
    
    /* Section backgrounds */
    .branch-info-section,
    .branch-gallery-section,
    .branch-map-section,
    .branch-classes-section,
    .branch-services-section,
    .branch-trainers-section {
        background: #151515;
    }
    
    .branch-details h3 {
        color: white;
        margin-bottom: 1rem;
    }
    
    .branch-details p {
        color: #ccc;
        margin-bottom: 1rem;
    }
    
    .branch-item {
        background: transparent;
        border-radius: 10px;
        overflow: hidden;
        transition: transform 0.3s ease;
        margin-bottom: 2rem;
    }
    
    .branch-item:hover {
        transform: translateY(-5px);
    }
    
    .bi-pic img {
        width: 100%;
        height: 200px;
        object-fit: cover;
    }
    
    .bi-text {
        padding: 1.5rem;
    }
    
    .bi-text h4 {
        margin-bottom: 0.5rem;
        color: white;
    }
    
    .bi-info {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin: 1rem 0;
    }

    
    .opening-hours, .social-links {
        background: transparent;
        border-radius: 10px;
        margin-bottom: 2rem;
        width: 80%;
    }
    
    .oh-item {
        display: flex;
        justify-content: space-between;
        padding: 0.5rem 0;
        border-bottom: 1px solid #444;
        color: white;
    }
    
    .oh-item:last-child {
        border-bottom: none;
    }
    
    .oh-item .day {
        color: white;
    }
    
    .oh-item .time {
        color: #ccc;
    }
    
    .time.closed {
        color: #e74c3c;
    }
    
    .sl-list a {
        display: inline-block;
        width: 40px;
        height: 40px;
        background: #333;
        color: white;
        text-align: center;
        line-height: 40px;
        border-radius: 50%;
        margin-right: 0.5rem;
        transition: background 0.3s ease;
    }
    
    .sl-list a:hover {
        background: #e91e63;
    }
    
    /* Gallery Grid Layout */
    .gallery-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 0;
        width: 100%;
        padding: 0;
    }
    
    .gallery-large-item {
        position: relative;
        border-radius: 0;
        overflow: hidden;
        height: 500px;
    }
    
    .gallery-large-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }
    
    .gallery-large-item:hover img {
        transform: scale(1.05);
    }
    
    .gallery-small-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0;
        height: 500px;
    }
    
    .gallery-small-item {
        position: relative;
        border-radius: 0;
        overflow: hidden;
    }
    
    .gallery-small-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }
    
    .gallery-small-item:hover img {
        transform: scale(1.05);
    }
    
    .gi-icon {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 50px;
        height: 50px;
        background: rgba(0, 0, 0, 0.7);
        color: white;
        text-align: center;
        line-height: 50px;
        border-radius: 50%;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .gallery-large-item:hover .gi-icon,
    .gallery-small-item:hover .gi-icon {
        opacity: 1;
    }
    
    /* Responsive Design */
    @media (max-width: 768px) {
        .gallery-grid {
            grid-template-columns: 1fr;
            gap: 0;
            padding: 0;
        }
        
        .gallery-large-item {
            height: 300px;
        }
        
        .gallery-small-grid {
            height: 300px;
            grid-template-columns: 1fr 1fr;
        }
        
        .gallery-small-item {
            height: 50%;
        }
    }
    
    .service-item {
        background: transparent;
        border-radius: 10px;
        overflow: hidden;
        transition: transform 0.3s ease;
        margin-bottom: 2rem;
    }
    
    .service-item:hover {
        transform: translateY(-5px);
    }
    
    .si-pic img {
        width: 100%;
        height: 200px;
        object-fit: cover;
    }
    
    .si-text {
        padding: 1.5rem;
    }
    
    .si-text h4 {
        color: white;
    }
    
    .si-text p {
        color: #ccc;
    }
    
    .trainer-item {
        text-align: center;
        background: transparent;
        border-radius: 10px;
        padding: 2rem;
        margin: 0 1rem;
    }
    
    .ti-pic img {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        object-fit: cover;
        margin-bottom: 1rem;
    }
    
    .ti-text h5 {
        color: white;
    }
    
    .ti-text span {
        color: #ccc;
    }
    
    .contact-info{
        margin-top: 2rem;
    }
    
    .contact-info h4 {
        color: white;
    }
    
    .contact-info p {
        color: #ccc;
    }
    
    .ci-item, .mi-item {
        display: flex;
        align-items: center;
        margin: 1rem 0;
    }
    
    .ci-item i, .mi-item i {
        font-size: 1.5rem;
        color: #e91e63;
        margin-right: 1rem;
    }
    
    .mi-pic img {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        object-fit: cover;
        margin-right: 1rem;
    }
    
    .mi-text h5 {
        color: white;
    }
    
    .mi-text p {
        color: #ccc;
    }
</style>