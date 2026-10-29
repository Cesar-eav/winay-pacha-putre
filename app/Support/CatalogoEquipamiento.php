<?php

namespace App\Support;

use App\Models\Equipamiento;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class CatalogoEquipamiento
{
    public const CATEGORIAS = [
        'cocina' => ['Cocina y comedor', 'Kitchen & dining', 'Cuisine et salle à manger'],
        'dormitorio' => ['Dormitorio', 'Bedroom', 'Chambre'],
        'bano' => ['Baño', 'Bathroom', 'Salle de bain'],
        'calefaccion' => ['Calefacción y clima', 'Heating & climate', 'Chauffage et climat'],
        'living' => ['Living y entretenimiento', 'Living room & entertainment', 'Salon et loisirs'],
        'conectividad' => ['Conectividad y trabajo', 'Connectivity & work', 'Connexion et travail'],
        'exterior' => ['Exterior', 'Outdoors', 'Extérieur'],
        'servicios' => ['Servicios y seguridad', 'Services & safety', 'Services et sécurité'],
        'otros' => ['Otros', 'Other', 'Autres'],
    ];

    /**
     * clave => [categoría, ícono, ES, EN, FR, sinónimos para el buscador del admin].
     * La clave identifica el ítem: no cambiarla una vez publicada.
     */
    public const ITEMS = [
        'cocina-equipada' => ['cocina', 'cocina', 'Cocina equipada', 'Equipped kitchen', 'Cuisine équipée', 'cocinar'],
        'cocina-gas' => ['cocina', 'cocina', 'Cocina a gas', 'Gas stove', 'Cuisinière à gaz', 'anafe hornalla quemador'],
        'horno' => ['cocina', 'chef', 'Horno', 'Oven', 'Four', 'hornear'],
        'microondas' => ['cocina', 'microondas', 'Microondas', 'Microwave', 'Micro-ondes', ''],
        'refrigerador' => ['cocina', 'frigobar', 'Refrigerador', 'Refrigerator', 'Réfrigérateur', 'refri nevera heladera'],
        'frigobar' => ['cocina', 'frigobar', 'Frigobar', 'Mini fridge', 'Mini-réfrigérateur', 'minibar refri nevera'],
        'hervidor' => ['cocina', 'hervidor', 'Hervidor', 'Electric kettle', 'Bouilloire électrique', 'tetera agua'],
        'cafetera' => ['cocina', 'hervidor', 'Cafetera', 'Coffee maker', 'Cafetière', 'cafe'],
        'tostador' => ['cocina', 'pan', 'Tostador', 'Toaster', 'Grille-pain', 'tostadora pan'],
        'termo' => ['cocina', 'taza', 'Termo', 'Thermos', 'Thermos', 'agua caliente'],
        'utensilios' => ['cocina', 'cubiertos', 'Utensilios de cocina', 'Cooking utensils', 'Ustensiles de cuisine', 'ollas sartenes'],
        'vajilla' => ['cocina', 'vajilla', 'Vajilla', 'Dishes and cutlery', 'Vaisselle', 'platos tazas cubiertos vasos'],
        'condimentos' => ['cocina', 'chef', 'Aceite, sal y condimentos', 'Oil, salt and spices', 'Huile, sel et épices', 'especias azucar'],
        'comedor' => ['cocina', 'comedor', 'Comedor', 'Dining area', 'Coin repas', 'mesa'],
        'lavaplatos' => ['cocina', 'lavaplatos', 'Lavaplatos', 'Kitchen sink', 'Évier', 'lavaloza fregadero'],
        'te-cafe' => ['cocina', 'taza', 'Té y café de cortesía', 'Complimentary tea and coffee', 'Thé et café offerts', 'infusiones'],
        'mate-coca' => ['cocina', 'hoja', 'Mate de coca', 'Coca tea', 'Thé de coca', 'te hojas altura'],
        'agua-purificada' => ['cocina', 'agua-potable', 'Agua purificada', 'Purified water', 'Eau purifiée', 'bidon botella beber potable'],

        'cama-matrimonial' => ['dormitorio', 'cama', 'Cama matrimonial', 'Double bed', 'Lit double', 'dos plazas 2 plazas'],
        'cama-king' => ['dormitorio', 'cama', 'Cama king', 'King-size bed', 'Lit king-size', 'grande'],
        'cama-plaza-media' => ['dormitorio', 'cama-simple', 'Cama de 1,5 plazas', 'Three-quarter bed', 'Lit 120 cm', 'plaza y media'],
        'cama-individual' => ['dormitorio', 'cama-simple', 'Cama de una plaza', 'Single bed', 'Lit simple', 'individual 1 plaza'],
        'cama-nido' => ['dormitorio', 'cama-simple', 'Cama nido', 'Trundle bed', 'Lit gigogne', 'adicional'],
        'camarote' => ['dormitorio', 'camarote', 'Camarote', 'Bunk bed', 'Lits superposés', 'litera'],
        'cama-adicional' => ['dormitorio', 'cama-simple', 'Cama adicional', 'Extra bed', 'Lit d\'appoint', 'supletoria'],
        'cuna' => ['dormitorio', 'cuna', 'Cuna', 'Baby cot', 'Lit bébé', 'bebe guagua'],
        'ropa-cama' => ['dormitorio', 'ropa-cama', 'Ropa de cama', 'Bed linen', 'Linge de lit', 'sabanas'],
        'frazadas-extra' => ['dormitorio', 'ropa-cama', 'Frazadas extra', 'Extra blankets', 'Couvertures supplémentaires', 'mantas cobijas abrigo'],
        'plumon' => ['dormitorio', 'ropa-cama', 'Plumón', 'Duvet', 'Couette', 'edredon cubrecama'],
        'almohadas-extra' => ['dormitorio', 'ropa-cama', 'Almohadas extra', 'Extra pillows', 'Oreillers supplémentaires', 'cojines'],
        'velador' => ['dormitorio', 'velador', 'Velador', 'Nightstand', 'Table de chevet', 'mesa de noche mesita'],
        'lampara-velador' => ['dormitorio', 'velador', 'Lámpara de velador', 'Bedside lamp', 'Lampe de chevet', 'luz'],
        'ropero' => ['dormitorio', 'ropero', 'Ropero', 'Wardrobe', 'Armoire', 'closet guardarropa'],
        'mueble-ropa' => ['dormitorio', 'ropero', 'Mueble para ropa', 'Clothes storage', 'Rangement pour vêtements', 'cajonera comoda'],
        'colgadores' => ['dormitorio', 'ropero', 'Colgadores', 'Clothes hangers', 'Cintres', 'perchas ganchos'],
        'cortinas-blackout' => ['dormitorio', 'cortinas', 'Cortinas blackout', 'Blackout curtains', 'Rideaux occultants', 'oscuras persianas'],

        'agua-caliente' => ['bano', 'agua-caliente', 'Agua caliente', 'Hot water', 'Eau chaude', 'calefont termo'],
        'ducha' => ['bano', 'agua-caliente', 'Ducha', 'Shower', 'Douche', ''],
        'tina' => ['bano', 'tina', 'Tina', 'Bathtub', 'Baignoire', 'bañera'],
        'toallas' => ['bano', 'ropa-cama', 'Toallas', 'Towels', 'Serviettes de toilette', ''],
        'secador-pelo' => ['bano', 'secador', 'Secador de pelo', 'Hair dryer', 'Sèche-cheveux', 'pelo cabello'],
        'articulos-aseo' => ['bano', 'gota', 'Artículos de aseo', 'Toiletries', 'Articles de toilette', 'jabon shampoo champu'],
        'papel-higienico' => ['bano', 'bano', 'Papel higiénico', 'Toilet paper', 'Papier toilette', 'confort'],

        'estufa-lena' => ['calefaccion', 'lena', 'Estufa a leña', 'Wood-burning stove', 'Poêle à bois', 'chimenea fuego'],
        'lena-incluida' => ['calefaccion', 'lena', 'Leña incluida', 'Firewood included', 'Bois de chauffage inclus', ''],
        'calefactor-electrico' => ['calefaccion', 'calefactor', 'Calefactor eléctrico', 'Electric heater', 'Radiateur électrique', 'estufa electrica'],
        'estufa-gas' => ['calefaccion', 'estufa', 'Estufa a gas', 'Gas heater', 'Chauffage au gaz', 'calefactor'],
        'estufa-parafina' => ['calefaccion', 'estufa', 'Estufa a parafina', 'Kerosene heater', 'Poêle à pétrole', 'kerosene calefactor'],
        'calefaccion-central' => ['calefaccion', 'calefaccion', 'Calefacción central', 'Central heating', 'Chauffage central', 'radiadores losa radiante'],
        'guatero' => ['calefaccion', 'gota', 'Guatero', 'Hot-water bottle', 'Bouillotte', 'bolsa agua caliente'],
        'frazada-electrica' => ['calefaccion', 'electricidad', 'Frazada eléctrica', 'Electric blanket', 'Couverture chauffante', 'manta'],
        'ventilador' => ['calefaccion', 'ventilador', 'Ventilador', 'Fan', 'Ventilateur', 'calor'],
        'termopanel' => ['calefaccion', 'casa', 'Ventanas termopanel', 'Double-glazed windows', 'Fenêtres à double vitrage', 'aislacion'],

        'living' => ['living', 'sillon', 'Living', 'Living room', 'Salon', 'estar'],
        'sofa' => ['living', 'sofa', 'Sofá', 'Sofa', 'Canapé', 'sillon'],
        'sofa-cama' => ['living', 'sofa', 'Sofá cama', 'Sofa bed', 'Canapé-lit', 'futon'],
        'sillones' => ['living', 'sillon', 'Sillones', 'Armchairs', 'Fauteuils', 'butacas'],
        'tv' => ['living', 'tv', 'Televisor', 'TV', 'Télévision', 'tele television'],
        'tv-cable' => ['living', 'tv', 'TV cable', 'Cable TV', 'Télévision par câble', 'satelital canales'],
        'smart-tv' => ['living', 'streaming', 'Smart TV con streaming', 'Smart TV with streaming', 'Smart TV avec streaming', 'netflix youtube'],
        'radio' => ['living', 'radio', 'Radio', 'Radio', 'Radio', ''],
        'parlante' => ['living', 'parlante', 'Parlante Bluetooth', 'Bluetooth speaker', 'Enceinte Bluetooth', 'musica'],
        'libros' => ['living', 'libros', 'Libros', 'Books', 'Livres', 'biblioteca lectura'],
        'juegos-mesa' => ['living', 'juegos', 'Juegos de mesa', 'Board games', 'Jeux de société', 'naipes cartas'],
        'juegos-ninos' => ['living', 'puzzle', 'Juegos para niños', 'Children\'s games', 'Jeux pour enfants', 'juguetes'],

        'wifi' => ['conectividad', 'wifi', 'Wifi', 'Wi-Fi', 'Wi-Fi', 'internet'],
        'senal-celular' => ['conectividad', 'senal', 'Señal de celular', 'Mobile signal', 'Réseau mobile', 'cobertura telefono'],
        'escritorio' => ['conectividad', 'escritorio', 'Escritorio para trabajar', 'Work desk', 'Bureau', 'teletrabajo'],
        'enchufes-cama' => ['conectividad', 'enchufe', 'Enchufes junto a la cama', 'Outlets by the bed', 'Prises près du lit', ''],
        'cargador-usb' => ['conectividad', 'cargador', 'Cargador USB', 'USB charger', 'Chargeur USB', 'celular'],
        'adaptador' => ['conectividad', 'enchufe', 'Adaptador de enchufe', 'Plug adapter', 'Adaptateur de prise', ''],
        'telefono' => ['conectividad', 'telefono', 'Teléfono', 'Telephone', 'Téléphone', ''],
        'respaldo-electrico' => ['conectividad', 'electricidad', 'Respaldo eléctrico', 'Backup power', 'Alimentation de secours', 'generador corte luz'],

        'estacionamiento' => ['exterior', 'estacionamiento', 'Estacionamiento', 'Parking', 'Parking', 'auto'],
        'estacionamiento-techado' => ['exterior', 'parking', 'Estacionamiento techado', 'Covered parking', 'Parking couvert', 'auto'],
        'terraza' => ['exterior', 'sol', 'Terraza', 'Terrace', 'Terrasse', 'balcon deck'],
        'jardin' => ['exterior', 'jardin', 'Jardín', 'Garden', 'Jardin', 'patio'],
        'huerto' => ['exterior', 'brote', 'Huerto', 'Vegetable garden', 'Potager', 'huerta'],
        'quincho' => ['exterior', 'parrilla', 'Quincho', 'Barbecue area', 'Espace barbecue', 'asado'],
        'parrilla' => ['exterior', 'parrilla', 'Parrilla', 'Grill', 'Barbecue', 'asado'],
        'fogon' => ['exterior', 'lena', 'Fogón', 'Fire pit', 'Foyer extérieur', 'fogata'],
        'mesa-exterior' => ['exterior', 'mesa', 'Mesa exterior', 'Outdoor table', 'Table extérieure', ''],
        'hamaca' => ['exterior', 'arboles', 'Hamaca', 'Hammock', 'Hamac', ''],
        'vista-volcanes' => ['exterior', 'volcan', 'Vista a los volcanes', 'Volcano view', 'Vue sur les volcans', 'nevados payachatas taapaca'],
        'vista-cerros' => ['exterior', 'cerro', 'Vista a los cerros', 'Mountain view', 'Vue sur les montagnes', 'montaña'],
        'cielo-estrellas' => ['exterior', 'telescopio', 'Cielo para observar estrellas', 'Stargazing sky', 'Ciel pour observer les étoiles', 'astronomia noche'],
        'binoculares' => ['exterior', 'binoculares', 'Binoculares', 'Binoculars', 'Jumelles', 'aves pajaros'],
        'bicicletas' => ['exterior', 'bicicleta', 'Bicicletas', 'Bicycles', 'Vélos', 'bici'],
        'tendedero' => ['exterior', 'ropero', 'Tendedero', 'Clothesline', 'Étendoir', 'colgar ropa'],

        'desayuno' => ['servicios', 'desayuno', 'Desayuno incluido', 'Breakfast included', 'Petit-déjeuner inclus', ''],
        'limpieza' => ['servicios', 'limpieza', 'Limpieza diaria', 'Daily cleaning', 'Ménage quotidien', 'aseo'],
        'lavanderia' => ['servicios', 'lavadora', 'Lavandería', 'Laundry service', 'Blanchisserie', 'lavado ropa'],
        'lavadora' => ['servicios', 'lavadora', 'Lavadora', 'Washing machine', 'Lave-linge', ''],
        'plancha' => ['servicios', 'ropero', 'Plancha', 'Iron', 'Fer à repasser', 'planchar'],
        'mascotas' => ['servicios', 'mascotas', 'Se admiten mascotas', 'Pets allowed', 'Animaux acceptés', 'perro gato pet friendly'],
        'no-fumar' => ['servicios', 'no-fumar', 'Prohibido fumar', 'Non-smoking', 'Non-fumeur', 'humo cigarro'],
        'accesible' => ['servicios', 'accesible', 'Acceso para silla de ruedas', 'Wheelchair accessible', 'Accessible en fauteuil roulant', 'movilidad reducida discapacidad'],
        'entrada-independiente' => ['servicios', 'puerta', 'Entrada independiente', 'Private entrance', 'Entrée indépendante', 'llave propia'],
        'llegada-flexible' => ['servicios', 'reloj', 'Horario de llegada flexible', 'Flexible check-in', 'Arrivée flexible', 'check-in hora'],
        'anfitrion' => ['servicios', 'campana', 'Anfitrión en el lugar', 'Host on site', 'Hôte sur place', 'atencion recepcion'],
        'info-turistica' => ['servicios', 'mapa', 'Información turística', 'Tourist information', 'Informations touristiques', 'tours excursiones'],
        'traslado' => ['servicios', 'traslado', 'Traslado desde Arica', 'Transfer from Arica', 'Transfert depuis Arica', 'transfer bus'],
        'botiquin' => ['servicios', 'botiquin', 'Botiquín', 'First-aid kit', 'Trousse de secours', 'primeros auxilios'],
        'oxigeno' => ['servicios', 'oxigeno', 'Oxígeno para la altura', 'Oxygen for altitude sickness', 'Oxygène contre le mal d\'altitude', 'puna soroche apunamiento'],
        'extintor' => ['servicios', 'extintor', 'Extintor', 'Fire extinguisher', 'Extincteur', 'incendio'],
        'detector-humo' => ['servicios', 'humo', 'Detector de humo', 'Smoke detector', 'Détecteur de fumée', 'alarma'],
        'caja-fuerte' => ['servicios', 'caja-fuerte', 'Caja fuerte', 'Safe', 'Coffre-fort', ''],
    ];

    /**
     * Para equipamiento agregado a mano: palabra (ASCII, minúsculas) => ícono. Coincide con la palabra
     * completa o su plural; con * al final, con cualquier palabra que empiece así.
     * El orden importa: gana la primera que aparezca en el nombre.
     */
    public const PALABRAS = [
        'ropa de cama' => 'ropa-cama', 'agua caliente' => 'agua-caliente', 'aire acondicionado' => 'frio',
        'caja fuerte' => 'caja-fuerte', 'silla de ruedas' => 'accesible', 'primeros auxilios' => 'botiquin',
        'sabana' => 'ropa-cama', 'toalla' => 'ropa-cama', 'frazada' => 'ropa-cama', 'manta' => 'ropa-cama',
        'plumon' => 'ropa-cama', 'almohada' => 'ropa-cama', 'cobija' => 'ropa-cama', 'edredon' => 'ropa-cama',
        'cuna' => 'cuna', 'bebe' => 'cuna', 'guagua' => 'cuna',
        'camarote' => 'camarote', 'litera' => 'camarote', 'cama' => 'cama',
        'wifi' => 'wifi', 'internet' => 'wifi', 'router' => 'router', 'senal' => 'senal', 'cobertura' => 'senal',
        'lena' => 'lena', 'fogon' => 'lena', 'fogata' => 'lena', 'chimenea' => 'lena',
        'estufa' => 'estufa', 'gas' => 'estufa', 'parafina' => 'estufa',
        'calefactor' => 'calefactor', 'calefacc*' => 'calefaccion', 'termometro' => 'calefaccion',
        'ventilador' => 'ventilador', 'congelador' => 'frio',
        'ducha' => 'agua-caliente', 'calefont' => 'agua-caliente',
        'tina' => 'tina', 'jacuzzi' => 'tina', 'banera' => 'tina',
        'secador' => 'secador', 'papel' => 'bano', 'wc' => 'bano', 'jabon' => 'gota', 'shampoo' => 'gota',
        'agua' => 'agua-potable', 'vaso' => 'agua-potable',
        'estacionamiento' => 'estacionamiento', 'parking' => 'estacionamiento', 'auto' => 'estacionamiento',
        'velador' => 'velador', 'lampara' => 'velador', 'luz' => 'velador',
        'tv' => 'tv', 'televis*' => 'tv', 'tele' => 'tv', 'netflix' => 'streaming', 'streaming' => 'streaming',
        'chromecast' => 'streaming', 'proyector' => 'proyector', 'pelicula' => 'pelicula', 'dvd' => 'pelicula',
        'frigobar' => 'frigobar', 'minibar' => 'frigobar', 'refri*' => 'frigobar', 'nevera' => 'frigobar',
        'microondas' => 'microondas', 'horno' => 'chef',
        'hervidor' => 'hervidor', 'tetera' => 'hervidor', 'cafe' => 'hervidor', 'cafetera' => 'hervidor', 'capsula' => 'hervidor',
        'te' => 'taza', 'taza' => 'taza', 'termo' => 'taza', 'mate' => 'hoja', 'coca' => 'hoja', 'hierba' => 'hoja',
        'tostador' => 'pan', 'pan' => 'pan', 'desayuno' => 'desayuno', 'huevo' => 'desayuno',
        'sopa' => 'sopa', 'olla' => 'cocina', 'cocina' => 'cocina', 'chef' => 'chef', 'condiment*' => 'chef',
        'parrilla' => 'parrilla', 'asado' => 'parrilla', 'quincho' => 'parrilla',
        'vino' => 'vino', 'cerveza' => 'cerveza', 'leche' => 'leche',
        'comedor' => 'comedor', 'cubierto' => 'cubiertos', 'utensilio' => 'cubiertos', 'plato' => 'vajilla', 'vajilla' => 'vajilla',
        'mesa' => 'mesa', 'lavaplatos' => 'lavaplatos', 'lavaloza' => 'lavaplatos',
        'sofa' => 'sofa', 'sillon' => 'sillon', 'silla' => 'sillon', 'living' => 'sillon',
        'ropero' => 'ropero', 'mueble' => 'ropero', 'closet' => 'ropero', 'colgador' => 'ropero', 'percha' => 'ropero',
        'plancha' => 'ropero', 'ropa' => 'ropero', 'tendedero' => 'ropero',
        'cortina' => 'cortinas', 'persiana' => 'cortinas',
        'lavadora' => 'lavadora', 'lavanderia' => 'lavadora', 'secadora' => 'lavadora', 'limpieza' => 'limpieza', 'aseo' => 'limpieza',
        'basura' => 'basura', 'reciclaje' => 'basura',
        'radio' => 'radio', 'parlante' => 'parlante', 'musica' => 'musica', 'guitarra' => 'guitarra', 'instrumento' => 'guitarra',
        'libro' => 'libros', 'biblioteca' => 'libros', 'revista' => 'libros',
        'juego' => 'juegos', 'naipe' => 'juegos', 'cartas' => 'juegos', 'puzzle' => 'puzzle', 'rompecabeza' => 'puzzle',
        'juguete' => 'puzzle', 'consola' => 'consola',
        'enchufe' => 'enchufe', 'adaptador' => 'enchufe', 'cargador' => 'cargador', 'usb' => 'cargador', 'bateria' => 'cargador',
        'generador' => 'electricidad', 'electric*' => 'electricidad', 'solar' => 'sol',
        'celular' => 'celular', 'telefono' => 'telefono', 'computador' => 'notebook', 'notebook' => 'notebook',
        'escritorio' => 'escritorio', 'impresora' => 'impresora',
        'bicicleta' => 'bicicleta', 'bici' => 'bicicleta', 'volcan' => 'volcan', 'nevado' => 'volcan',
        'cerro' => 'cerro', 'montana' => 'cerro', 'vista' => 'cerro',
        'arbol' => 'arboles', 'hamaca' => 'arboles', 'bosque' => 'arboles', 'jardin' => 'jardin', 'flor' => 'jardin',
        'huert*' => 'brote', 'planta' => 'brote', 'carpa' => 'carpa', 'camping' => 'carpa', 'reja' => 'reja', 'cerco' => 'reja',
        'quitasol' => 'quitasol', 'paraguas' => 'quitasol', 'terraza' => 'sol', 'balcon' => 'sol', 'sol' => 'sol',
        'atardecer' => 'atardecer', 'estrella' => 'telescopio', 'telescopio' => 'telescopio', 'astronom*' => 'telescopio',
        'binocular' => 'binoculares', 'ave' => 'binoculares', 'mapa' => 'mapa', 'tour' => 'mapa', 'excursion' => 'mapa',
        'mochila' => 'mochila', 'trekking' => 'huellas', 'caminata' => 'huellas', 'sendero' => 'huellas', 'brujula' => 'brujula',
        'mascota' => 'mascotas', 'perro' => 'mascotas', 'gato' => 'mascotas',
        'fuma*' => 'no-fumar', 'accesib*' => 'accesible', 'rampa' => 'accesible',
        'reloj' => 'reloj', 'hora' => 'reloj', 'calendario' => 'calendario',
        'llave' => 'llave', 'candado' => 'candado', 'puerta' => 'puerta', 'entrada' => 'puerta',
        'seguridad' => 'seguridad', 'alarma' => 'humo', 'humo' => 'humo', 'extintor' => 'extintor',
        'botiquin' => 'botiquin', 'oxigeno' => 'oxigeno', 'medic*' => 'remedio', 'remedio' => 'remedio',
        'anfitrion' => 'campana', 'recepcion' => 'campana', 'traslado' => 'traslado',
        'transfer' => 'traslado', 'bus' => 'traslado', 'casa' => 'casa', 'ventana' => 'casa',
    ];

    public static function sugerirIcono(string $nombre): string
    {
        $nombre = Str::lower(Str::ascii($nombre));

        foreach (self::PALABRAS as $palabra => $icono) {
            $patron = str_ends_with($palabra, '*')
                ? '/\b'.preg_quote(rtrim($palabra, '*'), '/').'/'
                : '/\b'.preg_quote($palabra, '/').'(s|es)?\b/';

            if (preg_match($patron, $nombre)) {
                return $icono;
            }
        }

        return 'check';
    }

    public static function categoriaNombre(string $categoria, ?string $locale = null): string
    {
        $indice = ['es' => 0, 'en' => 1, 'fr' => 2][$locale ?? app()->getLocale()] ?? 0;

        return (self::CATEGORIAS[$categoria] ?? self::CATEGORIAS['otros'])[$indice];
    }

    /**
     * @param  Collection<int, Equipamiento>  $equipamientos
     * @return Collection<string, Collection<int, Equipamiento>>
     */
    public static function agrupar(Collection $equipamientos): Collection
    {
        $posicion = array_flip(array_keys(self::CATEGORIAS));

        return $equipamientos
            ->sortBy([
                fn ($a, $b) => ($posicion[$a->categoria] ?? 99) <=> ($posicion[$b->categoria] ?? 99),
                fn ($a, $b) => $a->orden <=> $b->orden,
            ])
            ->groupBy(fn ($e) => isset($posicion[$e->categoria]) ? $e->categoria : 'otros');
    }

    public static function textoBusqueda(Equipamiento $equipamiento): string
    {
        $sinonimos = self::ITEMS[$equipamiento->clave][5] ?? '';

        return Str::lower(Str::ascii($equipamiento->getTranslation('nombre', 'es').' '.$sinonimos));
    }

    /**
     * Crea los ítems del catálogo que falten y pone al día su categoría, ícono y orden.
     * El nombre de los que ya existen no se toca (el cliente pudo haberlo afinado, p. ej. "TV 34\"").
     */
    public static function sincronizar(): void
    {
        foreach (array_keys(self::ITEMS) as $orden => $clave) {
            [$categoria, $icono, $es, $en, $fr] = self::ITEMS[$clave];

            $equipamiento = Equipamiento::firstOrNew(['clave' => $clave]);

            if (! $equipamiento->exists) {
                $equipamiento->nombre = ['es' => $es, 'en' => $en, 'fr' => $fr];
            }

            $equipamiento->fill(['categoria' => $categoria, 'icono' => $icono, 'orden' => $orden])->save();
        }
    }
}
