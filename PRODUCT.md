# Product

<!-- impeccable:product-schema 1 -->

## Platform

web

## Users

Viajeros mixtos, en proporción similar entre internacionales (ruta altiplánica Arica–Putre–Bolivia, mochileros y turismo de naturaleza) y nacionales chilenos buscando altiplano/naturaleza. Llegan buscando información sobre las cabañas, la cultura y cosmovisión aymara del territorio, y el entorno de Putre, antes de decidir si solicitan una estadía.

## Product Purpose

Sitio informativo con CMS para "Wiñaypacha Putre": difunde la cultura, cosmovisión y territorio del pueblo aymara y presenta cabañas para alojarse en Putre (Región de Arica y Parinacota). No es una plataforma de reservas en tiempo real: "Reservas" es un formulario de solicitud (lead) que el anfitrión confirma manualmente.

## Positioning

No compite como plataforma de booking anónima. Su mecanismo distintivo es la curaduría personal del anfitrión: cada solicitud de estadía es revisada y confirmada por una persona, no por disponibilidad automática. Ese contacto directo se comunica como sello de hospitalidad y cercanía, no como limitación técnica. El contenido cultural (cosmovisión, fiestas, entorno) es tan central como la oferta de alojamiento — el sitio educa sobre el territorio aymara, no solo vende noches de cabaña.

## Operating Context

- Rol único `admin` (sin registro público) gestiona el CMS: temas, cabañas, equipamiento, entorno, especies, servicios locales, calendario de fiestas, página "Nosotros" y configuración global.
- Contenido editado a mano en ES/EN/FR vía `spatie/laravel-translatable`; DeepL API es solo asistente de traducción en el admin, nunca traducción automática pública sin revisión humana.
- Solicitudes de reserva (`SolicitudReserva`) y leads de contacto (`LeadContacto`) llegan al admin para seguimiento manual, sin cobro online ni disponibilidad automática.

## Capabilities and Constraints

- Fuera de alcance explícito: reservas en tiempo real, disponibilidad automática, cobro online, traducción automática pública sin revisión, gestión de dominio/hosting de producción (administrativo del cliente, no tarea de desarrollo).
- Secciones actuales del sitio público: Inicio, Cultura, Putre, Cabañas (listado y detalle), Entorno, Nosotros, Contacto, Reserva.
- Negocio de un solo anfitrión/operación (a diferencia del proyecto hermano `pindoor`, que es multi-tenant).

## Evidence on Hand

Todo el contenido actual (fotos de cabañas y entorno, textos, calendario de fiestas) es placeholder (`WinayPlaceholderSeeder`) mientras se define el contenido final real del negocio. El trabajo de diseño y contenido no debe fabricar testimonios, datos de fiestas, o fotos como si fueran reales — debe señalarlos como placeholder hasta que el cliente entregue material verídico.

## Product Principles

- El contenido cultural y territorial tiene el mismo peso que la oferta de alojamiento; no es decoración alrededor de un formulario de reserva.
- La curaduría manual del anfitrión es una decisión de posicionamiento (hospitalidad personal), no una carencia a disimular.
- Sobriedad visual: acentos de identidad aymara (wiphala, chakana, textiles) con moderación, nunca como fondo saturado.
- El multilenguaje (ES/EN/FR) es un compromiso de contenido real editado a mano, no una traducción automática de cara al público.
- Placeholder es un estado explícito y temporal, no una autorización para inventar evidencia.
