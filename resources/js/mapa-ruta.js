import L from 'leaflet';
import 'leaflet/dist/leaflet.css';

// Trazado tomado de OpenStreetMap: esquina de la plaza (Carrera con Latorre),
// una cuadra por Latorre y luego Lynch hasta su final, frente a la cabaña.
const RUTA = [
    [-18.196259, -69.559633],
    [-18.196354, -69.559957],
    [-18.196472, -69.560354],
    [-18.195516, -69.560646],
    [-18.19503, -69.560767],
    [-18.194954, -69.560727],
    [-18.194928, -69.560653],
    [-18.194845, -69.560305],
    [-18.194712, -69.560222],
    [-18.194618, -69.560243],
    [-18.194676, -69.560544],
    [-18.1947326, -69.5605207],
];

const TERRACOTA = '#b5502f';
const ANDINO = '#2f4a5c';

export function montar(el, { plaza, alojamiento }) {
    const mapa = L.map(el, {
        scrollWheelZoom: false,
        dragging: !L.Browser.mobile,
        attributionControl: true,
    });

    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
    }).addTo(mapa);

    const linea = L.polyline(RUTA, { color: TERRACOTA, weight: 5, opacity: 0.9, lineCap: 'round', lineJoin: 'round' }).addTo(mapa);

    L.circleMarker(RUTA[0], { radius: 7, color: '#fff', weight: 2, fillColor: ANDINO, fillOpacity: 1 })
        .bindTooltip(plaza, { permanent: true, direction: 'right', offset: [8, 0] })
        .addTo(mapa);

    L.circleMarker(RUTA.at(-1), { radius: 9, color: '#fff', weight: 3, fillColor: TERRACOTA, fillOpacity: 1 })
        .bindTooltip(alojamiento, { permanent: true, direction: 'left', offset: [-10, 0] })
        .addTo(mapa);

    mapa.fitBounds(linea.getBounds(), { padding: [48, 48], maxZoom: 18 });
}
