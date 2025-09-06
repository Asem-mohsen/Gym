<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class GymSetting extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'site_setting_id',
        'primary_color',
        'secondary_color',
        'accent_color',
        'text_color',
        'font_family',
        'border_radius',
        'box_shadow',
        'home_page_sections',
        'section_styles',
        'media_settings',
        'page_texts',
    ];

    protected $casts = [
        'home_page_sections' => 'array',
        'section_styles' => 'array',
        'media_settings' => 'array',
        'page_texts' => 'array',
    ];

    /**
     * Get the site setting that owns the gym setting.
     */
    public function siteSetting(): BelongsTo
    {
        return $this->belongsTo(SiteSetting::class, 'site_setting_id');
    }

    /**
     * Get all branding fields
     */
    public static function getBrandingFields(): array
    {
        return [
            'primary_color',
            'secondary_color',
            'accent_color',
            'text_color',
            'font_family',
            'border_radius',
            'box_shadow'
        ];
    }

    /**
     * Get default home page sections
     */
    public static function getDefaultHomePageSections(): array
    {
        return [
            'hero' => true,
            'choseus' => true,
            'classes' => true,
            'banner' => true,
            'memberships' => true,
            'gallery' => true,
            'team' => true
        ];
    }

    /**
     * Get available media types
     */
    public static function getAvailableMediaTypes(): array
    {
        return [
            // Home page media
            'hero_banner',
            'choseus_banner',
            'classes_banner',
            'banner_section_bg',
            'memberships_banner',
            'gallery_banner',
            'team_banner',
            
            // Page-specific media
            'login_page_image',
            'register_page_image',
            'services_hero_banner',
            'services_middle_image',
            'about_team_image',
            'about_gallery_image',
            'about_contact_image',
            'contact_page_image',
            'blog_page_image'
        ];
    }

    /**
     * Get media types that support multiple files
     */
    public static function getMultipleFileMediaTypes(): array
    {
        return [
            // Home page media
            'hero_banner' => 2, // 2 images for hero slider
            'choseus_banner' => 1,
            'classes_banner' => 1,
            'banner_section_bg' => 1,
            'memberships_banner' => 1,
            'gallery_banner' => 1,
            'team_banner' => 1,
            
            // Page-specific media (all single images)
            'login_page_image' => 1,
            'register_page_image' => 1,
            'services_hero_banner' => 1,
            'services_middle_image' => 1,
            'about_team_image' => 1,
            'about_gallery_image' => 1,
            'about_contact_image' => 1,
            'contact_page_image' => 1,
            'blog_page_image' => 1
        ];
    }

    /**
     * Register media collections
     */
    public function registerMediaCollections(): void
    {
        $mediaTypes = self::getAvailableMediaTypes();
        $multipleFileTypes = self::getMultipleFileMediaTypes();
        
        foreach ($mediaTypes as $mediaType) {
            $collection = $this->addMediaCollection($mediaType)
                ->useDisk('public');
            
            // Set single file for types that only need one image
            if (!isset($multipleFileTypes[$mediaType]) || $multipleFileTypes[$mediaType] <= 1) {
                $collection->singleFile();
            }
        }
    }

    /**
     * Get only the non-null branding values
     */
    public function getNonNullBrandingValues(): array
    {
        $brandingFields = self::getBrandingFields();
        $values = [];
        
        foreach ($brandingFields as $field) {
            if ($this->$field !== null) {
                $values[$field] = $this->$field;
            }
        }
        
        return $values;
    }

    /**
     * Get available page types for text customization
     */
    public static function getAvailablePageTypes(): array
    {
        return [
            'login' => 'Login Page',
            'register' => 'Register Page',
            'auth_common' => 'Auth Pages (Common)',
            'home' => 'Home Page',
            'about' => 'About Us Page',
            'services' => 'Services Page',
            'contact' => 'Contact Us Page',
            'team' => 'Our Team Page',
            'gallery' => 'Gallery Page',
            'classes' => 'Classes Page'
        ];
    }

    /**
     * Get default text fields for each page type
     */
    public static function getDefaultPageTexts(): array
    {
        return [
            'login' => [
                'title' => 'Welcome Back',
                'subtitle' => 'Sign in to your account',
                'button_text' => 'Sign In',
                'forgot_password_text' => 'Forgot your password?',
                'register_link_text' => "Don't have an account? Sign up"
            ],
            'register' => [
                'title' => 'Join Our Gym',
                'subtitle' => 'Create your account to get started',
                'button_text' => 'Create Account',
                'login_link_text' => 'Already have an account? Sign in'
            ],
            'auth_common' => [
                'platform_title' => 'Unlock your full potential',
                'platform_description' => 'Stay consistent, stay strong — your fitness journey starts here'
            ],
            'home' => [
                'hero_title' => 'Transform Your Body',
                'hero_subtitle' => 'Achieve your fitness goals with our expert trainers',
                'hero_button_text' => 'Get Started',
                'choseus_title' => 'Why Choose Us',
                'choseus_subtitle' => 'We provide the best fitness experience',
                'classes_title' => 'Our Classes',
                'classes_subtitle' => 'Join our amazing fitness classes',
                'memberships_title' => 'Membership Plans',
                'memberships_subtitle' => 'Choose the plan that fits your needs',
                'gallery_title' => 'Our Gallery',
                'gallery_subtitle' => 'See our amazing facilities',
                'team_title' => 'Meet Our Team',
                'team_subtitle' => 'Professional trainers dedicated to your success'
            ],
            'about' => [
                'title' => 'About us',
                'hero_title' => 'PUSH YOUR LIMITS FORWARD',
                'hero_subtitle' => 'Why chose us?',
                'about_title' => 'What we have done',
                'about_subtitle' => 'About Us',
                'team_title' => 'TRAIN WITH EXPERTS',
                'team_subtitle' => 'Our Team',
                'testimonial_title' => 'Our cilent say',
                'testimonial_subtitle' => 'Testimonial'
            ],
            'services' => [
                'title' => 'Services',
                'hero_title' => 'PUSH YOUR LIMITS FORWARD',
                'hero_subtitle' => 'What we do?'
            ],
            'contact' => [
                'title' => 'Contact Us',
                'subtitle' => 'Get in touch with our team',
                'form_title' => 'Send us a Message',
                'form_subtitle' => 'We would love to hear from you',
                'button_text' => 'Send Message'
            ],
            'team' => [
                'title' => 'Our Team',
                'hero_title' => 'TRAIN WITH EXPERTS',
                'hero_subtitle' => 'Our Team'
            ],
            'gallery' => [
                'title' => 'Gallery',
                'subtitle' => 'See our amazing facilities',
                'hero_title' => 'Our Facilities',
                'hero_subtitle' => 'State-of-the-art equipment and modern facilities'
            ],
            'classes' => [
                'title' => 'Class Timetable',
                'hero_title' => 'Timetable',
                'hero_subtitle' => 'Classes'
            ]
        ];
    }

    /**
     * Get page texts with defaults
     */
    public function getPageTexts(): array
    {
        $defaultTexts = self::getDefaultPageTexts();
        $customTexts = $this->page_texts ?? [];
        
        // Merge custom texts with defaults
        $result = [];
        foreach ($defaultTexts as $page => $fields) {
            $result[$page] = array_merge($fields, $customTexts[$page] ?? []);
        }
        
        return $result;
    }

    /**
     * Get texts for a specific page
     */
    public function getPageText(string $page): array
    {
        $allTexts = $this->getPageTexts();
        return $allTexts[$page] ?? [];
    }
}
