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
        'repeater_fields',
    ];

    protected $casts = [
        'home_page_sections' => 'array',
        'section_styles' => 'array',
        'media_settings' => 'array',
        'page_texts' => 'array',
        'repeater_fields' => 'array',
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
            'forget_password_page_image',
            'reset_password_page_image',
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
            'forget_password_page_image' => 1,
            'reset_password_page_image' => 1,
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

    /**
     * Get default repeater configurations for different sections
     */
    public static function getDefaultRepeaterConfigs(): array
    {
        return [
            'home_choseus' => [
                'title' => 'Why Choose Us Section',
                'description' => 'Features and benefits that make your gym stand out',
                'page_location' => 'Home Page',
                'section_location' => 'Below the main banner, above classes section',
                'max_items' => 4,
                'fields' => [
                    'icon' => [
                        'type' => 'text',
                        'label' => 'Icon Class',
                        'placeholder' => 'e.g., flaticon-034-stationary-bike',
                        'help_text' => 'Use Flaticon classes. <a href="https://www.flaticon.com/" target="_blank">Browse icons here</a>',
                        'required' => true
                    ],
                    'title' => [
                        'type' => 'text',
                        'label' => 'Feature Title',
                        'placeholder' => 'e.g., Modern equipment',
                        'required' => true
                    ],
                    'description' => [
                        'type' => 'textarea',
                        'label' => 'Feature Description',
                        'placeholder' => 'Brief description of the feature',
                        'required' => true
                    ]
                ],
                'default_items' => [
                    [
                        'icon' => 'flaticon-034-stationary-bike',
                        'title' => 'Modern equipment',
                        'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut dolore facilisis.'
                    ],
                    [
                        'icon' => 'flaticon-033-juice',
                        'title' => 'Healthy nutrition plan',
                        'description' => 'Quis ipsum suspendisse ultrices gravida. Risus commodo viverra maecenas accumsan lacus vel facilisis.'
                    ],
                    [
                        'icon' => 'flaticon-002-dumbell',
                        'title' => 'Professional training plan',
                        'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut dolore facilisis.'
                    ],
                    [
                        'icon' => 'flaticon-014-heart-beat',
                        'title' => 'Unique to your needs',
                        'description' => 'Quis ipsum suspendisse ultrices gravida. Risus commodo viverra maecenas accumsan lacus vel facilisis.'
                    ]
                ]
            ],
            'home_hero' => [
                'title' => 'Main Banner Section',
                'description' => 'The large banner at the top of your homepage with rotating slides',
                'page_location' => 'Home Page',
                'section_location' => 'Top of the page (first thing visitors see)',
                'max_items' => 2,
                'fields' => [
                    'title' => [
                        'type' => 'text',
                        'label' => 'Main Headline',
                        'placeholder' => 'e.g., Be strong training hard',
                        'required' => true
                    ],
                    'subtitle' => [
                        'type' => 'text',
                        'label' => 'Subtitle',
                        'placeholder' => 'e.g., Shape your body',
                        'required' => true
                    ],
                    'button_text' => [
                        'type' => 'text',
                        'label' => 'Button Text',
                        'placeholder' => 'e.g., Get info',
                        'required' => true
                    ],
                    'button_link' => [
                        'type' => 'select',
                        'label' => 'Button Link',
                        'placeholder' => 'Select a page to link to',
                        'help_text' => 'Choose which page the button will redirect users to',
                        'required' => false,
                        'options' => [
                            ['value' => '#', 'label' => 'Same Page (No redirect)'],
                            ['value' => 'home', 'label' => 'Home Page'],
                            ['value' => 'about', 'label' => 'About Us'],
                            ['value' => 'services', 'label' => 'Services'],
                            ['value' => 'classes', 'label' => 'Classes'],
                            ['value' => 'memberships', 'label' => 'Memberships'],
                            ['value' => 'gallery', 'label' => 'Gallery'],
                            ['value' => 'blog', 'label' => 'Blog'],
                            ['value' => 'team', 'label' => 'Our Team'],
                            ['value' => 'contact', 'label' => 'Contact Us']
                        ]
                    ]
                ],
                'default_items' => [
                    [
                        'title' => 'Be strong training hard',
                        'subtitle' => 'Shape your body',
                        'button_text' => 'Get info',
                        'button_link' => '#'
                    ],
                    [
                        'title' => 'Be strong training hard',
                        'subtitle' => 'Shape your body',
                        'button_text' => 'Get info',
                        'button_link' => '#'
                    ]
                ]
            ],
            'about_features' => [
                'title' => 'About Page Features',
                'description' => 'Feature highlights on the About Us page',
                'page_location' => 'About Us Page',
                'section_location' => 'Main content area of the about page',
                'max_items' => 6,
                'fields' => [
                    'icon' => [
                        'type' => 'text',
                        'label' => 'Icon Class',
                        'placeholder' => 'e.g., flaticon-034-stationary-bike',
                        'help_text' => 'Use Flaticon classes. <a href="https://www.flaticon.com/" target="_blank">Browse icons here</a>',
                        'required' => true
                    ],
                    'title' => [
                        'type' => 'text',
                        'label' => 'Feature Title',
                        'placeholder' => 'Feature title',
                        'required' => true
                    ],
                    'description' => [
                        'type' => 'textarea',
                        'label' => 'Feature Description',
                        'placeholder' => 'Feature description',
                        'required' => true
                    ]
                ],
                'default_items' => []
            ]
        ];
    }

    /**
     * Get repeater data for a specific section
     */
    public function getRepeaterData(string $section): array
    {
        $repeaterFields = $this->repeater_fields ?? [];
        $defaultConfigs = self::getDefaultRepeaterConfigs();
        
        if (!isset($defaultConfigs[$section])) {
            return [];
        }
        
        $config = $defaultConfigs[$section];
        $customData = $repeaterFields[$section] ?? [];
        
        // If no custom data, return default items
        if (empty($customData)) {
            return $config['default_items'] ?? [];
        }
        
        return $customData;
    }

    /**
     * Get all repeater data
     */
    public function getAllRepeaterData(): array
    {
        $repeaterFields = $this->repeater_fields ?? [];
        $defaultConfigs = self::getDefaultRepeaterConfigs();
        $result = [];
        
        foreach ($defaultConfigs as $section => $config) {
            $result[$section] = $this->getRepeaterData($section);
        }
        
        return $result;
    }

    /**
     * Update repeater data for a specific section
     */
    public function updateRepeaterData(string $section, array $data): void
    {
        $repeaterFields = $this->repeater_fields ?? [];
        $repeaterFields[$section] = $data;
        $this->update(['repeater_fields' => $repeaterFields]);
    }

    /**
     * Convert page name to actual route URL
     */
    public static function convertPageNameToUrl(string $pageName, string $siteSettingSlug): string
    {
        if ($pageName === '#') {
            return '#';
        }

        return match($pageName) {
            'home' => route('user.home', ['siteSetting' => $siteSettingSlug]),
            'about' => route('user.about-us', ['siteSetting' => $siteSettingSlug]),
            'services' => route('user.services.index', ['siteSetting' => $siteSettingSlug]),
            'classes' => route('user.classes.index', ['siteSetting' => $siteSettingSlug]),
            'memberships' => route('user.memberships.index', ['siteSetting' => $siteSettingSlug]),
            'gallery' => route('user.gallery', ['siteSetting' => $siteSettingSlug]),
            'blog' => route('user.blog', ['siteSetting' => $siteSettingSlug]),
            'team' => route('user.team', ['siteSetting' => $siteSettingSlug]),
            'contact' => route('user.contact', ['siteSetting' => $siteSettingSlug]),
            default => '#'
        };
    }
}
