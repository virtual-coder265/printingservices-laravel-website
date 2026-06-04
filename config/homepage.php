<?php

return [
    'meta' => [
        'title' => 'Department of Printing Services | Government Press Malawi',
        'description' => 'Official public website for the Department of Printing Services, Government Press. Secure, professional, and timely printing services for Malawi since 1894.',
    ],

    'brand' => [
        'name' => 'Department of Printing Services',
        'short_name' => 'Government Press',
        'tagline' => 'Best Printers for the Nation',
        'logo' => 'images/brand/printing-services-logo.png',
    ],

    'utility' => [
        'notice' => 'Official public website of the Department of Printing Services under the Office of the President and Cabinet.',
        'phone' => '+265 (0) 995 881 711',
        'phone_href' => 'tel:+2650995881711',
        'email' => 'martin.nkhoma@opc.gov.mw',
        'email_href' => 'mailto:martin.nkhoma@opc.gov.mw',
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
        ['key' => 'contact', 'label' => 'Contact', 'route' => 'contact'],
    ],

    'navigation' => [
        [
            'key' => 'about',
            'label' => 'About Us',
            'href' => '#overview',
            'panel' => [
                'blurb' => 'A heritage institution supporting secure public-sector printing and dependable production for Malawi.',
                'groups' => [
                    [
                        'title' => 'Institution',
                        'links' => [
                            [
                                'label' => 'Heritage and mandate',
                                'href' => '#overview',
                                'description' => 'Learn how Government Press has served Malawi since 1894.',
                            ],
                            [
                                'label' => 'Mission and reach',
                                'href' => '#overview',
                                'description' => 'See how technology, professionalism, and service quality shape delivery.',
                            ],
                        ],
                    ],
                    [
                        'title' => 'Operations',
                        'links' => [
                            [
                                'label' => 'National footprint',
                                'href' => '#overview',
                                'description' => 'Headquartered in Lilongwe with major production in Zomba and reach into Mzuzu.',
                            ],
                            [
                                'label' => 'Training focus',
                                'href' => '#school',
                                'description' => 'Capacity building and skills development through the Printing Training School.',
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
                'blurb' => 'End-to-end services from design preparation and secure printing to finishing, binding, and distribution.',
                'groups' => [
                    [
                        'title' => 'Core service lines',
                        'links' => [
                            [
                                'label' => 'Secure printing',
                                'href' => '#services',
                                'description' => 'Controlled jobs requiring confidentiality, integrity, and dependable handling.',
                            ],
                            [
                                'label' => 'Publications and reports',
                                'href' => '#services',
                                'description' => 'Government reports, manuals, forms, gazettes, and institutional publications.',
                            ],
                            [
                                'label' => 'Finishing and binding',
                                'href' => '#services',
                                'description' => 'Professional finishing for print jobs that must be durable and presentation-ready.',
                            ],
                        ],
                    ],
                    [
                        'title' => 'Popular print products',
                        'links' => [
                            [
                                'label' => 'Certificates and brochures',
                                'href' => '#catalogue',
                                'description' => 'Premium printed items for institutions, programmes, and events.',
                            ],
                            [
                                'label' => 'Promotional materials',
                                'href' => '#catalogue',
                                'description' => 'Banners, posters, flyers, and cards for public communication and campaigns.',
                            ],
                            [
                                'label' => 'Request a quotation',
                                'href' => '#contact',
                                'description' => 'Share your job details with the front office and start the workflow.',
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
                'blurb' => 'Public information, current notices, and essential client guidance for working with the department.',
                'groups' => [
                    [
                        'title' => 'Public information',
                        'links' => [
                            [
                                'label' => 'Service charter',
                                'href' => '#charter',
                                'description' => 'Review service commitments and download the charter document.',
                            ],
                            [
                                'label' => 'Current updates',
                                'href' => '#resources',
                                'description' => 'Featured notices, quotation guidance, and service highlights.',
                            ],
                        ],
                    ],
                    [
                        'title' => 'Client support',
                        'links' => [
                            [
                                'label' => 'Quotation checklist',
                                'href' => '#contact',
                                'description' => 'Know what to include when requesting production support.',
                            ],
                            [
                                'label' => 'Office contacts',
                                'href' => '#contact',
                                'description' => 'Phone, email, and physical office details for enquiries.',
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
            'title' => 'State of the Art Printing Services',
            'description' => 'We provide quality, professional, and timely printing services for government institutions and private-sector clients across Malawi.',
            'image' => 'images/home/facility-floor.png',
            'primary_label' => 'Request Quotation',
            'primary_href' => '#contact',
            'secondary_label' => 'Explore Services',                                              
            'secondary_href' => '#services',
            'points' => [
                'Confidential handling for official documentation',
                'Professional production backed by experienced technical staff',
                'Service coverage anchored in Lilongwe, Zomba, and Mzuzu',
            ],
        ],
        [
            'eyebrow' => 'Production floor',
            'title' => 'State-of-the-art facilities built for dependable output',
            'description' => 'From pre-press preparation to finishing, our operational setup is structured to deliver quality at institutional scale.',
            'image' => 'images/home/facility-floor.png',
            'primary_label' => 'View Capabilities',
            'primary_href' => '#catalogue',
            'secondary_label' => 'See Our Process',
            'secondary_href' => '#charter',
            'points' => [
                'Large-format and document production under one public institution',
                'Quality control across proofing, print, finishing, and packaging',
                'A disciplined workflow designed for fast, clear approvals',
            ],
        ],
        [
            'eyebrow' => 'Secure printing',
            'title' => 'Confidential jobs managed with integrity from start to finish',
            'description' => 'Government Press remains a trusted backbone for sensitive printed materials where reliability, control, and traceability matter most.',
            'image' => 'images/home/manual-cover.webp',
            'primary_label' => 'View Service Standards',
            'primary_href' => '#charter',
            'secondary_label' => 'Contact Front Office',
            'secondary_href' => '#contact',
            'points' => [
                'Secure workflows for controlled and high-stakes print runs',
                'High-quality output for official records, reports, and certificates',
                'National credibility built on confidentiality and consistency',
            ],
        ],
        [
            'eyebrow' => 'Printing Training School',
            'title' => 'Capacity building for the next generation of print professionals',
            'description' => 'Our training focus supports practical learning in modern printing technologies, finishing techniques, and production discipline.',
            'image' => 'images/home/speech-cover.webp',
            'primary_label' => 'Explore The School',
            'primary_href' => '#school',
            'secondary_label' => 'Speak To The Team',
            'secondary_href' => '#contact',
            'points' => [
                'Hands-on exposure to production workflows and print preparation',
                'Skills development aligned to quality, accuracy, and discipline',
                'A core institutional pillar highlighted alongside public service delivery',
            ],
        ],
        [
            'eyebrow' => 'Turnaround and service',
            'title' => 'Professional print support for public institutions and private clients',
            'description' => 'We combine long-standing public service experience with competitive, professional-grade print delivery for a wide range of needs.',
            'image' => 'images/home/manual-cover.webp',
            'primary_label' => 'Browse Print Products',
            'primary_href' => '#catalogue',
            'secondary_label' => 'Get In Touch',
            'secondary_href' => '#contact',
            'points' => [
                'Commercial-basis delivery while retaining public-service discipline',
                'Solutions spanning reports, promotional materials, and branded outputs',
                'Trusted turnaround shaped by heritage, systems, and people',
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
        ['icon' => 'award', 'title' => 'Quality Workmanship', 'description' => 'We deliver excellence in every print job.'],
        ['icon' => 'clock', 'title' => 'Timely Delivery', 'description' => 'Your deadlines are our operational priority.'],
        ['icon' => 'users', 'title' => 'Experienced Team', 'description' => 'Skilled professionals committed to public service.'],
        ['icon' => 'smile', 'title' => 'Client Satisfaction', 'description' => 'We build lasting institutional relationships.'],
    ],

    'values' => [
        ['icon' => 'clock', 'title' => 'Reliable & Timely', 'description' => 'We deliver projects on time without compromising quality.'],
        ['icon' => 'users', 'title' => 'Skilled Workforce', 'description' => 'Our team brings experience, dedication and professionalism.'],
        ['icon' => 'shield-check', 'title' => 'Secure Handling', 'description' => 'Confidential jobs managed with integrity from start to finish.'],
        ['icon' => 'search', 'title' => 'Attentive to Detail', 'description' => 'We focus on every detail from proofing to finishing.'],
        ['icon' => 'handshake', 'title' => 'Public Trust', 'description' => 'Your satisfaction is at the heart of everything we do.'],
    ],

    'trust_metrics' => [
        ['icon' => 'check-circle', 'title' => 'Quality Materials', 'description' => 'We use dependable materials for long-lasting results.'],
        ['icon' => 'trending-up', 'title' => 'Modern Techniques', 'description' => 'We embrace innovation to deliver better outcomes.'],
        ['icon' => 'shield-check', 'title' => 'Safe & Sustainable', 'description' => 'We prioritize safety and environmental care.'],
        ['icon' => 'users', 'title' => 'Local & Trusted', 'description' => 'Proudly serving Malawi with integrity since 1894.'],
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

    'footer_services' => [
        ['icon' => 'shield-check', 'label' => 'Secure Printing', 'href' => '#services'],
        ['icon' => 'book-open', 'label' => 'Government Publications', 'href' => '#services'],
        ['icon' => 'printer', 'label' => 'General Print Production', 'href' => '#services'],
        ['icon' => 'layers', 'label' => 'Finishing & Binding', 'href' => '#services'],
        ['icon' => 'graduation-cap', 'label' => 'Printing Training School', 'href' => '#school'],
    ],

    'overview' => [
        'badge' => 'Since 1894',
        'eyebrow' => 'Government Press, Malawi',
        'title' => 'Trusted printing infrastructure for public communication and official production',
        'lead' => 'The Department of Printing Services, known as Government Press, is one of Malawi\'s oldest and most vital public service institutions.',
        'body' => [
            'Established in 1894, the department was formed to handle official government printing and remains a critical asset in the production of high-quality public documents.',
            'In 1970, its role was broadened when the department was re-designated to operate as a Treasury Fund under Section 17 of the Finance and Audit Act and Section 37:01 of the Laws of Malawi.',
            'Today, DPS operates on a commercial basis, offering professional printing services to both government departments and private-sector clients while sustaining strict standards of confidentiality, reliability, and service quality.',
        ],
        'mission' => 'To be leaders in the provision of quality printing services through the use of modern technology and professional and competent staff in order to contribute to the sustainable socio-economic development of the country.',
        'points' => [
            'Secure handling for official and sensitive public documents',
            'Front-office coordination anchored in Lilongwe',
            'Scaled production capacity through the largest works in Zomba',
            'Additional operational reach extending into Mzuzu',
        ],
        'cta_label' => 'Explore Services',
        'cta_href' => '#services',
        'cards' => [
            [
                'title' => 'Head Office',
                'description' => 'Headquartered in Lilongwe for front-office support, coordination, and client access.',
            ],
            [
                'title' => 'Largest Works',
                'description' => 'The department maintains its largest printing works in Zomba for scaled production.',
            ],
            [
                'title' => 'Nationwide Access',
                'description' => 'Additional sub-printing offices extend the service footprint into Lilongwe and Mzuzu.',
            ],
            [
                'title' => 'Public Trust',
                'description' => 'Official documentation benefits from disciplined handling, confidentiality, and operational integrity.',
            ],
        ],
        'image_primary' => 'images/home/facility-floor.png',
        'image_secondary' => 'images/home/manual-cover.webp',
    ],

    'services' => [
        [
            'icon' => 'shield',
            'category' => 'Controlled production',
            'title' => 'Secure printing',
            'description' => 'Confidential print jobs are managed with disciplined workflows, controlled handling, and dependable quality throughout production.',
            'points' => [
                'Sensitive documents and controlled circulation materials',
                'Integrity-focused handling from brief to dispatch',
            ],
        ],
        [
            'icon' => 'publication',
            'category' => 'Public information',
            'title' => 'Government publications and reports',
            'description' => 'Produce reports, manuals, official forms, gazettes, and institutional publications with clear hierarchy and durable finishing.',
            'points' => [
                'Readable layouts for policy, reporting, and public communication',
                'Consistent output for recurring departmental publications',
            ],
        ],
        [
            'icon' => 'press',
            'category' => 'Commercial jobs',
            'title' => 'General print production',
            'description' => 'Support a broad catalogue of public and private print jobs ranging from brochures and flyers to branded collateral and event materials.',
            'points' => [
                'Short-run and repeat production support',
                'Suitable for institutions, programmes, and campaigns',
            ],
        ],
        [
            'icon' => 'design',
            'category' => 'Pre-press support',
            'title' => 'Artwork preparation and proofing',
            'description' => 'Refine layouts, prepare artwork, and confirm print-ready details before production begins to reduce errors and delays.',
            'points' => [
                'Proof review before print commitment',
                'Support for sizing, pagination, and production readiness',
            ],
        ],
        [
            'icon' => 'finish',
            'category' => 'Presentation quality',
            'title' => 'Finishing, binding, and packaging',
            'description' => 'Complete the production chain with binding, trimming, finishing, and packaging that keeps final output presentation-ready.',
            'points' => [
                'Durable finishes for manuals, certificates, and reports',
                'Packaging support for orderly handover and distribution',
            ],
        ],
        [
            'icon' => 'school',
            'category' => 'Skills development',
            'title' => 'Printing Training School',
            'description' => 'Promote practical learning in printing technology, finishing techniques, and production discipline for future professionals.',
            'points' => [
                'Training rooted in real production workflows',
                'Capacity building aligned to modern print operations',
            ],
        ],
    ],

    'catalogue' => [
        'eyebrow' => 'Print catalogue',
        'title' => 'Popular print products and institutional outputs',
        'lead' => 'The existing catalogue already points to strong demand for promotional materials, institutional stationery, reports, and premium document products.',
        'products' => [
            ['name' => 'Banners (Vinyl)', 'type' => 'Display and promotion', 'note' => 'High-visibility event and campaign materials'],
            ['name' => 'Brochures (Tri-Fold)', 'type' => 'Printed materials', 'note' => 'Compact information packs for programmes and services'],
            ['name' => 'Business Cards', 'type' => 'Corporate stationery', 'note' => 'Professional identity materials for offices and teams'],
            ['name' => 'Calendars', 'type' => 'Branded materials', 'note' => 'Useful annual communication products for institutions'],
            ['name' => 'College Certificates', 'type' => 'Security-oriented output', 'note' => 'Formal documents where finish and credibility matter'],
            ['name' => 'Flyers (A5)', 'type' => 'Campaign support', 'note' => 'Fast communication pieces for outreach and events'],
            ['name' => 'Posters (A2)', 'type' => 'Public messaging', 'note' => 'Large-format awareness and promotional display items'],
        ],
        'image_primary' => 'images/home/manual-cover.webp',
        'image_secondary' => 'images/home/speech-cover.webp',
    ],

    'process' => [
        'eyebrow' => 'Service flow',
        'title' => 'A clear workflow for briefing, approval, production, and delivery',
        'lead' => 'The public homepage can already set the right expectations for how jobs move through the department, while deeper workflow controls can later be managed in the CMS or client portal.',
        'steps' => [
            [
                'icon' => 'brief',
                'label' => '01',
                'title' => 'Receive the brief',
                'description' => 'Capture quantity, size, artwork status, finishing requirements, and delivery expectations at the front office.',
            ],
            [
                'icon' => 'proof',
                'label' => '02',
                'title' => 'Confirm specifications',
                'description' => 'Review proofs, align on material and presentation needs, and confirm readiness before production starts.',
            ],
            [
                'icon' => 'shield',
                'label' => '03',
                'title' => 'Produce with control',
                'description' => 'Move the job through secure, quality-led production processes supported by experienced technical teams.',
            ],
            [
                'icon' => 'delivery',
                'label' => '04',
                'title' => 'Finish and hand over',
                'description' => 'Apply finishing, packaging, and coordinated delivery or collection arrangements for the completed job.',
            ],
        ],
    ],

    'school' => [
        'eyebrow' => 'Training School',
        'title' => 'A training pillar that strengthens the future of print production',
        'lead' => 'The earlier website highlighted the Printing Training School as a core public-facing offer, and it deserves a first-class position in the redesign.',
        'body' => [
            'We believe in continuous capacity building, and staff regularly undergo training in the latest printing technologies, finishing techniques, and design systems.',
            'This same culture of learning can be reflected in a public-facing school section that introduces practical, production-informed training for aspiring professionals and institutional partners.',
        ],
        'focus_areas' => [
            'Printing technology fundamentals',
            'Pre-press and layout discipline',
            'Finishing techniques and quality control',
            'Production workflow and operational standards',
        ],
        'cta_label' => 'Register Interest',
        'cta_href' => '#contact',
        'image' => 'images/home/speech-cover.webp',
    ],

    'updates' => [
        [
            'tag' => 'Featured',
            'title' => 'Service Charter available for download',
            'excerpt' => 'Make the department\'s service commitments visible from the homepage and provide a direct route to the current charter document.',
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
            'excerpt' => 'The redesigned homepage gives the school a strong editorial position instead of burying it in a legacy menu structure.',
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

    'contact' => [
        'eyebrow' => 'Contact and quotation',
        'title' => 'Start your next print job with the right brief',
        'lead' => 'While the full CMS-driven request flow is still to come, the homepage can already guide visitors with the key information needed to begin.',
        'phone' => '+265 (0) 995 881 711',
        'phone_href' => 'tel:+2650995881711',
        'email' => 'martin.nkhoma@opc.gov.mw',
        'email_href' => 'mailto:martin.nkhoma@opc.gov.mw',
        'hours' => 'Monday to Friday',
        'locations' => [
            'Along Chilambura Road, opposite Chipiku Stores, Lilongwe.',
            'Opposite MANEB Offices, Zomba.',
        ],
        'quote_checklist' => [
            'Product type or document name',
            'Required size, quantity, and preferred finish',
            'Artwork status or whether design support is needed',
            'Required completion date and collection or delivery details',
        ],
    ],

    'footer' => [
        'summary' => 'Government Press remains a trusted public institution for secure, professional, and timely printing services in Malawi.',
        'links' => [
            ['label' => 'About', 'href' => '#overview'],
            ['label' => 'Services', 'href' => '#services'],
            ['label' => 'Catalogue', 'href' => '#catalogue'],
            ['label' => 'Training School', 'href' => '#school'],
            ['label' => 'Contact', 'href' => '#contact'],
        ],
    ],
];
