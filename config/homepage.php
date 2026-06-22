<?php

return [
    'meta' => [
        'title' => 'Professional Printing Services Malawi | Government Press',
        'description' => 'Get professional, reliable printing services for your business, organization, or government needs. Quality documents, brochures, certificates, banners, and more since 1894.',
    ],

    'brand' => [
        'name' => 'Department of Printing Services',
        'short_name' => 'Government Press',
        'tagline' => 'Best Printers for the Nation',
        'logo' => 'images/brand/printing-services-logo.png',
    ],

    'utility' => [
        'notice' => 'The official website of the Department of Printing Services (Government Press), under the Office of the President and Cabinet.',
        'phone' => '+265 (0) 995 881 711',
        'phone_href' => 'tel:+2650995881711',
        'email' => 'printingservices@opc.gov.mw',
        'email_href' => 'mailto:printingservices@opc.gov.mw',
        'hours' => 'Monday to Friday',
        'charter_label' => 'Service Charter',
        'charter_file' => 'documents/service-charter.pdf',
        'search_placeholder' => 'Search services, resources, and updates',
        'cart_label' => 'Cart',
        'cart_count' => 0,
        'wishlist_label' => 'Wishlist',
        'wishlist_count' => 0,
        'quotation_label' => 'Request Quotation',
        'client_area_label' => 'Client Area',
        'whatsapp' => '265995881711',
        'social_links' => [
            ['label' => 'Facebook', 'href' => '#', 'icon' => 'facebook'],
            ['label' => 'LinkedIn', 'href' => '#', 'icon' => 'linkedin'],
            ['label' => 'X', 'href' => '#', 'icon' => 'x-social'],
            ['label' => 'Instagram', 'href' => '#', 'icon' => 'instagram'],
        ],
    ],

    'public_menu' => [
        ['key' => 'home', 'label' => 'Home', 'route' => 'home'],
        ['key' => 'services', 'label' => 'Services', 'route' => 'services'],
        ['key' => 'products', 'label' => 'Products', 'route' => 'products'],
        ['key' => 'training', 'label' => 'Training', 'route' => 'training'],
        ['key' => 'about', 'label' => 'About Us', 'route' => 'about'],
        ['key' => 'teams', 'label' => 'Team', 'route' => 'teams'],
        ['key' => 'contact', 'label' => 'Contact', 'route' => 'contact'],
    ],

    'navigation' => [
        [
            'key' => 'about',
            'label' => 'About Us',
            'href' => '#overview',
            'panel' => [
                'blurb' => 'For over 130 years, we\'ve been delivering professional printing services you can trust.',
                'groups' => [
                    [
                        'title' => 'Learn About Us',
                        'links' => [
                            [
                                'label' => 'Our Heritage',
                                'href' => '#overview',
                                'description' => 'Discover how we\'ve earned trust serving Malawi since 1894.',
                            ],
                            [
                                'label' => 'What We Do',
                                'href' => '#overview',
                                'description' => 'See our commitment to quality, reliability, and professional service.',
                            ],
                        ],
                    ],
                    [
                        'title' => 'Find Us',
                        'links' => [
                            [
                                'label' => 'Office Locations',
                                'href' => '#overview',
                                'description' => 'Visit us in Lilongwe, Zomba, or Mzuzu.',
                            ],
                            [
                                'label' => 'Training School',
                                'href' => '#school',
                                'description' => 'Build your career in professional printing.',
                            ],
                        ],
                    ],
                ],
            ],
        ],
        [
            'key' => 'services',
            'label' => 'Services',
            'href' => '#services',
            'panel' => [
                'blurb' => 'From design and printing to binding and finishing, we handle every stage of your project.',
                'groups' => [
                    [
                        'title' => 'Print Services',
                        'links' => [
                            [
                                'label' => 'Secure Printing',
                                'href' => '#services',
                                'description' => 'Confidential documents handled with integrity.',
                            ],
                            [
                                'label' => 'Government Publications',
                                'href' => '#services',
                                'description' => 'Professional reports, manuals, and official documents.',
                            ],
                            [
                                'label' => 'Commercial Printing',
                                'href' => '#services',
                                'description' => 'Brochures, flyers, banners, and promotional materials.',
                            ],
                        ],
                    ],
                    [
                        'title' => 'Popular Products',
                        'links' => [
                            [
                                'label' => 'See All Products',
                                'href' => '#catalogue',
                                'description' => 'Browse our full range of printing options.',
                            ],
                            [
                                'label' => 'Certificates & Awards',
                                'href' => '#catalogue',
                                'description' => 'Professional-quality credentials.',
                            ],
                            [
                                'label' => 'Request A Quote',
                                'href' => '#contact',
                                'description' => 'Get started on your next project.',
                            ],
                        ],
                    ],
                ],
            ],
        ],
        [
            'key' => 'resources',
            'label' => 'Resources',
            'href' => '#resources',
            'panel' => [
                'blurb' => 'Everything you need to know about working with us.',
                'groups' => [
                    [
                        'title' => 'Information',
                        'links' => [
                            [
                                'label' => 'Service Charter',
                                'href' => '#charter',
                                'description' => 'Our commitments and service standards.',
                            ],
                            [
                                'label' => 'How We Work',
                                'href' => '#process',
                                'description' => 'See our 4-step process from idea to finished product.',
                            ],
                        ],
                    ],
                    [
                        'title' => 'Contact & Support',
                        'links' => [
                            [
                                'label' => 'Get A Quotation',
                                'href' => '#contact',
                                'description' => 'Share your project details with our team.',
                            ],
                            [
                                'label' => 'Contact Details',
                                'href' => '#contact',
                                'description' => 'Phone, email, and office locations.',
                            ],
                        ],
                    ],
                ],
            ],
        ],
        [
            'key' => 'contact',
            'label' => 'Contact',
            'href' => '#contact',
        ],
    ],

    'hero_slides' => [
        [
            'eyebrow' => '',
            'title' => 'Your Trusted Partner for Professional Printing',
            'description' => 'Whether you need official documents, reports, certificates, brochures, banners, or promotional materials, our experienced team is ready to deliver high-quality printing you can trust.',
            'image' => 'images/banner-1.png',
            'primary_label' => 'Request Quotation',
            'primary_href' => '#contact',
            'secondary_label' => 'Explore Services',                                              
            'secondary_href' => '#services',
            'points' => [
                'High-quality results from start to finish',
                'Fast, reliable turnaround for your projects',
                'Serving Malawi for over 130 years',
            ],
        ],
        [
            'eyebrow' => 'Our Facilities',
            'title' => 'Modern Equipment. Experienced Hands. Outstanding Results.',
            'description' => 'We invest in the latest printing technology and work with skilled professionals who take pride in delivering excellence on every project, every time.',
            'image' => 'images/banner-2.png',
            'primary_label' => 'See Our Work',
            'primary_href' => '#catalogue',
            'secondary_label' => 'How We Work',
            'secondary_href' => '#process',
            'points' => [
                'State-of-the-art printing equipment',
                'Quality checks at every production stage',
                'Fast turnaround without cutting corners',
            ],
        ],
        [
            'eyebrow' => 'Secure & Confidential',
            'title' => 'Peace of Mind for Your Sensitive Documents',
            'description' => 'We specialize in secure, confidential printing for sensitive materials like certificates, exam papers, official records, and secure documents—where privacy and integrity matter most.',
            'image' => 'images/banner-3.png',
            'primary_label' => 'Secure Printing Service',
            'primary_href' => '#services',
            'secondary_label' => 'Contact Our Team',
            'secondary_href' => '#contact',
            'points' => [
                'Confidential handling from briefing to delivery',
                'Secure workflows you can depend on',
                'Built on decades of trusted service',
            ],
        ],
        [
            'eyebrow' => 'Invest in Skills',
            'title' => 'Building Tomorrow\'s Printing Professionals',
            'description' => 'Want to develop printing skills? Our TEVETA-accredited training school offers hands-on learning in modern printing technology and professional production practices.',
            'image' => 'images/banner-4.png',
            'primary_label' => 'Explore Training',
            'primary_href' => '#school',
            'secondary_label' => 'Get In Touch',
            'secondary_href' => '#contact',
            'points' => [
                'Learn from experienced print professionals',
                'TEVETA-accredited certification program',
                'Real-world training in modern equipment',
            ],
        ],
        [
            'eyebrow' => 'Complete Solutions',
            'title' => 'From Your Idea to Your Finished Print Product',
            'description' => 'We handle every stage of your project—design support, professional printing, binding, finishing, and delivery—so you get exactly what you need, on time and on budget.',
            'image' => 'images/banner-5.png',
            'primary_label' => 'Explore Services',
            'primary_href' => '#services',
            'secondary_label' => 'Request A Quote',
            'secondary_href' => '#contact',
            'points' => [
                'End-to-end project management',
                'Professional support at every step',
                'Results that exceed expectations',
            ],
        ],
    ],

    'stats' => [
        ['value' => '1894', 'label' => 'Established'],
        ['value' => '3', 'label' => 'Operational touchpoints'],
        ['value' => '1970', 'label' => 'Treasury fund re-designation'],
        ['value' => 'National', 'label' => 'Public and private service reach'],
    ],

    'highlights' => [
        ['icon' => 'award', 'title' => 'Premium Quality', 'description' => 'We don\'t cut corners. Every print job gets our full attention and expertise.'],
        ['icon' => 'clock', 'title' => 'Fast Turnaround', 'description' => 'We meet your deadlines without compromising on quality.'],
        ['icon' => 'users', 'title' => 'Experienced Team', 'description' => 'Our professionals bring years of printing experience to every project.'],
        ['icon' => 'smile', 'title' => 'Your Success', 'description' => 'Your satisfaction is our goal. We want you to be happy with your results.'],
    ],

    'values' => [
        ['icon' => 'clock', 'title' => 'Always On Time', 'description' => 'We respect your schedule. Your deadlines become our priorities.'],
        ['icon' => 'users', 'title' => 'Professionals You Can Trust', 'description' => 'Our experienced team brings expertise, skill, and a commitment to your success.'],
        ['icon' => 'shield-check', 'title' => 'Your Privacy Matters', 'description' => 'Confidential documents are handled with the utmost care and integrity.'],
        ['icon' => 'search', 'title' => 'Attention to Every Detail', 'description' => 'We don\'t miss anything. From color to finishing, we get it right.'],
        ['icon' => 'handshake', 'title' => 'Customer First', 'description' => 'Your satisfaction is at the heart of everything we do.'],
    ],

    'trust_metrics' => [
        ['icon' => 'check-circle', 'title' => 'Quality Materials', 'description' => 'We use only quality materials that produce long-lasting, professional results.'],
        ['icon' => 'trending-up', 'title' => 'Latest Technology', 'description' => 'Modern equipment means better quality, faster production, and more options for you.'],
        ['icon' => 'shield-check', 'title' => 'Safe & Responsible', 'description' => 'We prioritize both security and environmental responsibility in everything we do.'],
        ['icon' => 'users', 'title' => 'Trusted for 130 Years', 'description' => 'Proven track record serving Malawi. You can count on us to deliver.'],
    ],

    'featured_images' => [
        ['image' => 'images/home/facility-floor.png', 'alt' => 'Government Press production floor'],
        ['image' => 'images/home/manual-cover.webp', 'alt' => 'Official publication sample'],
        ['image' => 'images/home/speech-cover.webp', 'alt' => 'Printed institutional materials'],
        ['image' => 'images/home/manual-cover.webp', 'alt' => 'Secure print output'],
    ],

    'service_icons' => [
        'shield' => 'shield-check',
        'publication' => 'book-open',
        'press' => 'printer',
        'design' => 'pen-tool',
        'finish' => 'layers',
        'school' => 'graduation-cap',
    ],

    'service_images' => [
        'shield' => 'images/secure-printing.png',
        'publication' => 'images/govt-publications.png',
        'press' => 'images/banner-1.png',
        'design' => 'images/proof-reading.png',
        'finish' => 'images/banner-2.png',
        'school' => 'images/training-school.png',
    ],

    'page_hero' => [
        'image' => 'images/thumbnail.png',
    ],

    'footer_addresses' => [
        [
            'label' => 'Lilongwe - Production Office',
            'address' => 'Along Chilambura Road, opposite Chipiku Stores, Lilongwe',
            'map_url' => 'https://www.google.com/maps/search/?api=1&query=Chilambura+Road%2C+Lilongwe%2C+Malawi',
        ],
        [
            'label' => 'Lilongwe - Corporate Office',
            'address' => 'Lilongwe',
            'map_url' => '',
        ],
        [
            'label' => 'Zomba Office',
            'address' => 'Opposite MANEB Offices, Zomba',
            'map_url' => 'https://www.google.com/maps/search/?api=1&query=MANEB+Offices%2C+Zomba%2C+Malawi',
        ],
    ],

    'overview' => [
        'badge' => 'Since 1894',
        'eyebrow' => 'Who We Are',
        'title' => 'Over 130 Years of Excellence in Printing',
        'lead' => 'For more than a century, we\'ve been helping government institutions, organizations, and businesses bring their printing projects to life with quality, reliability, and professionalism.',
        'body' => [
            'What started as a government printing facility has grown into one of Malawi\'s most trusted printing organizations. We\'ve earned our reputation by consistently delivering excellent results, meeting tight deadlines, and handling sensitive projects with the utmost care and confidentiality.',
            'Today, we serve both public and private sector clients with a full range of printing services—from official publications and reports to certificates, promotional materials, and secure printing for sensitive documents.',
            'Our success comes from three things: modern equipment, skilled professionals who take pride in their work, and a commitment to putting your needs first. Whether you\'re a government ministry, nonprofit organization, or private business, we\'re here to make your printing projects successful.',
        ],
        'mission' => 'To be your trusted partner in professional printing—delivering quality, reliability, and excellence on every project.',
        'points' => [
            'High-quality printing with modern equipment',
            'Fast, dependable service with experienced professionals',
            'Secure, confidential handling of sensitive materials',
            'Serving customers nationwide with offices in Lilongwe, Zomba, and Mzuzu',
        ],
        'cta_label' => 'Explore Services',
        'cta_href' => '#services',
        'cards' => [
            [
                'title' => 'Lilongwe Office',
                'description' => 'Our main office handles quotations, customer support, and coordination for your projects.',
            ],
            [
                'title' => 'Zomba Works',
                'description' => 'Our largest production facility brings your jobs to life with state-of-the-art equipment and skilled technicians.',
            ],
            [
                'title' => 'Nationwide Service',
                'description' => 'We also serve customers from Mzuzu, ensuring quality printing services across the country.',
            ],
            [
                'title' => 'Complete Solutions',
                'description' => 'From your first inquiry through final delivery, we manage every step to ensure success.',
            ],
        ],
        'image_primary' => 'images/home/facility-floor.png',
        'image_secondary' => 'images/home/manual-cover.webp',
    ],

    'services' => [
        [
            'icon' => 'shield',
            'category' => 'For Sensitive Documents',
            'title' => 'Secure Printing',
            'description' => 'We specialize in secure, confidential printing for sensitive materials such as certificates, examination papers, official records, and other confidential publications. Your privacy is guaranteed at every stage.',
            'points' => [
                'Confidential handling from start to finish',
                'Secure workflows for sensitive documents',
            ],
        ],
        [
            'icon' => 'publication',
            'category' => 'Government & Organizations',
            'title' => 'Government Publications & Reports',
            'description' => 'We produce professional reports, manuals, official forms, gazettes, and institutional publications that look great and stand the test of time.',
            'points' => [
                'Professional layouts and clear hierarchy',
                'Consistent quality for recurring publications',
            ],
        ],
        [
            'icon' => 'press',
            'category' => 'For Your Business',
            'title' => 'Commercial Printing',
            'description' => 'We print brochures, flyers, posters, business cards, banners, calendars, reports, and many other materials for businesses, organizations, and events across Malawi.',
            'points' => [
                'Perfect for promotions, campaigns, and events',
                'Everything from small orders to large print runs',
            ],
        ],
        [
            'icon' => 'design',
            'category' => 'Pre-Print Support',
            'title' => 'Design & Print Preparation',
            'description' => 'Need help with design or aren\'t sure if your artwork is ready? We\'ll review your files, prepare artwork, and make sure everything is perfect before we print.',
            'points' => [
                'Professional proof review before printing',
                'Guidance on sizing, colors, and production readiness',
            ],
        ],
        [
            'icon' => 'finish',
            'category' => 'Finishing & Delivery',
            'title' => 'Binding, Finishing & Packaging',
            'description' => 'We complete your project with professional finishing touches—binding, trimming, and careful packaging—so your final product looks polished and professional.',
            'points' => [
                'Durable finishes for manuals, reports, and certificates',
                'Proper packaging for safe collection or delivery',
            ],
        ],
        [
            'icon' => 'school',
            'category' => 'Build Your Skills',
            'title' => 'Printing Training School',
            'description' => 'Want to build a career in printing? Our TEVETA-accredited training program offers hands-on learning in modern printing technology, finishing techniques, and professional production standards.',
            'points' => [
                'Learn from experienced printing professionals',
                'Training aligned to real-world production work',
            ],
        ],
    ],

    'catalogue' => [
        'eyebrow' => 'What We Print',
        'title' => 'Popular Print Products for Every Need',
        'lead' => 'From promotional materials to official documents, we print a wide range of products for government institutions, organizations, and businesses throughout Malawi.',
        'products' => [
            ['name' => 'Banners (Vinyl)', 'type' => 'Promotion & Events', 'note' => 'High-impact displays for campaigns and events'],
            ['name' => 'Brochures (Tri-Fold)', 'type' => 'Marketing Materials', 'note' => 'Compact information packs for programs and services'],
            ['name' => 'Business Cards', 'type' => 'Professional Identity', 'note' => 'Premium cards that make a great first impression'],
            ['name' => 'Calendars', 'type' => 'Branded Materials', 'note' => 'Year-round promotion for your organization'],
            ['name' => 'Certificates', 'type' => 'Credentials & Awards', 'note' => 'Professional-quality certificates for achievements and qualifications'],
            ['name' => 'Flyers (A5)', 'type' => 'Quick Promotion', 'note' => 'Cost-effective materials for outreach and announcements'],
            ['name' => 'Posters (A2)', 'type' => 'Large Format', 'note' => 'Eye-catching displays for awareness and announcements'],
        ],
        'image_primary' => 'images/home/manual-cover.webp',
        'image_secondary' => 'images/home/speech-cover.webp',
    ],

    'process' => [
        'eyebrow' => 'Our Process',
        'title' => 'How We Bring Your Project to Life',
        'lead' => 'We\'ve simplified our process to make it easy for you to understand how your project moves from your idea to your finished print products.',
        'steps' => [
            [
                'icon' => 'brief',
                'label' => '01',
                'title' => 'Tell Us What You Need',
                'description' => 'Share your printing requirements—what you\'re printing, how many copies, what size, preferred finish, and when you need it. Our team will answer any questions.',
            ],
            [
                'icon' => 'proof',
                'label' => '02',
                'title' => 'Review & Approve',
                'description' => 'We\'ll prepare the proofs and specifications for your approval. You\'ll see exactly what you\'re getting before printing begins, so there are no surprises.',
            ],
            [
                'icon' => 'shield',
                'label' => '03',
                'title' => 'Professional Production',
                'description' => 'Your project is printed using quality materials, modern equipment, and our skilled, experienced technicians who care about getting it right.',
            ],
            [
                'icon' => 'delivery',
                'label' => '04',
                'title' => 'Collection or Delivery',
                'description' => 'Once complete, your order is finished, carefully packaged, and ready for collection from one of our offices or delivery to your location.',
            ],
        ],
    ],

    'school' => [
        'eyebrow' => 'Training & Development',
        'title' => 'Build Your Career in Professional Printing',
        'lead' => 'Our TEVETA-accredited Printing Training School offers practical, hands-on training in modern printing technology, finishing techniques, and professional production standards—preparing you for a rewarding career in the printing industry.',
        'body' => [
            'We believe in continuous skill development. Our training program is built on real production experience and taught by professionals with years of industry knowledge.',
            'Whether you\'re starting a career in printing or looking to upgrade your skills, our TEVETA-accredited certificate program provides the training you need to succeed in this important industry.',
        ],
        'focus_areas' => [
            'Modern printing technology and equipment',
            'Professional design and layout preparation',
            'Finishing techniques and quality standards',
            'Real-world production workflows and best practices',
        ],
        'cta_label' => 'Register Interest',
        'cta_href' => '/training/enroll',
        'image' => 'images/training-school2.png',
    ],

    'updates' => [
        [
            'tag' => 'Featured',
            'title' => 'Service Charter available for download',
            'excerpt' => 'The department\'s service charter is now accessible on this website, outlining our commitments and standards for clients.',
            'cta_label' => 'Open Charter',
            'cta_href' => 'documents/service-charter.pdf',
            'icon' => 'document',
        ],
        [
            'tag' => 'Current',
            'title' => 'Quotation requests can begin with a simple production brief',
            'excerpt' => 'Phone, email, and office details are already available and can guide visitors on how to start a print request while the CMS workflows are being built.',
            'cta_label' => 'See Contact Details',
            'cta_href' => '#contact',
            'icon' => 'brief',
        ],
        [
            'tag' => 'Spotlight',
            'title' => 'Printing Training School remains a flagship story',
            'excerpt' => 'Enroll in our TEVETA-accredited certificate program in printing, offered by the Government Press Training School.',
            'cta_label' => 'Explore The School',
            'cta_href' => '#school',
            'icon' => 'school',
        ],
    ],

    'resources' => [
        [
            'title' => 'Service Charter',
            'description' => 'Download the current public service charter for the department.',
            'href' => 'documents/service-charter.pdf',
            'icon' => 'document',
        ],
        [
            'title' => 'Front Office Contact',
            'description' => 'Phone and email details for quotation support and general enquiries.',
            'href' => '#contact',
            'icon' => 'phone',
        ],
        [
            'title' => 'Office Locations',
            'description' => 'Find the Lilongwe and Zomba contact points highlighted on the public site.',
            'href' => '#contact',
            'icon' => 'location',
        ],
    ],

    'teams' => [
        'eyebrow' => 'Our People',
        'title' => 'Meet Our Team',
        'lead' => 'Get to know the professionals behind Government Press. Our team brings decades of printing expertise, customer service, and technical skill to every project.',
    ],

    'contact' => [
        'eyebrow' => 'Get In Touch',
        'title' => 'Let\'s Discuss Your Printing Needs',
        'lead' => 'Ready to get started? Whether you need a quotation, have questions about our services, or want to discuss a printing project, our team is here to help. Contact us today.',
        'phone' => '+265 (0) 995 881 711',
        'phone_href' => 'tel:+2650995881711',
        'email' => 'printingservices@opc.gov.mw',
        'email_href' => 'mailto:printingservices@opc.gov.mw',
        'hours' => 'Monday to Friday',
        'po_boxes' => [
            'P.O. Box 216, Lilongwe',
            'Private Bag 37, Zomba',
        ],
        'locations' => [
            'Along Chilambura Road, opposite Chipiku Stores, Lilongwe.',
            'Opposite MANEB Offices, Zomba.',
        ],
        'quote_checklist' => [
            'What you\'re printing (product type or document name)',
            'Size, quantity, and finish preference',
            'Whether you have artwork ready or need design help',
            'When you need it completed and delivery method',
        ],
    ],

    'footer' => [
        'summary' => 'For over 130 years, Government Press has been Malawi\'s trusted partner for professional, reliable printing services. Whether you\'re a government ministry, nonprofit organization, or private business, we\'re here to help your next printing project succeed.',
        'links' => [
            ['label' => 'Home', 'route' => 'home'],
            ['label' => 'About Us', 'route' => 'about'],
            ['label' => 'Team', 'route' => 'teams'],
            ['label' => 'Services', 'route' => 'services'],
            ['label' => 'Products', 'route' => 'products'],
            ['label' => 'Training', 'route' => 'training'],
            ['label' => 'Contact', 'route' => 'contact'],
        ],
    ],
];
