<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Agency;
use App\Models\User;
use App\Models\TripCategory;
use App\Models\Trip;
use App\Models\Program;
use App\Models\PricingGroup;
use App\Models\PricingInstallment;
use App\Models\Bus;
use App\Models\BusSeat;
use App\Models\Accommodation;
use App\Models\Room;
use App\Models\ItineraryDay;
use App\Models\ItineraryActivity;
use App\Models\Traveler;
use App\Models\TripBooking;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\Faq;
use App\Models\Service;
use App\Models\Setting;
use App\Models\NotificationTemplate;
use App\Models\NotificationRule;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        if (Agency::where('is_installed', true)->exists()) {
            return;
        }

        $agency = Agency::create([
            'name' => 'TOUR UP Travels',
            'admin_name' => 'Admin TOUR UP',
            'email' => 'admin@tourup.com',
            'phone' => '51999000000',
            'welcome_phrase' => 'Descubre el mundo con nosotros. Viajes escolares y grupales con experiencia.',
            'about' => 'TOUR UP es una agencia de viajes especializada en programas escolares y grupales. Con más de 10 años de experiencia, ofrecemos experiencias educativas y culturales inolvidables para estudiantes y grupos organizados.',
            'address' => 'Av. Principal 123, Lima, Perú',
            'ruc' => '20123456789',
            'active_theme' => 'ethos_earth',
            'is_installed' => true,
        ]);

        $admin = User::create([
            'name' => 'Admin TOUR UP',
            'email' => 'admin@tourup.com',
            'phone' => '51999000000',
            'password' => Hash::make('admin123'),
            'is_admin' => true,
        ]);

        $aventura = TripCategory::create(['name' => 'Aventura', 'slug' => 'aventura', 'description' => 'Viajes de aventura y deportes al aire libre', 'icon' => 'fa-mountain', 'is_active' => true]);
        $cultural = TripCategory::create(['name' => 'Cultural', 'slug' => 'cultural', 'description' => 'Viajes culturales y educativos', 'icon' => 'fa-landmark', 'is_active' => true]);
        $idiomas = TripCategory::create(['name' => 'Idiomas', 'slug' => 'idiomas', 'description' => 'Inmersión lingüística en el extranjero', 'icon' => 'fa-language', 'is_active' => true]);
        $naturaleza = TripCategory::create(['name' => 'Naturaleza', 'slug' => 'naturaleza', 'description' => 'Ecoturismo y contacto con la naturaleza', 'icon' => 'fa-leaf', 'is_active' => true]);

        $programaSelva = Program::create([
            'name' => 'Aventura en la Selva Amazónica',
            'slug' => 'aventura-selva-amazonica',
            'trip_category_id' => $aventura->id,
            'description' => 'Programa de 7 días explorando la selva amazónica peruana. Incluye caminatas, paseos en bote, avistamiento de fauna y convivencia con comunidades locales.',
            'duration_days' => 7,
            'age_range' => '14-18',
            'is_active' => true,
        ]);

        $programaMadrid = Program::create([
            'name' => 'Inmersión Cultural en Madrid',
            'slug' => 'inmersion-cultural-madrid',
            'trip_category_id' => $cultural->id,
            'description' => 'Programa de 10 días en Madrid visitando museos, monumentos históricos y participando en talleres culturales.',
            'duration_days' => 10,
            'age_range' => '12-17',
            'is_active' => true,
        ]);

        $viajeSelva = Trip::create([
            'reference' => 'TUP-2026-001',
            'name' => 'Aventura en la Selva Amazónica 2026',
            'slug' => 'aventura-selva-amazonica-2026',
            'trip_category_id' => $aventura->id,
            'destination' => 'Iquitos, Perú',
            'start_date' => '2026-08-15',
            'end_date' => '2026-08-21',
            'description' => 'Una semana inolvidable en la selva amazónica. Los estudiantes explorarán la biodiversidad de la Amazonía peruana, navegarán por el río Amazonas, visitarán comunidades ribereñas y participarán en talleres de conservación ambiental.',
            'image' => null,
            'status' => 'on_sale',
            'total_spots' => 30,
            'occupied_spots' => 0,
        ]);

        $viajeMadrid = Trip::create([
            'reference' => 'TUP-2026-002',
            'name' => 'Inmersión Cultural en Madrid 2026',
            'slug' => 'inmersion-cultural-madrid-2026',
            'trip_category_id' => $cultural->id,
            'destination' => 'Madrid, España',
            'start_date' => '2026-10-01',
            'end_date' => '2026-10-10',
            'description' => 'Diez días de inmersión cultural en la capital de España. Visitaremos el Museo del Prado, el Palacio Real, el Retiro, y realizaremos excursiones a Toledo y Segovia.',
            'image' => null,
            'status' => 'on_sale',
            'total_spots' => 25,
            'occupied_spots' => 0,
        ]);

        $grupoDefault = PricingGroup::create(['trip_id' => $viajeSelva->id, 'name' => 'Por defecto', 'is_active' => true]);
        PricingInstallment::create(['pricing_group_id' => $grupoDefault->id, 'name' => 'Reserva de plaza', 'due_date' => '2026-06-01', 'amount' => 150.00, 'order' => 1]);
        PricingInstallment::create(['pricing_group_id' => $grupoDefault->id, 'name' => 'Segundo pago', 'due_date' => '2026-07-01', 'amount' => 300.00, 'order' => 2]);
        PricingInstallment::create(['pricing_group_id' => $grupoDefault->id, 'name' => 'Pago final', 'due_date' => '2026-08-01', 'amount' => 250.00, 'order' => 3]);

        $grupoDefault2 = PricingGroup::create(['trip_id' => $viajeMadrid->id, 'name' => 'Por defecto', 'is_active' => true]);
        PricingInstallment::create(['pricing_group_id' => $grupoDefault2->id, 'name' => 'Reserva de plaza', 'due_date' => '2026-07-01', 'amount' => 200.00, 'order' => 1]);
        PricingInstallment::create(['pricing_group_id' => $grupoDefault2->id, 'name' => 'Segundo pago', 'due_date' => '2026-08-01', 'amount' => 400.00, 'order' => 2]);
        PricingInstallment::create(['pricing_group_id' => $grupoDefault2->id, 'name' => 'Pago final', 'due_date' => '2026-09-01', 'amount' => 350.00, 'order' => 3]);

        $bus = Bus::create([
            'trip_id' => $viajeSelva->id,
            'name' => 'Bus 1',
            'total_seats' => 40,
            'rows' => 10,
            'columns' => 4,
        ]);
        for ($row = 1; $row <= 10; $row++) {
            for ($col = 1; $col <= 4; $col++) {
                BusSeat::create(['bus_id' => $bus->id, 'seat_number' => "{$row}{$col}", 'row' => $row, 'column' => $col, 'is_occupied' => false]);
            }
        }

        $hotel = Accommodation::create([
            'trip_id' => $viajeSelva->id,
            'name' => 'Eco Lodge Amazonia',
            'stars' => 3,
            'location' => 'Iquitos, Perú',
            'image' => null,
            'link' => null,
        ]);
        foreach (['101', '102', '103', '201', '202', '203'] as $num) {
            Room::create(['accommodation_id' => $hotel->id, 'room_number' => $num, 'type' => 'Compartida', 'capacity' => 4]);
        }

        $dia1 = ItineraryDay::create(['trip_id' => $viajeSelva->id, 'day_number' => 1, 'date' => '2026-08-15', 'title' => 'Llegada a Iquitos']);
        ItineraryActivity::create(['itinerary_day_id' => $dia1->id, 'time' => '08:00', 'title' => 'Vuelo Lima - Iquitos', 'description' => 'Vuelo directo desde Lima hacia Iquitos', 'type' => 'transport', 'order' => 1]);
        ItineraryActivity::create(['itinerary_day_id' => $dia1->id, 'time' => '12:00', 'title' => 'Bienvenida y almuerzo', 'description' => 'Almuerzo de bienvenida en el Eco Lodge', 'type' => 'food', 'order' => 2]);
        ItineraryActivity::create(['itinerary_day_id' => $dia1->id, 'time' => '15:00', 'title' => 'Exploración del lodge', 'description' => 'Recorrido por las instalaciones y charla introductoria', 'type' => 'activity', 'order' => 3]);

        $dia2 = ItineraryDay::create(['trip_id' => $viajeSelva->id, 'day_number' => 2, 'date' => '2026-08-16', 'title' => 'Navegación por el Amazonas']);
        ItineraryActivity::create(['itinerary_day_id' => $dia2->id, 'time' => '07:00', 'title' => 'Desayuno', 'description' => 'Desayuno buffet en el lodge', 'type' => 'food', 'order' => 1]);
        ItineraryActivity::create(['itinerary_day_id' => $dia2->id, 'time' => '08:30', 'title' => 'Paseo en bote', 'description' => 'Navegación por el río Amazonas', 'type' => 'activity', 'order' => 2]);
        ItineraryActivity::create(['itinerary_day_id' => $dia2->id, 'time' => '12:00', 'title' => 'Picnic en la ribera', 'description' => 'Almuerzo tipo picnic en una playa del río', 'type' => 'food', 'important_notes' => 'Llevar repelente de insectos obligatorio', 'order' => 3]);
        ItineraryActivity::create(['itinerary_day_id' => $dia2->id, 'time' => '19:00', 'title' => 'Cena en el lodge', 'description' => 'Cena con show cultural', 'type' => 'food', 'order' => 4]);
        ItineraryActivity::create(['itinerary_day_id' => $dia2->id, 'time' => '21:00', 'title' => 'Retorno a habitaciones', 'description' => 'Regreso a las habitaciones para descansar', 'type' => 'accommodation', 'order' => 5]);

        $traveler = Traveler::create([
            'first_name' => 'Carlos',
            'last_name' => 'García Pérez',
            'dni' => '12345678',
            'birth_date' => '2008-05-12',
            'sex' => 'M',
            'email' => 'carlos.garcia@email.com',
            'phone' => '51988123456',
        ]);

        $traveler2 = Traveler::create([
            'first_name' => 'María',
            'last_name' => 'López Torres',
            'dni' => '87654321',
            'birth_date' => '2009-11-23',
            'sex' => 'F',
            'email' => 'maria.lopez@email.com',
            'phone' => '51988765432',
        ]);

        TripBooking::create([
            'trip_id' => $viajeSelva->id,
            'traveler_id' => $traveler->id,
            'payment_status' => 'pending',
            'booking_status' => 'confirmed',
            'document_status' => 'pending',
            'amount_paid' => 0,
        ]);

        $blogCategoria = BlogCategory::create(['name' => 'Consejos de Viaje', 'slug' => 'consejos-viaje', 'description' => 'Consejos útiles para viajeros', 'is_active' => true]);
        BlogCategory::create(['name' => 'Destinos', 'slug' => 'destinos', 'description' => 'Información sobre destinos', 'is_active' => true]);

        BlogPost::create([
            'blog_category_id' => $blogCategoria->id,
            'title' => 'Consejos para viajes escolares seguros',
            'slug' => 'consejos-viajes-escolares-seguros',
            'excerpt' => 'Todo lo que necesitas saber para organizar un viaje escolar seguro y exitoso.',
            'body' => '<p>Organizar un viaje escolar requiere una planificación cuidadosa. Aquí te compartimos los mejores consejos para garantizar una experiencia segura y educativa.</p><h3>1. Documentación en regla</h3><p>Asegúrate de que todos los estudiantes tengan su documentación al día: DNI, autorización de padres, ficha médica y seguro de viaje.</p><h3>2. Comunicación constante</h3><p>Mantén una comunicación fluida con los padres durante todo el viaje. Comparte el itinerario y establece canales de emergencia.</p><h3>3. Grupos reducidos</h3><p>Divide a los estudiantes en grupos pequeños con un responsable asignado para facilitar la supervisión.</p>',
            'image' => null,
            'author' => 'Admin TOUR UP',
            'status' => 'published',
            'published_at' => now(),
        ]);

        BlogPost::create([
            'blog_category_id' => $blogCategoria->id,
            'title' => 'Beneficios de los viajes educativos',
            'slug' => 'beneficios-viajes-educativos',
            'excerpt' => 'Descubre cómo los viajes educativos impactan positivamente en el desarrollo de los estudiantes.',
            'body' => '<p>Los viajes educativos son mucho más que una excursión. Representan una oportunidad única de aprendizaje experiencial que complementa la formación académica.</p><h3>Desarrollo personal</h3><p>Los estudiantes ganan independencia, confianza y habilidades sociales al interactuar en entornos nuevos.</p><h3>Aprendizaje vivencial</h3><p>La experiencia directa con otras culturas y entornos naturales refuerza los contenidos aprendidos en el aula.</p>',
            'image' => null,
            'author' => 'Admin TOUR UP',
            'status' => 'published',
            'published_at' => now(),
        ]);

        Faq::create(['question' => '¿Cómo puedo inscribir a mi hijo en un viaje?', 'answer' => 'Puedes inscribirlo directamente desde la ficha del viaje en nuestra web, o contactándonos por teléfono o email. Te guiaremos en todo el proceso.', 'order' => 1, 'is_active' => true]);
        Faq::create(['question' => '¿Qué documentación necesita mi hijo para viajar?', 'answer' => 'Los estudiantes necesitan: DNI o pasaporte vigente, autorización firmada por los padres, ficha médica actualizada y seguro de viaje.', 'order' => 2, 'is_active' => true]);
        Faq::create(['question' => '¿Cómo funcionan los planes de pago?', 'answer' => 'Ofrecemos planes de pago fraccionado para facilitar la inscripción. Puedes elegir entre diferentes grupos de tarifa según la edad del viajero.', 'order' => 3, 'is_active' => true]);

        Service::create(['name' => 'Viajes Escolares', 'slug' => 'viajes-escolares', 'description' => 'Programas educativos diseñados para colegios, con itinerarios pedagógicos y supervisión permanente.', 'icon' => 'fa-school', 'is_active' => true, 'order' => 1]);
        Service::create(['name' => 'Viajes Grupales', 'slug' => 'viajes-grupales', 'description' => 'Experiencias personalizadas para grupos organizados: clubs, asociaciones y empresas.', 'icon' => 'fa-users', 'is_active' => true, 'order' => 2]);

        Setting::set('hero_title', 'Descubre el Mundo');
        Setting::set('hero_subtitle', 'Viajes escolares y grupales con propósito educativo');
        Setting::set('about_intro', 'Somos una agencia comprometida con la educación y el desarrollo de los jóvenes a través de viajes transformadores.');
        Setting::set('cta_text', '¿Listo para tu próxima aventura?');
        Setting::set('cta_button', 'Explorar Viajes');
        Setting::set('footer_copyright', '© ' . date('Y') . ' TOUR UP Travels. Todos los derechos reservados.');
        Setting::set('blog_enabled', 'true');
        Setting::set('faq_enabled', 'true');
        Setting::set('booking_enabled', 'true');
        Setting::set('services_enabled', 'true');

        $recordatorio = NotificationTemplate::create([
            'name' => 'Recordatorio de pago',
            'slug' => 'payment_reminder',
            'subject' => 'Recordatorio de pago - {{viaje}}',
            'body' => 'Hola {{cliente}}, te recordamos que el pago de "{{cuota}}" vence el {{fecha_vencimiento}}. Monto: S/{{monto}}.',
            'channels' => '["email","whatsapp"]',
        ]);

        $documentos = NotificationTemplate::create([
            'name' => 'Solicitud de documentos',
            'slug' => 'document_request',
            'subject' => 'Documentos pendientes - {{viaje}}',
            'body' => 'Hola {{cliente}}, faltan documentos para tu viaje {{viaje}}: {{documentos_faltantes}}. Por favor entrégalos antes del {{fecha_limite}}.',
            'channels' => '["email"]',
        ]);

        $previo = NotificationTemplate::create([
            'name' => 'Información pre-viaje',
            'slug' => 'pre_trip_info',
            'subject' => 'Información importante - {{viaje}}',
            'body' => 'Hola {{cliente}}, tu viaje {{viaje}} comienza en {{dias_restantes}} días. Prepara tu equipaje y revisa las recomendaciones adjuntas.',
            'channels' => '["email","whatsapp"]',
        ]);

        $post = NotificationTemplate::create([
            'name' => 'Encuesta post-viaje',
            'slug' => 'post_trip_survey',
            'subject' => '¿Cómo fue tu experiencia? - {{viaje}}',
            'body' => 'Hola {{cliente}}, esperamos que hayas disfrutado tu viaje {{viaje}}. Cuéntanos tu experiencia respondiendo esta breve encuesta.',
            'channels' => '["email"]',
        ]);

        $followup = NotificationTemplate::create([
            'name' => 'Seguimiento lead',
            'slug' => 'lead_followup',
            'subject' => '¿Sigue interesado? - {{viaje}}',
            'body' => 'Hola {{cliente}}, vimos que te interesaste en {{viaje}}. ¿Te gustaría recibir más información o resolver alguna duda?',
            'channels' => '["email","whatsapp"]',
        ]);

        NotificationRule::create([
            'notification_template_id' => $recordatorio->id,
            'event' => 'payment_reminder',
            'trigger_condition' => '{"days_before_due": 7, "payment_status": "pending"}',
            'channel' => 'email',
            'is_active' => true,
        ]);

        NotificationRule::create([
            'notification_template_id' => $recordatorio->id,
            'event' => 'payment_reminder',
            'trigger_condition' => '{"days_before_due": 3, "payment_status": "pending"}',
            'channel' => 'whatsapp',
            'is_active' => true,
        ]);

        NotificationRule::create([
            'notification_template_id' => $documentos->id,
            'event' => 'document_request',
            'trigger_condition' => '{"days_before_trip": 30, "document_status": "pending"}',
            'channel' => 'email',
            'is_active' => true,
        ]);

        NotificationRule::create([
            'notification_template_id' => $previo->id,
            'event' => 'pre_trip_info',
            'trigger_condition' => '{"days_before_trip": 7}',
            'channel' => 'email',
            'is_active' => true,
        ]);

        NotificationRule::create([
            'notification_template_id' => $previo->id,
            'event' => 'pre_trip_info',
            'trigger_condition' => '{"days_before_trip": 1}',
            'channel' => 'whatsapp',
            'is_active' => true,
        ]);

        NotificationRule::create([
            'notification_template_id' => $post->id,
            'event' => 'post_trip_survey',
            'trigger_condition' => '{"days_after_trip": 1}',
            'channel' => 'email',
            'is_active' => true,
        ]);

        NotificationRule::create([
            'notification_template_id' => $followup->id,
            'event' => 'lead_followup',
            'trigger_condition' => '{"days_without_contact": 7}',
            'channel' => 'email',
            'is_active' => true,
        ]);
    }
}
