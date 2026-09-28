CMS de Blog de Pindoor — cómo funciona
La pieza central (y la que más te interesa) es el sistema de imágenes intercaladas por párrafo. Aquí va el resumen de la arquitectura completa para portarla a otro proyecto.

1. Modelo de datos

posts
  titulo, resumen, contenido   (JSON traducible: es/en/fr vía spatie/laravel-translatable)
  slug, autor, imagen_portada, publicado, publicado_en, dynamic_block_title

post_imagenes  (belongsTo Post)
  ruta            — path de la imagen
  orden           — posición dentro de la galería (para el admin: drag & drop)
  posicion        — número de párrafo/bloque tras el cual debe insertarse (nullable)
La clave de diseño: orden y posicion son cosas distintas. orden es el orden de la galería (cosmético, para el panel admin). posicion es el punto de anclaje real dentro del texto — si es null, la imagen se reparte automáticamente; si tiene un número, se fija a ese párrafo.

2. Editor (admin)
Quill.js como WYSIWYG (toolbar reducida: bold/italic/underline, H2/H3, blockquote, listas, link, imagen, align).
El botón de imagen del toolbar no inserta base64: sube el archivo por AJAX a un endpoint (imagenHandler() → fetch a /admin/blog/imagen), y solo inserta la URL resultante como <img> embebido en el contenido. Esto mantiene el HTML liviano y las imágenes servidas/comprimidas igual que cualquier otro asset.
Un panel lateral aparte (no parte del editor) gestiona la galería del post: hasta N slots de imagen con preview, drag & drop para reordenar, y un input numérico por imagen ("tras qué párrafo aparece, vacío = automático").
3. El algoritmo de intercalado (la parte que te gusta)
Se ejecuta dos veces con la misma lógica: en JS (para la vista previa en vivo del admin) y en PHP (al renderizar la página pública). Los pasos:

Trocear el HTML en "bloques": se insertan marcadores tras cada tag de cierre relevante (</p>, </h2>, </h3>, </h4>, </blockquote>, </ul>, </ol>) y se parte el string por ahí. Cada bloque = un párrafo/elemento visual, numerado 1..N.
Separar imágenes en dos grupos: las que tienen posicion fija (van directo al bloque N) y las "automáticas" (posicion = null).
Repartir las automáticas: se calcula un intervalo floor(numBloques / (n_automaticas + 1)) y se van insertando en múltiplos de ese intervalo, saltando los números ya ocupados por imágenes fijas. Las que no alcanzan a caber van al final.
Reconstruir el HTML: se recorre cada bloque, se concatena su texto, y tras cada uno se insertan las imágenes (<figure class="editorial-fig">) asignadas a ese número.
Si una imagen quedó con posicion > número total de bloques (o no hay texto en absoluto), no se inserta inline: cae a un carrusel final con lightbox (Alpine.js).
El panel de preview del admin (columna derecha del formulario) corre el mismo cálculo en JS en tiempo real, mostrando cada bloque de texto truncado + miniatura de la imagen que caería ahí (marcando "auto" vs manual), para que el editor vea exactamente dónde va a quedar cada foto antes de guardar.

4. Guardado
El submit del form manda:

galeria_orden[]: tokens tipo existente:{id} / nueva:{slot} en el orden final tras el drag & drop.
posicion_existente_{id} / posicion_nueva_{slot}: el número de párrafo por imagen.
El controller recorre galeria_orden[], asigna orden incremental, sube las nuevas (con un servicio de compresión de imágenes), y guarda posicion tal cual venga (o null si el campo estaba vacío → automática).

5. Extras del CMS que vale la pena copiar
Multi-idioma real: tabs ES/EN/FR sobre los mismos campos (título, resumen, contenido), guardado como JSON traducible; slug e imágenes son compartidos entre idiomas. Incluye botón "auto-traducir" que llama a una API de traducción gratuita, troceando el texto en oraciones y traduciendo nodo por nodo para no perder negritas/links/headers.
Borrador/Publicado con toggle, y publicado_en que se setea solo al primer publish.
Preview sin publicar: link con token para compartir el borrador antes de publicarlo.
Entidades relacionadas (lugares/rutas mencionados en el post) vía Alpine.js con buscador + filtro, generando mentions en JSON-LD para SEO.
Contador de caracteres en vivo para resumen (SEO/tarjetas) y contenido, con corte duro al límite.
Para portarlo a otro proyecto
Lo mínimo reusable, sin nada de esto acoplado a Pindoor: modelo Post + PostImagen(post_id, ruta, orden, posicion), Quill con handler de subida AJAX, y la función de troceo/reparto de bloques (existe en dos copias casi idénticas — JS para preview, PHP para render — así que al portarlo conviene extraerla a una sola fuente de verdad, por ejemplo generando el preview también vía un endpoint que corra el mismo PHP, en vez de duplicar el algoritmo en JS).